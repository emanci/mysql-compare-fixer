<?php

namespace Emanci\MysqlDiff\Model;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/show-columns.html
 * @link https://dev.mysql.com/doc/refman/5.7/en/columns-table.html
 */
class Column extends AbstractAsset
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $autoIncrement = false;

    /**
     * @var bool
     */
    protected $unsigned = false;

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string
     */
    protected $extra;

    /**
     * @var string
     */
    protected $collation;

    /**
     * @var string
     */
    protected $comment;

    /**
     * Column construct.
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name, array $attributes = [])
    {
        $this->setName($name);
        $this->map($attributes);
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

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param bool $autoIncrement
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param bool $unsigned
     */
    public function setUnsigned($unsigned)
    {
        $this->unsigned = $unsigned;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUnsigned()
    {
        return $this->unsigned;
    }

    /**
     * @param string $collation
     */
    public function setCollation($collation)
    {
        $this->collation = $collation;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollation()
    {
        return $this->collation;
    }

    /**
     * @param bool $nullable
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
