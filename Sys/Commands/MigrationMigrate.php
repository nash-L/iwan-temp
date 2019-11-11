<?php


namespace Sys\Commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class MigrationMigrate extends PhinxAgent
{
    protected static $defaultName = 'migration:migrate';

    protected function configure()
    {
        parent::configure();

        $this->addOption('--environment', '-e', InputOption::VALUE_REQUIRED, 'The target environment');

        $this->setDescription('Migrate the database')
            ->addOption('--target', '-t', InputOption::VALUE_REQUIRED, 'The version number to migrate to')
            ->addOption('--date', '-d', InputOption::VALUE_REQUIRED, 'The date to migrate to')
            ->addOption('--dry-run', '-x', InputOption::VALUE_NONE, 'Dump query to standard output instead of executing it')
            ->addOption('--fake', null, InputOption::VALUE_NONE, "Mark any migrations selected as run, but don't actually execute them")
            ->setHelp(
                <<<EOT
The <info>migrate</info> command runs all available migrations, optionally up to a specific version

<info>iwan migrate -e development</info>
<info>iwan migrate -e development -t 20110103081132</info>
<info>iwan migrate -e development -d 20110103</info>
<info>iwan migrate -e development -v</info>

EOT
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeAgent('migrate', true);
    }
}