<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ArchiveNode;
use App\Models\Api\Balance;
use App\Models\Api\Exorde;
use App\Models\Api\Node;
use App\Models\Api\Server;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpseclib3\Exception\UnableToConnectException;
use phpseclib3\Net\SSH2;

class BalanceController extends Controller
{
    public function index(): Response|Application|ResponseFactory
    {
        $servers = Server::all();
        if (!$servers) {
            return response(
                [
                    'servers' => [],
                ]);
        }

        $result = [];
        /** @var Server $server */
        foreach ($servers as $server) {
            $result [] = $server;
        }

        return response(
            [
                'servers' => $result,
            ]);
    }

    public function archiveNodes(): Response|Application|ResponseFactory
    {
        $result = [];
        $nodesCount = 8;
        $this->offline = 0;

        $result [] = $this->getEvmData('Ethereum');
        $result [] = $this->getEvmData('Arbitrum');
        $result [] = $this->getEvmData('Celo');
        $result [] = $this->getEvmData('Goerli');
        $result [] = $this->getCosmosData('Evmos');
        $result [] = $this->getCosmosData('Osmosis');
        $result [] = $this->getStarknetData();
        $result [] = $this->getAptosData();

        return response(
            [
                'all' => $nodesCount,
                'online' => $nodesCount - $this->offline,
                'result' => $result,
            ]);
    }

    public function nibiru(): Response|Application|ResponseFactory
    {
        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Nibiru')->get();
        $nodesCount = count($nodes);
        $online = 0;
        $result = [];
        foreach ($nodes as $node) {
            try {
                $responseObject = Http::get($node->location_url)->object();
            } catch (ConnectionException $e) {
                try {
                    $server = Server::where('name', $node->location_name)->first();
                    $ssh = new SSH2($server->address);
                    $ssh->login('root', Crypt::decrypt($server->password));
                    $ssh->exec("systemctl restart nibid && journalctl -u nibid -f -o cat");
                } catch (UnableToConnectException $e) {
                    continue;
                }
            }

            $result [] = [
                'isSync' => !($responseObject->result->sync_info->catching_up),
                'lastBlock' => $responseObject->result->sync_info->latest_block_height,
                'nodeName' => $node->name,
                'location' => $node->location_name,
            ];

            $node->last_block = $responseObject->result->sync_info->latest_block_height;
            $node->save();

            if (!($responseObject->result->sync_info->catching_up)) {
                $online++;
            }
        }
        return response(
            [
                'all' => $nodesCount,
                'online' => $online,
                'result' => $result,
            ]);
    }

    public function lava(): Response|Application|ResponseFactory
    {
        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Lava')->get();
        $nodesCount = count($nodes);
        $online = 0;
        $result = [];
        foreach ($nodes as $node) {
            try {
                $responseObject = Http::get($node->location_url)->object();
            } catch (ConnectionException $e) {
                $server = Server::where('name', $node->location_name)->first();
                $ssh = new SSH2($server->address);
                $ssh->login('root', Crypt::decrypt($server->password));
                $ssh->exec("systemctl restart lavad && journalctl -u lavad -f -o cat");
                continue;
            }
            $result [] = [
                'isSync' => !($responseObject->result->sync_info->catching_up),
                'lastBlock' => $responseObject->result->sync_info->latest_block_height,
                'nodeName' => $node->name,
                'location' => $node->location_name,
            ];

            if (!($responseObject->result->sync_info->catching_up)) {
                $online++;
            }
        }
        return response(
            [
                'all' => $nodesCount,
                'online' => $online,
                'result' => $result,
            ]);
    }

    public function exorde(): Response|Application|ResponseFactory
    {
        $offline = 0;
        $exordes = Exorde::orderBy('rank')->get();
        $result = [];

        /** @var Exorde $exorde */
        foreach ($exordes as $exorde) {
            if (!$exorde->is_sync) {
                $offline++;
            }
            $result[] = [
                'ip' => $exorde->ip,
                'location' => $exorde->location,
                'address' => $exorde->address,
                'previousReputation' => $exorde->previous_reputation,
                'reputation' => $exorde->current_reputation,
                'isSync' => (bool)$exorde->is_sync,
                'rank' => $exorde->rank,
            ];
        }

        return response(
            [
                'all' => 25,
                'online' => 25 - $offline,
                'result' => $result,
            ]);
    }

    public function taiko()
    {
        /** @var Node[] $nodes */
        $nodes = Node::where('project_name', 'Taiko')->get();
        $nodesCount = count($nodes);
        $online = 0;
        $result = [];

        foreach ($nodes as $node) {
            try {
                $responseObject = Http::post(
                    $node->location_url, [
                        'jsonrpc' => '2.0',
                        'method' => 'eth_blockNumber',
                        'params' => [],
                        'id' => 0
                    ]
                )->object();
            } catch (ConnectionException $e) {
//                $server = Server::where('name', $node->location_name)->first();
//                $ssh = new SSH2($server->address);
//                $ssh->login('root', Crypt::decrypt($server->password));
//                $ssh->exec("systemctl restart lavad && journalctl -u lavad -f -o cat");
                continue;
            }

            $node->is_sync = $node->last_block < hexdec($responseObject->result);
            $node->last_block = hexdec($responseObject->result);
            $node->save();

            $result [] = [
                'isSync' => $node->is_sync,
                'lastBlock' => $node->last_block,
                'nodeName' => $node->name,
                'location' => $node->location_name,
            ];

            if ($node->is_sync) {
                $online++;
            }
        }

        return response(
            [
                'all' => $nodesCount,
                'online' => $online,
                'result' => $result,
            ]);
    }

