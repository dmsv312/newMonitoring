<?php

namespace App\Console\Commands;

use App\Models\Api\ArchiveNode;
use App\Models\Api\Exorde;
use App\Models\Api\Node;
use App\Models\Api\Server;
use App\Models\Api\Task;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpseclib3\Net\SSH2;

class NotifyDmitrii extends Command
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
    protected $signature = 'notifydm:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        // TODO - sleep for random second, because cron doesnt have second value
        $random = random_int(1, 58);
        sleep($random);
        $user = User::find(1);
        $notification = new TelegramNotification();
//        $notification->text = $this->result;
        $notification->text = '1 min';
        $user->notify($notification);

        /** @var Task $task */
        $task = Task::orderBy('id', 'DESC')->first();
        $task->calculateAndSaveNextSwapTime();
    }
}

