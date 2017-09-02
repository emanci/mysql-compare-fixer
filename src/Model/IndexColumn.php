<?php

namespace Emanci\MysqlCompareFixer\Model;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/column-indexes.html
 */
class IndexColumn
{
    /**
     * @var Column
     */
    protected $column;

    /**
     * @var int
     */
    protected $firstNCharacters;

    /**
     * @param Column $column
     * @param int    $firstNCharacters
     */
    public function __construct(Column $column, $firstNCharacters = null)
    {
        $this->column = $column;
        $this->firstNCharacters = $firstNCharacters;
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
    public function getFirstNCharacters()
    {
        return $this->firstNCharacters;
    }

    /**
     * @return string
     */
    public function getIndexColumnScript()
    {
        $firstNCharactersScript = '';

        if ($firstNCharacters = $this->getFirstNCharacters()) {
            $firstNCharactersScript = sprintf('(%s)', $firstNCharacters);
        }

        return sprintf('`%s`%s', $this->getColumn()->getName(), $firstNCharactersScript);
    }
}
