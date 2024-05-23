<?php

namespace App\Console\Commands;

use App\Models\Api\ArchiveNode;
use App\Models\Api\Exorde;
use App\Models\Api\Node;
use App\Models\Api\Server;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpseclib3\Net\SSH2;

class NotifyDmitrii extends Command
{
    public ?string $result = null;
    public ?int $offlineNibiru = null;
    public ?int $offlineLava = null;
    public ?int $offlineArchive = null;
    public ?int $offlineExorde = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifydm:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->result = '';

        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Nibiru')->get();
        $nodesCountNibiru = count($nodes);
        $this->updateNibiru();

        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Lava')->get();
        $nodesCountLava = count($nodes);
        $this->updateLava();

        /** @var Exorde[] $nodes */
        $nodes = Exorde::all();
        $nodesCountExorde = count($nodes);
        $this->updateExorde();

        $archiveNodes = ArchiveNode::all();
        $archiveNodesCount = count($archiveNodes);
        $this->updateArchive();

        $this->result = $this->result . 'Nibiru - ' . ($nodesCountNibiru - $this->offlineNibiru) . ' / ' . $nodesCountNibiru . "\n";
        $this->result = $this->result . 'Lava - ' . ($nodesCountLava - $this->offlineLava) . ' / ' . $nodesCountLava . "\n";
        $this->result = $this->result . 'Exorde - ' . ($nodesCountExorde - $this->offlineExorde) . ' / ' . $nodesCountExorde . "\n";
        $this->result = $this->result . 'Archive Nodes - ' . ($archiveNodesCount - $this->offlineArchive) . ' / ' . $archiveNodesCount;

