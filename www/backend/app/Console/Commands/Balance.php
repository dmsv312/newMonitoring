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

class Balance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get balance for different tokens';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $random = random_int(1, 58);
        sleep($random);

        // TODO - change for current server
        $server = Server::find(23);
        $ssh = new SSH2($server->address);
        $ssh->login('root', Crypt::decrypt($server->password));

        $output1 = explode("\n", $ssh->exec('cd tstest/build/ && node getBalanceWeth.js'));

        $wethBalance = \App\Models\Api\Balance::find(1);
        $wethBalance->amount = $output1[0];
        $wethBalance->save();

        $output2 = explode("\n", $ssh->exec('cd tstest/build/ && node getBalanceUsdt.js'));

        $usdtBalance = \App\Models\Api\Balance::find(2);
        $usdtBalance->amount = $output2[0];
        $usdtBalance->save();
    }
}

