<?php

namespace app\Console\Commands;

use App\Models\Api\Server;
use Illuminate\Console\Command;
use phpseclib3\Net\SSH2;
use Illuminate\Support\Facades\Crypt;

class TestNode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:cron';

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
        $server = Server::find(23);
        if (!$server) {
            return null;
        }

        $ssh = new SSH2($server->address);
        $ssh->login('root', Crypt::decrypt($server->password));
        $output = explode("\n", $ssh->exec('cd tstest/ && node test.js'));
    }
}
