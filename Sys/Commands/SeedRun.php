<?php


namespace Sys\Commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class SeedRun extends PhinxAgent
{
    protected static $defaultName = 'seed:run';

    protected function configure()
    {
        parent::configure();

        $this->addOption('--environment', '-e', InputOption::VALUE_REQUIRED, 'The target environment');

        $this->setDescription('Run database seeders')
            ->addOption('--seed', '-s', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'What is the name of the seeder?')
            ->setHelp(
                <<<EOT
The <info>seed:run</info> command runs all available or individual seeders

<info>phinx seed:run -e development</info>
<info>phinx seed:run -e development -s UserSeeder</info>
<info>phinx seed:run -e development -s UserSeeder -s PermissionSeeder -s LogSeeder</info>
<info>phinx seed:run -e development -v</info>

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
        $this->executeAgent('seed:run', true);
    }

}