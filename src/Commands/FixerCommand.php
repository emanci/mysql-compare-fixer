<?php

namespace Emanci\MysqlDiffFixer\Commands;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @link https://symfony.com/doc/current/console.html
 */
class FixerCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('mdf:fix')
             ->setDescription('Fix the different.')
             ->setHelp('This command help you to created tables or fields.');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Continue with this fix action? [y/n]: ', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $this->diff->run();
        $this->prettyPrintTable($output);
    }

    /**
     * Pretty print table.
     *
     * @param OutputInterface $output
     */
    protected function prettyPrintTable(OutputInterface $output)
    {
        list($successAddTables, $failedAddTables) = $this->diff->getAddTables();
        list($successRepairTables, $failedRepairTables) = $this->diff->getRepairTables();

        $headers = [
            'Successful created ('.count($successAddTables).')',
            'Failed created ('.count($failedAddTables).')',
            'Successful repaired ('.count($successRepairTables).')',
            'Failed repaired ('.count($failedRepairTables).')',
        ];

        $rows = [
            [
                implode(',', $successAddTables),
                implode(',', $failedAddTables),
                implode(',', $successRepairTables),
                implode(',', $failedRepairTables),
            ],
        ];

        $table = new Table($output);
        $table->setHeaders($headers)
              ->setRows($rows);

        $table->render();
    }
}
