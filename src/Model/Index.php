<?php

namespace Emanci\MysqlCompareFixer\Model;

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
    public function getDefinitionScript()
    {
        $definitionIndexType = $this->getDefinitionIndexType();
        $indexColumnsString = implode(',', $this->getDefinitionIndexColumns());
        $indexOptions = $this->options ? ' '.$this->options : '';

        return sprintf('%sKEY `%s` (%s)%s', $definitionIndexType, $this->name, $indexColumnsString, $indexOptions);
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
        $indexColumns = [];

        foreach ($this->indexColumns as $column) {
            $firstCharacters = '';
            if ($indexColumn->getIndexFirstCharacters()) {
                $firstCharacters = sprintf('(%s)', $indexColumn->getIndexFirstCharacters());
            }
            $indexColumns[] = sprintf('`%s`%s', $indexColumn->getColumn()->getName(), $firstCharacters);
        }

        return $indexColumns;
    }
}
