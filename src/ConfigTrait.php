<?php

namespace Emanci\MysqlDiffFixer;

trait ConfigTrait
{
    /**
     * The socialite configuration.
     *
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Set the config.
     *
     * @param ConfigInterface $config
     *
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the config.
     *
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }
}
