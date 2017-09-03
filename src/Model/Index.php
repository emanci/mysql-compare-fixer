<?php

namespace Emanci\MysqlDiff\Model;

class Index
{
    /**
     * @var string
     */
    protected $name;

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
     * @return string
     */
    public function getIndexScript()
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
