<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Balance;
use App\Models\Api\Server;
use App\Models\Api\Token;
use App\Models\Api\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use phpseclib3\Net\SSH2;

class TransactionController extends Controller
{
//    public const ETH_AMOUNT = [0.01, 0.02, 0.03, 0.04, 0.05];
    public const ETH_AMOUNT = [0.01, 0.02, 0.03];
    public const TOKEN_IDS = [2, 3];

    public static function nextTransaction(): array
    {
        /** @var Transaction $nextTransaction */
        $nextTransaction = Transaction::orderBy('id')->where('is_complete', 0)->first();
        $tokenTo = Token::find($nextTransaction->token_id_to);
        $tokenFrom = Token::find($nextTransaction->token_id_from);

        return [
            'token_id_to' => $tokenTo->id,
            'token_id_from' => $tokenFrom->id,
            'transaction_id' => $nextTransaction->id,
            'amount' => $nextTransaction->amount,
        ];
    }

    public function saveTransaction(Request $request): array
    {
        $id = intval($request->post()['transaction_id']);
        // Save previous transaction
        /** @var Transaction $transaction */
        $transaction = Transaction::find($id);
        $transaction->hash = '';
        $transaction->is_complete = true;
        $transaction->save();

//        // create new Transaction item with is_complete = false and without hash;
//        // ETH token id = 1
//        // USDT token id = 2
//        $nextTransaction = new Transaction();
//        // Next transaction from USDT to ETH
//        if ($request->post()['token_id_from'] == 1) {
//            $nextTransaction->token_id_from = $request->post()['token_id_to'];
//            $nextTransaction->token_id_to = 1;
//            // TODO - how to update balance
//            $nextTransaction->amount = 1;
//        }
//        // Next transaction from ETH to USDT
//        else {
//            $nextTransaction->token_id_from = 1;
//            $randomToken = self::TOKEN_IDS[array_rand(self::TOKEN_IDS)];
//            $nextTransaction->token_id_to = $randomToken;
//            $randomAmount = self::ETH_AMOUNT[array_rand(self::ETH_AMOUNT)];
//            $nextTransaction->amount = $randomAmount;
//            // TODO - random stable token, for first - USDT
//        }
//
//        $nextTransaction->is_complete = false;
//        $nextTransaction->save();

        return ['result' => 'success'];
    }

    public function test(): array
    {

        return ['hello' => 'hello'];
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