    public function getEvmData(string $projectName): array
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
            $this->offline++;
            return [
                'nodeName' => $archiveNode->name,
                'location' => $archiveNode->location,
                'isSync' => false,
                'lastBlock' => $archiveNode->last_block,
                'previousBlock' => $archiveNode->previous_block,
                'realBlock' => $archiveNode->real_block,
            ];
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = hexdec($responseObject->result);
        $archiveNode->real_block = hexdec($responsePublicObject->result);
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->offline++;
        }

        return [
            'nodeName' => $archiveNode->name,
            'location' => $archiveNode->location,
            'isSync' => $archiveNode->is_sync,
            'lastBlock' => $archiveNode->last_block,
            'previousBlock' => $archiveNode->previous_block,
            'realBlock' => $archiveNode->real_block,
        ];
    }

    public function getCosmosData(string $projectName): array
    {
        /** @var ArchiveNode $archiveNode */
        $archiveNode = ArchiveNode::where('name', $projectName)->first();

        try {
            $responseObject = Http::get($archiveNode->url)->object();
            $responsePublicObject = Http::get($archiveNode->public_rpc_url)->object();
        } catch (ConnectionException $e) {
            $this->offline++;
            return [
                'nodeName' => $archiveNode->name,
                'location' => $archiveNode->location,
                'isSync' => false,
                'lastBlock' => $archiveNode->last_block,
                'previousBlock' => $archiveNode->previous_block,
                'realBlock' => $archiveNode->real_block,
            ];
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = $responseObject->result->sync_info->latest_block_height;
        $archiveNode->real_block = $responsePublicObject->result->sync_info->latest_block_height;
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->offline++;
        }

        return [
            'nodeName' => $archiveNode->name,
            'location' => $archiveNode->location,
            'isSync' => $archiveNode->is_sync,
            'lastBlock' => $archiveNode->last_block,
            'previousBlock' => $archiveNode->previous_block,
            'realBlock' => $archiveNode->real_block,
        ];
    }

    public function getStarknetData(): array
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
            $this->offline++;
            return [
                'nodeName' => $archiveNode->name,
                'location' => $archiveNode->location,
                'isSync' => false,
                'lastBlock' => $archiveNode->last_block,
                'previousBlock' => $archiveNode->previous_block,
                'realBlock' => $archiveNode->real_block,
            ];
        }

        $archiveNode->previous_block = $archiveNode->last_block;
        $archiveNode->last_block = $responseObject->result;
        $archiveNode->real_block = $responsePublicObject->result;
        $archiveNode->is_sync = $archiveNode->last_block > $archiveNode->previous_block;
        $archiveNode->save();

        if (!$archiveNode->is_sync) {
            $this->offline++;
        }

        return [
            'nodeName' => $archiveNode->name,
            'location' => $archiveNode->location,
            'isSync' => $archiveNode->is_sync,
            'lastBlock' => $archiveNode->last_block,
            'previousBlock' => $archiveNode->previous_block,
            'realBlock' => $archiveNode->real_block,
        ];
    }

    public function getAptosData(): array
    {
        $archiveNode = ArchiveNode::where('name', 'Aptos')->first();
        try {
            $responseObject = Http::get($archiveNode->url)->body();
        } catch (ConnectionException $e) {
            $this->offline++;
            return ['nodeName' => $archiveNode->name,
                'location' => $archiveNode->location,
                'isSync' => false,
                'lastBlock' => $archiveNode->last_block,
                'previousBlock' => $archiveNode->previous_block,
                'realBlock' => $archiveNode->real_block,
            ];
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

        return [
            'nodeName' => $archiveNode->name,
            'location' => $archiveNode->location,
            'isSync' => $archiveNode->is_sync,
            'lastBlock' => $archiveNode->last_block,
            'previousBlock' => $archiveNode->previous_block,
            'realBlock' => $archiveNode->real_block,
        ];
    }

    public function balance(): array
    {
        $server = Server::find(23);
        if (!$server) {
            return [];
        }

        $ssh = new SSH2($server->address);
        $ssh->login('root', Crypt::decrypt($server->password));
        $output = explode("\n", $ssh->exec('cd tstest/build/ && node getBalance.js'));

        $balance = new Balance();
        $balance->amount = floatval($output[0]);
        $balance->save();

        return [
            'output' => $balance->amount,
        ];
    }

    public function balancePost(\Illuminate\Http\Request $request): array
    {
        $balance = new Balance();
        $balance->amount = floatval($request->post()['amount']);
        $balance->save();
        return ['data' => $balance->amount];
    }

    public function test(): array
    {
        return [
            'output' => 'hello'
        ];
    }

    public function swap(): array
    {

    }
}
