<?php

namespace Emanci\MysqlDiff\Models;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/show-columns.html
 *
 * The INFORMATION_SCHEMA COLUMNS Table
 * @link https://dev.mysql.com/doc/refman/5.7/en/columns-table.html
 *
 * Character Sets and Collations in General
 * @link https://dev.mysql.com/doc/refman/5.7/en/charset-general.html
 */
class Column extends AbstractAsset
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $length;

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
    protected $characterSet;

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
     *
     * @return $this
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
     * @param int $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param bool $autoIncrement
     *
     * @return $this
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
     *
     * @return $this
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
     *
     * @return $this
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
     * @param string $characterSet
     *
     * @return $this
     */
    public function setCharacterSet($characterSet)
    {
        $this->characterSet = $characterSet;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharacterSet()
    {
        return $this->characterSet;
    }

    /**
     * @param bool $nullable
     *
     * @return $this
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
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
     *
     * @return $this
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
     *
     * @return $this
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
     *
     * @return $this
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

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return 'PRI' === $this->key;
    }
}
