<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use phpseclib3\Net\SSH2;

class IbcAxlEvmos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibc:axlevmos';

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
        exec('cd $HOME/pythontest/ && python3 ibc_axl_evmos.py');
    }
}

