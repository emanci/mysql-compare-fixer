<?php

namespace Emanci\MysqlDiff\Commands;

use Emanci\MysqlDiff\Core\Parser;
use Emanci\MysqlDiff\Database\Mysql;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @link https://symfony.com/doc/current/console.html
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var Mysql
     */
    protected $fromSchema;

    /**
     * @var Mysql
     */
    protected $toSchema;

    /**
     * MysqlDiffCommand construct.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct();
        $fromServer = $this->getDbInstance($config['from_server']);
        $toServer = $this->getDbInstance($config['to_server']);
        $parser = new Parser($fromServer);
        $this->fromSchema = $parser->parseSchema();
        $this->toSchema = $parser->setPlatform($toServer)->parseSchema();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $question
     *
     * @return bool
     */
    protected function ask(InputInterface $input, OutputInterface $output, $question = null)
    {
        $helper = $this->getHelper('question');
        $question = $question ? $question : $this->getDefaultQuestions();
        $question = new ConfirmationQuestion($question, false);

        return $helper->ask($input, $output, $question);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function getDefaultQuestions()
    {
        throw new InvalidArgumentException('No questions asked.');
    }

    /**
     * @param array $config
     *
     * @return Mysql
     */
    protected function getDbInstance($config)
    {
        return new Mysql($config);
    }
}
