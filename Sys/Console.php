<?php


namespace Sys;


use Symfony\Component\Console\Application as ConsoleApplication;
use Sys\Commands\Composer;
use Sys\Commands\MigrationCreate;
use Sys\Commands\MigrationMigrate;
use Sys\Commands\MigrationRollback;
use Exception;
use Sys\Commands\SeedCreate;
use Sys\Commands\SeedRun;

class Console
{
    const NAME = 'iWan by Nash';
    const VERSION = '0.1';
    /**
     * @var ConsoleApplication
     */
    private $application;

    public function define()
    {}

    /**
     * Console constructor.
     * @throws Exception
     */
    final public function __construct()
    {
        $this->application = new ConsoleApplication(self::NAME, self::VERSION);
        $this->application->addCommands([
            new MigrationCreate(), new MigrationMigrate(), new MigrationRollback(),
            new SeedCreate(), new SeedRun()
        ]);
    }

    /**
     * @throws Exception
     */
    private function start() {
        $this->application->run();
    }

    /**
     * @throws Exception
     */
    final public static function run()
    {
        $console = new static();
        $console->define();
        $console->start();
    }
}