        $user = User::find(1);
        $notification = new TelegramNotification();
        $notification->text = $this->result;
        $user->notify($notification);

//        $user = User::find(2);
//        $notification = new TelegramNotification();
//        $notification->text = $this->result;
//        $user->notify($notification);
//
//        $user = User::find(5);
//        $notification = new TelegramNotification();
//        $notification->text = $this->result;
//        $user->notify($notification);
    }

    public function updateNibiru()
    {
        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Nibiru')->get();
        $this->offlineNibiru = 0;

        foreach ($nodes as $node) {
            try {
                $responseObject = Http::get($node->location_url)->object();
            } catch (ConnectionException $e) {
                $this->result = $this->result . 'Nibiru ' . $node->location_name . ' is down - check the node' . "\n";
                $this->offlineNibiru++;
                $server = Server::where('name', $node->location_name)->first();
                $ssh = new SSH2($server->address);
                $ssh->login('root', Crypt::decrypt($server->password));
                $ssh->exec("systemctl restart nibid && journalctl -u nibid -f -o cat");
                continue;
            }
            if (intval($responseObject->result->sync_info->latest_block_height) == intval($node->last_block)) {
                $this->result = $this->result . 'Nibiru ' . $node->location_name . ' is down - check the node' . "\n";
                $this->offlineNibiru++;
                $server = Server::where('name', $node->location_name)->first();
                $ssh = new SSH2($server->address);
                $ssh->login('root', Crypt::decrypt($server->password));
                $ssh->exec("systemctl restart nibid && journalctl -u nibid -f -o cat");
            }
        }
    }

    public function updateLava()
    {
        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Lava')->get();
        $this->offlineLava = 0;

        foreach ($nodes as $node) {
            try {
                $responseObject = Http::get($node->location_url)->object();
            } catch (ConnectionException $e) {
                $this->result = $this->result . 'Lava ' . $node->location_name . ' is down - check the node' . "\n";
                $this->offlineLava++;
                $server = Server::where('name', $node->location_name)->first();
                $ssh = new SSH2($server->address);
                $ssh->login('root', Crypt::decrypt($server->password));
                $ssh->exec("systemctl restart lavad && journalctl -u lavad -f -o cat");
                continue;
            }
            if (intval($responseObject->result->sync_info->latest_block_height) == intval($node->last_block)) {
                $this->result = $this->result . 'Lava ' . $node->location_name . ' is down - check the node' . "\n";
                $this->offlineLava++;
                $server = Server::where('name', $node->location_name)->first();
                $ssh = new SSH2($server->address);
                $ssh->login('root', Crypt::decrypt($server->password));
                $ssh->exec("systemctl restart lavad && journalctl -u lavad -f -o cat");
            }
        }
    }

    public function updateExorde()
    {
        /** @var Exorde[] $nodes */
        $nodes = Exorde::all();
        $this->offlineExorde = 0;

        foreach ($nodes as $node) {
            if (!$node->is_sync) {
                $this->offlineExorde++;
            }
        }

    }

    public function updateArchive()
    {
        $this->updateEvm('Ethereum');
        $this->updateEvm('Arbitrum');
        $this->updateCosmos('Osmosis');
        $this->updateCosmos('Evmos');
        $this->updateStarknet();
        $this->updateAptos();
    }

    public function updateEvm(string $projectName)
    {
        $archiveNode = ArchiveNode::where('name', $projectName)->first();

        try {
            $responseObject = Http::post(
                $archiveNode->url, [
                    'jsonrpc' => '2.0',
                    'method' => 'eth_blockNumber',
                    'params' => [],
                    'id' => 1
                ]
            )->object();

            $responsePublicObject = Http::post(
                $archiveNode->public_rpc_url, [
                    'jsonrpc' => '2.0',
                    'method' => 'eth_blockNumber',
                    'params' => [],
                    'id' => 1
                ]
            )->object();
        } catch (ConnectionException $e) {
            $this->offlineArchive++;
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            return null;
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = hexdec($responseObject->result);
        $archiveNode->real_block = hexdec($responsePublicObject->result);
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            $this->offlineArchive++;
        }
    }

    public function updateCosmos(string $projectName)
    {
        /** @var ArchiveNode $archiveNode */
        $archiveNode = ArchiveNode::where('name', $projectName)->first();

        try {
            $responseObject = Http::get($archiveNode->url)->object();
            $responsePublicObject = Http::get($archiveNode->public_rpc_url)->object();
        } catch (ConnectionException $e) {
            $this->offlineArchive++;
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            return null;
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = $responseObject->result->sync_info->latest_block_height;
        $archiveNode->real_block = $responsePublicObject->result->sync_info->latest_block_height;
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            $this->offlineArchive++;
        }
    }

    public function updateStarknet()
    {
        $archiveNode = ArchiveNode::where('name', 'Starknet')->first();

        try {
            $responseObject = Http::post(
                $archiveNode->url, [
                    'jsonrpc' => '2.0',
                    'method' => 'starknet_blockNumber',
                    'params' => [],
                    'id' => 1
                ]
            )->object();

            $responsePublicObject = Http::post(
                $archiveNode->public_rpc_url, [
                    'jsonrpc' => '2.0',
                    'method' => 'starknet_blockNumber',
                    'params' => [],
                    'id' => 1
                ]
            )->object();
        } catch (ConnectionException $e) {
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            $this->offlineArchive++;
            return null;
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = $responseObject->result;
        $archiveNode->real_block = $responsePublicObject->result;
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->offlineArchive++;
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
        }
    }

    public function updateAptos()
    {
        $archiveNode = ArchiveNode::where('name', 'Aptos')->first();
        try {
            $responseObject = Http::get($archiveNode->url)->body();
        } catch (ConnectionException $e) {
            $this->offlineArchive++;
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
            return null;
        }

        $objectsArray = explode("\n", $responseObject);
        $block = 0;

        foreach ($objectsArray as $object) {
            $s = strpos($object, 'synced');

            if ($s) {
                $block = explode(" ", $object)[1];
                break;
            }
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = $block;
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->offlineArchive++;
            $this->result = $this->result . 'Archive Node ' . $archiveNode->name . ' is down - check the node' . "\n";
        }
    }
}

