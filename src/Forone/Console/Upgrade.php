<?php namespace Forone\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Upgrade extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Database Upgrade Table Start...');

        $this->call('db:clear');
        $this->call('migrate');
        $this->info('Database Upgraded');
        $this->call('db:seed');

        $this->info('Database Upgrade End');
    }

}
