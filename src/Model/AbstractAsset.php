<?php

namespace Emanci\MysqlDiff\Model;

abstract class AbstractAsset
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }
}
