<?php

namespace Emanci\MysqlDiff\Models;

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
    protected $subPart;

    /**
     * @param Column $column
     * @param int    $subPart
     */
    public function __construct(Column $column, $subPart = null)
    {
        $this->column = $column;
        $this->subPart = $subPart;
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
    public function getSubPart()
    {
        return $this->subPart;
    }

    /**
     * @return string
     */
    public function getIndexColumnDefinitionScript()
    {
        $subPartScript = '';

        if ($subPart = $this->getSubPart()) {
            $subPartScript = sprintf('(%s)', $subPart);
        }

        return sprintf('`%s`%s', $this->getColumn()->getName(), $subPartScript);
    }
}
