<?php

namespace Emanci\MysqlCompareFixer\Model;

class ForeignKey
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $references;

    /**
     * @var string
     */
    protected $refField;

    /**
     * @var string
     */
    protected $onDeleteClause;

    /**
     * @var string
     */
    protected $onUpdateClause;

    /**
     * ForeignKey construct.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return string
     */
    public function getRefField()
    {
        return $this->refField;
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
    public function getDefinitionScript()
    {
        $foreignKeyOptionsString = implode(' ', $this->getForeignKeyOptions());

        return sprintf('CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`) %s',
            $this->name,
            $this->key,
            $this->references,
            $this->refField,
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
