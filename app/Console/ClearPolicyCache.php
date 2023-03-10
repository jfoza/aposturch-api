<?php

namespace App\Console;

use App\Shared\Cache\PolicyCache;
use Illuminate\Console\Command;

class ClearPolicyCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:clear-policy-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para limpeza do cache de regras(policy) de todos os usuários.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        PolicyCache::invalidateAllPolicy();

        info('Cache de regras(policy) limpo.');

        echo "Cache de regras(policy) limpo!\n";
    }
}
