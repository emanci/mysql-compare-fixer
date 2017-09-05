<?php

namespace Emanci\MysqlDiff\Models;

/**
 * @link https://dev.mysql.com/doc/refman/5.5/en/constraint-foreign-key.html
 * @link https://dev.mysql.com/doc/refman/5.7/en/referential-constraints-table.html
 * @link https://dev.mysql.com/doc/refman/5.7/en/create-table-foreign-keys.html
 * @link https://dev.mysql.com/doc/refman/5.7/en/alter-table.html#alter-table-foreign-key
 */
class ForeignKey extends AbstractAsset
{
    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var string
     */
    protected $referencedTableName;

    /**
     * @var string
     */
    protected $referencedColumnName;

    /**
     * options:
     * RESTRICT | CASCADE | SET NULL | NO ACTION | SET DEFAULT.
     *
     * @var string
     */
    protected $onDeleteClause;

    /**
     * options:
     * RESTRICT | CASCADE | SET NULL | NO ACTION | SET DEFAULT.
     *
     * @var string
     */
    protected $onUpdateClause;

    /**
     * ForeignKey construct.
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
     * @param string $columnName
     *
     * @return $this
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $referencedTableName
     *
     * @return $this
     */
    public function setReferencedTableName($referencedTableName)
    {
        $this->referencedTableName = $referencedTableName;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferencedTableName()
    {
        return $this->referencedTableName;
    }

    /**
     * @param string $referencedColumnName
     *
     * @return $this
     */
    public function setReferencedColumnName($referencedColumnName)
    {
        $this->referencedColumnName = $referencedColumnName;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferencedColumnName()
    {
        return $this->referencedColumnName;
    }

    /**
     * @return string
     */
    public function getOnDeleteClause()
    {
        return $this->onDeleteClause;
    }

    /**
     * @return string
     */
    public function getOnUpdateClause()
    {
        return $this->onUpdateClause;
    }

    /**
     * @return string
     */
    public function getForeignKeyDefinitionScript()
    {
        $foreignKeyOptionsString = implode(' ', $this->getForeignKeyOptions());

        return sprintf('CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`) %s',
            $this->name,
            $this->columnName,
            $this->referencedTableName,
            $this->referencedColumnName,
            $foreignKeyOptionsString
        );
    }

    /**
     * @return array
     */
    protected function getForeignKeyOptions()
    {
        $options = [];

        if ($this->onDeleteClause) {
            $options[] = $this->onDeleteClause;
        }

        if ($this->onUpdateClause) {
            $options[] = $this->onUpdateClause;
        }

        return $options;
    }
}
