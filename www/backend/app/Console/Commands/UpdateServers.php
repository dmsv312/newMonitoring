<?php

namespace app\Console\Commands;

use App\Models\Api\Server;
use Illuminate\Console\Command;
use phpseclib3\Net\SSH2;
use Illuminate\Support\Facades\Crypt;

class UpdateServers extends Command
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
        $servers = Server::all();
        if (!$servers) {
            return null;
        }

        /** @var Server $server */
        foreach ($servers as $server) {
            $ssh = new SSH2($server->address);
            $ssh->login('root', Crypt::decrypt($server->password));
            $output = explode("\n", $ssh->exec('./free.sh'));
            $server->ram_free = $output[1];
            $server->cpu_usage = intval($output[2]);
            $server->ram_total = $output[3];
            $server->space_free = $output[4];
            $server->space_total = $output[5];
            $server->save();
        }
    }
}
