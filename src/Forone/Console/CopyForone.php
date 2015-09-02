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
        function copyr($source, $dest)
        {
            if (is_link($source)) {
                return symlink(readlink($source), $dest);
            }

            if (is_file($source)) {
                $copied = copy($source, $dest);
                $content = file_get_contents($dest);
                $content = str_replace("Forone\Admin\Controllers", "App\Http\Controllers\Forone\Admin\Controllers", $content);
                file_put_contents($dest, $content);
                return $copied;
            }

            if (!is_dir($dest)) {
                mkdir($dest, 0777, true);
            }

            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                copyr("$source/$entry", "$dest/$entry");
            }

            $dir->close();
            return true;
        }

        if($this->confirm('Copy routes? This will override app/Http/routes.php file, please backup that first')){
            copy('vendor/forone/administrator/src/Forone/routes.php', 'app/Http/routes.php');
            copyr('vendor/forone/administrator/src/Forone/Controllers', 'app/Http/Controllers/Forone/Admin/Controllers');
            copyr('vendor/forone/administrator/src/resources/views', 'resources/views/forone');
            $config = file_get_contents('config/forone.php');
            $config = str_replace("'disable_routes' => false", "'disable_routes' => true", $config);
            file_put_contents('config/forone.php', $config);
        }
    }

}
