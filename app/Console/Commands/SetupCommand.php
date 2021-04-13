<?php

namespace App\Console\Commands;

use Database\Seeders\ImportTableSeeder;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the app.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setAliases([
            's'
        ]);
        parent::__construct();
        $this->addOption("truncate", 't', InputOption::VALUE_NONE,'Truncate Tables.',null);
        $this->addOption("fresh", 'f', InputOption::VALUE_NONE,'Fresh Migration.',null);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tableNames = config('permission.table_names');
        $fresh = $this->option('fresh');

        if( !$fresh && $this->option('truncate') ) {
            $this->warn("Truncating tables ...");
            foreach ($tableNames as $tableNameIdx => $tableName) {
                try {
                    $this->comment("\tTruncate table {$tableNameIdx} ...");
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                    Schema::dropIfExists($tableName);
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    DB::table('migrations')->where("migration", "LIKE", "%_{$tableName}_%")->delete();
                    $this->info("\t\tSUCCESS");
                } catch (\Exception $exception) {
                    $this->error("\t\tFailed");
                    if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                        dd($exception->getMessage());
                    }

                }
            }
        }

        $this->warn("Migrating ...");
        Artisan::call('migrate' . ($fresh ? ":fresh" : ""), [
            '--seed' => true,
        ]);
        $this->info("Migrating Done");

//        $this->warn("Seeding ...");
//        Artisan::call('db:seed', [
//            '--class' => PermissionsSeeder::class,
//        ]);
//        $this->info("Seeding Done");
//
//        $this->warn("Importing ...");
//        Artisan::call('db:seed', [
//            '--class' => ImportTableSeeder::class,
//        ]);
//        $this->info("Importing Done");

        return 0;
    }
}
