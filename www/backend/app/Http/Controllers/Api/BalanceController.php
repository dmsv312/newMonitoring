<?php

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Balance;
use App\Models\Api\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use phpseclib3\Net\SSH2;

class BalanceController extends Controller
{
    public function balance(): array
    {
        // TODO - find current server
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

    public function saveBalance(Request $request): array
    {
        $balance = new Balance();
        $balance->amount = floatval($request->post()['amount']);
        $balance->save();
        return ['data' => $balance->amount];
    }
}
