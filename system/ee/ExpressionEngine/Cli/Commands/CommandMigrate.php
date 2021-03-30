<?php

namespace ExpressionEngine\Cli\Commands;

use ExpressionEngine\Cli\Cli;
use ExpressionEngine\Library\Filesystem\Filesystem;
use ExpressionEngine\Model\Migration\Migration;

/**
 * Run migrations
 */
class CommandMigrate extends Cli
{
    /**
     * name of command
     * @var string
     */
    public $name = 'Migrate';

    /**
     * signature of command
     * @var string
     */
    public $signature = 'migrate';

    /**
     * Public description of command
     * @var string
     */
    public $description = 'Runs specified migrations (all, core, or add-ons)';

    /**
     * Summary of command functionality
     * @var [type]
     */
    public $summary = 'Loops through the SYSPATH/user/database/migrations folder and executes all migrations that have not previously been run.';

    /**
     * How to use command
     * @var string
     */
    public $usage = 'php eecli.php migrate';

    /**
     * options available for use in command
     * @var array
     */
    public $commandOptions = [
        'steps,s:' => 'Specify the number of migrations to run',
        'everything,e' => 'Run all migrations. Core runs first, all add-on migrations, one at a time.',
        'all' => 'Run all migrations. Alias for --everything',
        'core,c' => 'Run only core migrations. This excludes all add-on migrations.',
        'addon,a:' => 'Run migration only for specified addon.',
        'addons' => 'Run migration only for specified addon.',
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

        $all = $this->option('--everything', false) || $this->option('--all', false);
        $core = $this->option('--core', false);
        $addons = $this->option('--addons', false);
        $addon = $this->option('--addon', false);

        // Lets figure out the location we're migrating
        $location = null;

        // If core is set, it means location is EE core
        if ($core) {
            $location = 'ExpressionEngine';
        } elseif ($all) {
            $location = 'all';
        } elseif ($addons) {
            $location = 'addons';
        } elseif ($addon) {
            $location = $addon;
        }

        // No location set. Lets ask and default to all
        if (is_null($location)) {
            $availableMigrationLocations = ee('Migration')->getAvailableLocations();
            if (count($availableMigrationLocations) === 0) {
                $this->complete('All available migrations have already run.');
            }
            $location = $this->ask('What is the location of your migration? [' . implode(', ', $availableMigrationLocations) . ']', 'all');
        }

        // No location set, even after
        if (! $location) {
            $this->fail('Please select location of migration using --core, --everything, --addons, or --addon=addon_name.');
        }

        $migrationGroup = ee('Migration')->getNextMigrationGroup();
        $ran = ee('Migration')->migrateAllByType($location, $migrationGroup, $steps);

        foreach ($ran as $ranMigration) {
            $this->info('Migrated: ' . $ranMigration);
        }

        $this->complete('All migrations completed successfully!');
    }
}
