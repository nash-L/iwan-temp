<?php


namespace Sys\Commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class MigrationRollback extends PhinxAgent
{
    protected static $defaultName = 'migration:rollback';

    protected function configure()
    {
        parent::configure();

        $this->addOption('--environment', '-e', InputOption::VALUE_REQUIRED, 'The target environment');

        $this->setDescription('Rollback the last or to a specific migration')
            ->addOption('--target', '-t', InputOption::VALUE_REQUIRED, 'The version number to rollback to')
            ->addOption('--date', '-d', InputOption::VALUE_REQUIRED, 'The date to rollback to')
            ->addOption('--force', '-f', InputOption::VALUE_NONE, 'Force rollback to ignore breakpoints')
            ->addOption('--dry-run', '-x', InputOption::VALUE_NONE, 'Dump query to standard output instead of executing it')
            ->addOption('--fake', null, InputOption::VALUE_NONE, "Mark any rollbacks selected as run, but don't actually execute them")
            ->setHelp(
                <<<EOT
The <info>rollback</info> command reverts the last migration, or optionally up to a specific version

<info>iwan rollback -e development</info>
<info>iwan rollback -e development -t 20111018185412</info>
<info>iwan rollback -e development -d 20111018</info>
<info>iwan rollback -e development -v</info>
<info>iwan rollback -e development -t 20111018185412 -f</info>

If you have a breakpoint set, then you can rollback to target 0 and the rollbacks will stop at the breakpoint.
<info>iwan rollback -e development -t 0 </info>

The <info>version_order</info> configuration option is used to determine the order of the migrations when rolling back.
This can be used to allow the rolling back of the last executed migration instead of the last created one, or combined
with the <info>-d|--date</info> option to rollback to a certain date using the migration start times to order them.

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
        $this->executeAgent('rollback', true);
    }
}