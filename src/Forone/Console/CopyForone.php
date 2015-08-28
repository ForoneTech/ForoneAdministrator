<?php namespace Forone\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyForone extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'forone:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy forone files for customize';

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
        if($this->confirm('Copy routes? This will override app/Http/routes.php file, please backup that first')){
            copy('vendor/forone/administrator/src/Forone/routes.php', 'app/Http/routes.php');
            $config = file_get_contents('config/forone.php');
            $config = str_replace("'disable_routes' => false", "'disable_routes' => true", $config);
            file_put_contents('config/forone.php', $config);
        }
    }

}
