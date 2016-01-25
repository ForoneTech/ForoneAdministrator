<?php namespace Forone\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\DB;
use Orangehill\Iseed\Facades\Iseed;

class Backup extends BaseCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup with ISeed.';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Database Backup Start...');

        $path = $this->getMigrationPath();
        $files = $this->files->glob($path.'/*_*.php');
        $files = array_map(function ($file) {
            return str_replace('.php', '', basename($file));
        }, $files);
        sort($files);

        $tables = [];
        $fileNames = [];

        foreach($files as $value){
            $name = preg_replace('/.*create_(.*)_table/', '$1', $value);
            array_push($fileNames, $name);
        }

        foreach (DB::select('SHOW TABLES') as $k => $v) {
            $tableName = array_values((array)$v)[0];
            array_push($tables, $tableName);
        }

        foreach ($fileNames as $value) {
            if(($key = array_search($value, $tables)) !== false) {
                unset($tables[$key]);
                Iseed::generateSeed($value);
                $this->info($value . ' Seeded');
            }else {
                foreach($tables as $key => $tableName){
                    if (strpos($value, $tableName) !== false) {
                        unset($tables[$key]);
                        Iseed::generateSeed($tableName);
                        $this->info($tableName . ' Seeded');
                        break;
                    }
                }
            }
        }

        if (count($tables)) {
            foreach ($tables as $tableName) {
                Iseed::generateSeed($tableName);
                $this->info($tableName . ' Seeded');
            }
        }

        $this->info('Database Backup End...');
    }

}
