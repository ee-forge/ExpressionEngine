<?php

namespace ExpressionEngine\Cli\Commands;

use ExpressionEngine\Cli\Cli;

/**
 * Run migrations
 */
class CommandMigrateCore extends Cli
{
    /**
     * name of command
     * @var string
     */
    public $name = 'Migrate Core';

    /**
     * signature of command
     * @var string
     */
    public $signature = 'migrate:core';

    /**
     * Public description of command
     * @var string
     */
    public $description = 'Runs core migrations';

    /**
     * Summary of command functionality
     * @var [type]
     */
    public $summary = 'Loops through the SYSPATH/user/database/migrations folder and executes all migrations that have not previously been run.';

    /**
     * How to use command
     * @var string
     */
    public $usage = 'php eecli.php migrate:core';

    /**
     * options available for use in command
     * @var array
     */
    public $commandOptions = [
        'steps,s:' => 'Specify the number of migrations to run',
    ];

    /**
     * Command can run without EE Core
     * @var boolean
     */
    public $standalone = false;

    /**
     * Run the command
     * @return mixed
     */
    public function handle()
    {
        // Specify the number of migrations to run
        $steps = $this->option('-s', -1);

        $location = 'ExpressionEngine';

        $migrationGroup = ee('Migration')->getNextMigrationGroup();
        $ran = ee('Migration')->migrateAllByType($location, $migrationGroup, $steps);

        foreach ($ran as $ranMigration) {
            $this->info('Migrated: ' . $ranMigration);
        }

        $this->complete('All migrations completed successfully!');
    }
}
