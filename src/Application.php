<?php

namespace Emanci\MysqlDiff;

use Emanci\MysqlDiff\Commands\DifferCommand;
use Emanci\MysqlDiff\Commands\FixerCommand;
use Emanci\MysqlDiff\Commands\HelpCommand;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application
{
    use ConfigTrait;

    /**
     * @var string
     */
    const APP_NAME = 'MysqlDiff';

    /**
     * @var string
     */
    const VERSION = 'v0.0.1';

    /**
     * The Console Application instance.
     *
     * @var \Symfony\Component\Console\Application
     */
    protected $consoleApp;

    /**
     * The default commands.
     *
     * @var array
     */
    protected $commands = [
        FixerCommand::class,
        DifferCommand::class,
    ];

    /**
     * Application construct.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig(new Config($config));
        $this->consoleApp = new ConsoleApplication(self::APP_NAME, self::VERSION);
        $this->registerCommands();
    }

    /**
     * Runs the console application.
     *
     * @param InputInterface|null  $input
     * @param OutputInterface|null $output
     *
     * @throws \Exception
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        return $this->consoleApp->run($input, $output);
    }

    /**
     * Returns the Console Application instance.
     *
     * @return \Symfony\Component\Console\Application
     */
    public function getConsoleApp()
    {
        return $this->consoleApp;
    }

    /**
     * Register commands object.
     */
    protected function registerCommands()
    {
        $this->consoleApp->add(new HelpCommand());

        array_walk($this->commands, function ($command) {
            $this->consoleApp->add(new $command($this->config->all()));
        });
    }
}
