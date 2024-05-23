<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use phpseclib3\Net\SSH2;
use App\Models\Api\Task;


class SquidUsdcAxl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'squid:usdcaxl';

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
        exec('cd $HOME/pythontest/ && python3 open_firefox_keplr.py');
        exec('cd $HOME/pythontest/ && python3 squid_usdc_axl.py');
        $squidAxlUsdc = Task::where('name', 'squid:axlusdc')->first();
        $squidUsdcAxl = Task::where('name', 'squid:usdcaxl')->first();

        $hourFirstTask = intval(explode(' ', $squidAxlUsdc->time)[1]);
        $hourSecondTask = intval(explode(' ', $squidUsdcAxl->time)[1]);

        if ($hourFirstTask == 23) {
            $hourFirstTask = 0;
        } else {
            $hourFirstTask = $hourFirstTask + 1;
        }

        $minute = rand(3, 40);
        $minute2 = $minute + rand(7, 15);

        if ($hourSecondTask == 23) {
            $hourSecondTask = 0;
        } else {
            $hourSecondTask = $hourSecondTask + 1;
        }

        $squidAxlUsdc->time = $minute . ' ' . $hourFirstTask . ' * * *';
        $squidUsdcAxl->time = $minute2 . ' ' . $hourSecondTask . ' * * *';
        $squidAxlUsdc->save();
        $squidUsdcAxl->save();
    }
}

