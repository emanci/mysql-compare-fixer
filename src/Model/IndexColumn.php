<?php

namespace Emanci\MysqlCompareFixer\Model;

class IndexColumn
{
    /**
     * @var Column
     */
    protected $column;

    /**
     * @var int
     */
    protected $indexFirstCharacters;

    /**
     * @param Column $column
     * @param int    $indexFirstCharacters
     */
    public function __construct(Column $column, $indexFirstCharacters = null)
    {
        $this->column = $column;
        $this->indexFirstCharacters = $indexFirstCharacters;
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getIndexFirstCharacters()
    {
        return $this->indexFirstCharacters;
    }
}
