<?php

namespace Emanci\MysqlDiff\Models;

use Emanci\MysqlDiff\Exceptions\IndexColumnException;

class Index extends AbstractAsset
{
    /**
     * @var bool
     */
    protected $unique;

    /**
     * @var array
     */
    protected $flags;

    /**
     * @var IndexColumn[]
     */
    protected $indexColumns = [];

    /**
     * The index option.
     *
     * @var string
     */
    protected $options;

    /**
     * Index construct.
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
     * @param bool $unique
     *
     * @return $this
     */
    public function setUnique($unique)
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * @param array $flags
     *
     * @return $this
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag)
    {
        return isset($this->flags[strtolower($flag)]);
    }

    /**
     * @param string $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param IndexColumn[]
     *
     * @return $this
     */
    public function setIndexColumns($indexColumns)
    {
        $this->indexColumns = $indexColumns;

        return $this;
    }

    /**
     * @return IndexColumn[]
     */
    public function getIndexColumns()
    {
        return $this->indexColumns;
    }

    /**
     * @param IndexColumn $indexColumn
     */
    public function addIndexColumn(IndexColumn $indexColumn)
    {
        $indexColumnName = $indexColumn->getColumn()->getName();

        if ($this->hasIndexColumn($indexColumnName)) {
            throw new IndexColumnException("IndexColumn already exists {$indexColumnName}");
        }

        $this->indexColumns[$indexColumnName] = $indexColumn;
    }

    /**
     * @param string $columnName
     *
     * @return bool
     */
    public function hasIndexColumn($indexColumnName)
    {
        return isset($this->indexColumns[$indexColumnName]);
    }

    /**
     * @return string
     */
    public function getIndexDefinitionScript()
    {
        $indexType = $this->getDefinitionIndexType();
        $indexColumnsString = implode(',', $this->getDefinitionIndexColumns());
        $indexOptions = $this->getDefinitionIndexOptions();

        return sprintf('%sKEY `%s` (%s)%s',
            $indexType,
            $this->name,
            $indexColumnsString,
            $indexOptions
        );
    }

    /**
     * @return string
     */
    protected function getDefinitionIndexType()
    {
        $indexType = '';

        if ($this->isUnique()) {
            $indexType = 'UNIQUE';
        } elseif ($this->isFulltext()) {
            $indexType = 'FULLTEXT';
        } elseif ($this->isSpatial()) {
            $indexType = 'SPATIAL';
        }

        return $indexType ? $indexType.' ' : $indexType;
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @return bool
     */
    public function isFulltext()
    {
        return $this->hasFlag('FULLTEXT');
    }

    /**
     * @return bool
     */
    public function isSpatial()
    {
        return $this->hasFlag('SPATIAL');
    }

    /**
     * @return array
     */
    protected function getDefinitionIndexColumns()
    {
        return array_map(function ($indexColumn) {
            return $indexColumn->getIndexColumnScript();
        }, $this->indexColumns);
    }

    /**
     * @return string
     */
    protected function getDefinitionIndexOptions()
    {
        return $this->options ? ' '.$this->options : '';
    }
}
