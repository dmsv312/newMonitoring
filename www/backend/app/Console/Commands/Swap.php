<?php

namespace app\Console\Commands;

use App\Models\Api\ArchiveNode;
use App\Models\Api\Exorde;
use App\Models\Api\Node;
use App\Models\Api\Server;
use App\Models\Api\Task;
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

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        /** @var Transaction $nextTransaction */
        $nextTransaction = Transaction::orderBy('id')->where('is_complete', 0)->first();
        $output = [];

        // exec("if pgrep 'firefo[x]'; then echo 'OK'; fi", $outpupt);

        // if (isset($output[0])) {
        //     $nextTransaction->hash = $output[0];
        // } else {
        //     $nextTransaction->hash = 'empty';
        //     exec('cd $HOME/pythontest/ && python3 open_firefox.py');
        // }
        
        exec('cd $HOME/pythontest/ && python3 open_firefox.py');
        
        if ($nextTransaction->token_id_from == 1 && $nextTransaction->token_id_to == 2) {
            exec('cd $HOME/pythontest/ && python3 eth_to_usdc.py', $output);
            print_r($output);
        }
        if ($nextTransaction->token_id_from == 1 && $nextTransaction->token_id_to == 3) {
            exec('cd $HOME/pythontest/ && python3 eth_to_usdt.py', $output);
            print_r($output);
        }
        if ($nextTransaction->token_id_from == 2 && $nextTransaction->token_id_to == 1) {
            exec('cd $HOME/pythontest/ && python3 usdc_to_eth.py', $output);
            print_r($output);
        }
        if ($nextTransaction->token_id_from == 3 && $nextTransaction->token_id_to == 1) {
            exec('cd $HOME/pythontest/ && python3 usdt_to_eth.py', $output);
            print_r($output);
        }
//
//        /** @var Task $swapTask */
//        $swapTask = Task::orderBy('id', 'DESC')->where('name', 'swap:cron')->first();
//        $swapTask->calculateAndSaveNextSwapTime();
    }
}

