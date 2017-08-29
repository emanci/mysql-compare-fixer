<?php

namespace Emanci\MysqlDiff\Commands;

use Emanci\MysqlDiff\MysqlDiff;
use Symfony\Component\Console\Command\Command;

/**
 * @link https://symfony.com/doc/current/console.html
 */
abstract class BaseCommand extends Command
{
    /**
     * The MysqlDiff instance.
     *
     * @var MysqlDiff
     */
    protected $diff;

    /**
     * MysqlDiffCommand construct.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct();
        $this->diff = new MysqlDiff($config);
    }
}
