<?php

namespace Emanci\MysqlCompareFixer\Model;

class Column
{
    protected $field;

    protected $type;

    protected $collation;

    protected $null;

    protected $key;

    protected $default;

    protected $extra;

    protected $comment;

    /**
     * Map the given array onto the column's properties.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCollation()
    {
        return $this->collation;
    }

    public function getNull()
    {
        return $this->null;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
