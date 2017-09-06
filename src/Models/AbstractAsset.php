<?php

namespace Emanci\MysqlDiff\Models;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $method = 'set'.$name;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }
}
