<?php


namespace Sys\Commands;


use Phinx\Console\Command\Create;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class MigrationCreate extends PhinxAgent
{
    protected static $defaultName = 'migration:create';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Create a new migration')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of the migration (in CamelCase)?')
            ->setHelp(sprintf(
                '%sCreates a new database migration%s',
                PHP_EOL,
                PHP_EOL
            ));
        // An alternative template.
        $this->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Use an alternative template');

        // A classname to be used to gain access to the template content as well as the ability to
        // have a callback once the migration file has been created.
        $this->addOption('class', 'l', InputOption::VALUE_REQUIRED, 'Use a class implementing "' . Create::CREATION_INTERFACE . '" to generate the template');

        // Allow the migration path to be chosen non-interactively.
        $this->addOption('path', null, InputOption::VALUE_REQUIRED, 'Specify the path in which to create this migration');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeAgent('create', true);
    }
}