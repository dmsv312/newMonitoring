<?php

namespace App\Console\Commands;

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

class PlanSchedule extends Command
{
    public const FORGE_EVMOS_USDC = 'forge:evmosusdc';
    public const FORGE_USDC_EVMOS = 'forge:usdcevmos';
    public const SQUID_AXL_USDC = 'squid:axlusdc';
    public const SQUID_USDC_AXL = 'squid:usdcaxl';
    public const IBC_EVMOS_AXL = 'ibc:evmosaxl';
    public const IBC_AXL_EVMOS = 'ibc:axlevmos';

    public $slots = [
        1 => 6,
        2 => 7,
        3 => 8,
        4 => 9,
        5 => 10,
        6 => 11,
    ];

    public $modules = [
        'forge' => ['forge:evmosusdc', 'forge:usdcevmos'],
        'squid' => ['squid:axlusdc', 'squid:usdcaxl'],
        'ibc' => ['ibc:evmosaxl', 'ibc:axlevmos'],
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:schedule';

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
        $dayOfWeek = getdate()['wday'];
        shuffle($this->modules);

        $task = Task::firstOrNew(['name' => $this->modules[0][0]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[1] . ' * * ' . $dayOfWeek;
        $task->save();

        $task = Task::firstOrNew(['name' => $this->modules[0][1]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[2] . ' * * ' . $dayOfWeek;
        $task->save();

        $task = Task::firstOrNew(['name' => $this->modules[1][0]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[3] . ' * * ' . $dayOfWeek;
        $task->save();

        $task = Task::firstOrNew(['name' => $this->modules[1][1]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[4] . ' * * ' . $dayOfWeek;
        $task->save();

        $task = Task::firstOrNew(['name' => $this->modules[2][0]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[5] . ' * * ' . $dayOfWeek;
        $task->save();

        $task = Task::firstOrNew(['name' => $this->modules[2][1]]);
        $task->time = rand(1, 57) . ' ' . $this->slots[6] . ' * * ' . $dayOfWeek;
        $task->save();
//
//        /** @var Task $swapTask */
//        $swapTask = Task::orderBy('id', 'DESC')->where('name', 'swap:cron')->first();
//        $swapTask->calculateAndSaveNextSwapTime();
    }
}

