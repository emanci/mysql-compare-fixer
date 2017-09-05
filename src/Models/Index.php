<?php

namespace Emanci\MysqlDiff\Models;

class Index extends AbstractAsset
{
    /**
     * @var bool
     */
    protected $unique;

    /**
     * @var bool
     */
    protected $fullText;

    /**
     * @var bool
     */
    protected $spatial;

    /**
     * @var string
     */
    protected $indexType;

    /**
     * @var IndexColumn[]
     */
    protected $indexColumns = [];

    /**
     * @var string
     */
    protected $options;

    /**
     * Index construct.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @return IndexColumn[]
     */
    public function getIndexColumns()
    {
        return $this->indexColumns;
    }

    /**
     * @param IndexColumn[]
     */
    public function setIndexColumns($indexColumns)
    {
        $this->indexColumns = $indexColumns;
    }

    /**
     * @param IndexColumn $indexColumn
     */
    public function addIndexColumn(IndexColumn $indexColumn)
    {
        $this->indexColumns[] = $indexColumn;
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

        if ($this->spatial) {
            $indexType = 'SPATIAL';
        } elseif ($this->unique) {
            $indexType = 'UNIQUE';
        } elseif ($this->fullText) {
            $indexType = 'FULLTEXT';
        }

        if (!empty($indexType)) {
            $indexType .= ' ';
        }

        return $indexType;
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
