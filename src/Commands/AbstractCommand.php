<?php

namespace Emanci\MysqlDiff\Commands;

use Emanci\MysqlDiff\Core\Parser;
use Emanci\MysqlDiff\Database\Mysql;
use Symfony\Component\Console\Command\Command;

/**
 * @link https://symfony.com/doc/current/console.html
 */
abstract class AbstractCommand extends Command
{
    /**
     * The Parser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * MysqlDiffCommand construct.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct();
        $master = $this->getDbInstance($config['master']);
        $slave = $this->getDbInstance($config['slave']);
        $this->parser = new Parser($master);
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
