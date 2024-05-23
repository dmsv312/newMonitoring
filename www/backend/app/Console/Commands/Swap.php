<?php

namespace app\Console\Commands;

use App\Models\Api\ArchiveNode;
use App\Models\Api\Exorde;
use App\Models\Api\Node;
use App\Models\Api\Server;
use App\Models\Api\Token;
use App\Models\Api\Transaction;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpseclib3\Net\SSH2;

class Swap extends Command
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
    protected $signature = 'swap:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function nextTransaction(): array
    {
        $tokens = Token::all();
        $array = [];
        /** @var Token $token */
        foreach ($tokens as $token) {
            $array [] = $token->address;
        }

        $index = array_rand($array);

        $token = Token::where(['address' => $array[$index]])->first();

        return [
            'token_contract_from' => '0x20b28B1e4665FFf290650586ad76E977EAb90c5D',
            'token_contract_to' => $array[$index],
            'token_id_to' => $token->id,
        ];
    }
    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
//        $server = Server::find(23);
//        if (!$server) {
//            return [];
//        }
//
//        $ssh = new SSH2($server->address);
//        $ssh->login('root', Crypt::decrypt($server->password));
//        $output = explode("\n", $ssh->exec('cd tstest/build/ && node swap.js'));
//
//        $transactionParams = $this->nextTransaction();
//        $transaction = new Transaction();
//        $transaction->token_id_from = 2;
//        $transaction->token_id_to = $transactionParams['token_id_to'];
//        $transaction->amount = 0.00001;
//        $transaction->hash = $output[1];
//        $transaction->save();
//
//        return [
//            'output' => $output[0],
//            'output2' => $output[1],
//        ];
    }
}

