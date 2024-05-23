<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use app\Models\Api\Balance;
use App\Models\Api\Server;
use App\Models\Api\Token;
use App\Models\Api\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use phpseclib3\Net\SSH2;

class TransactionController extends Controller
{
    public const ETH_AMOUNT = [0.01, 0.02, 0.03, 0.04, 0.05];
    public static function nextTransaction(): array
    {
        /** @var Transaction $nextTransaction */
        $nextTransaction = Transaction::orderBy('id', 'DESC')->first();
        $tokenTo = Token::find($nextTransaction->token_id_to);
        $tokenFrom = Token::find($nextTransaction->token_id_from);

        return [
            'token_contract_from' => $tokenFrom->address,
            'token_contract_to' => $tokenTo->address,
            'token_id_to' => $tokenTo->id,
            'token_id_from' => $tokenFrom->id,
            'transaction_id' => $nextTransaction->id,
        ];
    }

    public function saveTransaction(Request $request): array
    {
        // Save previous transaction
        $transaction = Transaction::where(['id' => $request->post()['transaction_id']]);
        $transaction->hash = floatval($request->post()['hash']);
        $transaction->is_complete = true;
        $transaction->save();

        // create new Transaction item with is_complete = false and without hash;
        // ETH token id = 1
        // USDT token id = 2
        $nextTransaction = new Transaction();
        // Next transaction from USDT to ETH
        if ($request->post()['token_id_from'] == 1) {
            $nextTransaction->token_id_from = 2;
            $nextTransaction->token_id_to = 1;
            $balance = Balance::find(2);
            $nextTransaction->amount = $balance->amount;
        }
        // Next transaction from ETH to USDT
        else {
            $nextTransaction->token_id_from = 1;
            $nextTransaction->token_id_to = 2;
            $randomAmount = self::ETH_AMOUNT[array_rand(self::ETH_AMOUNT)];
            $nextTransaction->amount = $randomAmount;
            // TODO - random stable token, for first - USDT
        }
        $nextTransaction->is_complete = false;

        return ['data' => 'hello'];
    }

    public function test(): array
    {
        $output = [];
        exec('cd $HOME && cd tstest/build/ && node getBalanceEth.js', $output, $code);
        return ['result' => $output, 'code' => $code];
    }

    public function swap(): array
    {
        $server = Server::find(23);
        if (!$server) {
            return [];
        }

        $ssh = new SSH2($server->address);
        $ssh->login('root', Crypt::decrypt($server->password));
        $output = explode("\n", $ssh->exec('cd tstest/build/ && node swap.js'));

        $transactionParams = TransactionController::nextTransaction();
        $transaction = new Transaction();
        $transaction->token_id_from = 2;
        $transaction->token_id_to = $transactionParams['token_id_to'];
        $transaction->amount = 0.00001;
        $transaction->hash = $output[1];
        $transaction->save();

        return [
            'output' => $output[0],
            'output2' => $output[1],
        ];
    }
}
