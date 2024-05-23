<?php

namespace app\Console\Commands;

use App\Models\Api\Exorde;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateExorde extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exorde:cron';

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
        $responseObjects = Http::get('https://raw.githubusercontent.com/exorde-labs/TestnetProtocol/main/Stats/leaderboard.json')->object();
        $responseObjects = json_decode(json_encode($responseObjects), true);
        $responseValues = array_values($responseObjects);

        $exordes = Exorde::all();
        /** @var Exorde $exorde */
        foreach ($exordes as $exorde) {
            if (array_key_exists($exorde->address, $responseObjects)) {
                $exorde->previous_reputation = $exorde->current_reputation;
                $exorde->current_reputation = $responseObjects[$exorde->address];
                $exorde->is_sync = $exorde->current_reputation > $exorde->previous_reputation;
                $exorde->rank = array_search($responseObjects[$exorde->address], $responseValues) + 1;
                $exorde->save();
            }
        }
    }
}
