<?php

namespace App\Console;

use App\Shared\Enums\EnvironmentEnum;
use App\Shared\Enums\QueueEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class CreateQueueRabbitmq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:create-queue-rabbitmq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando exclusivo para criação de filas padrão para o RabbitMQ.';

    /**
     * @return void
     */
    public function handle(): void
    {
        if (App::environment([EnvironmentEnum::LOCAL->value, EnvironmentEnum::STAGING->value])) {
            foreach (QueueEnum::cases() as $queue) {
                echo "Criando a fila: {$queue->value}\n";
                Artisan::call("rabbitmq:queue-declare {$queue->value}");
            }
        }
    }
}
