<?php

namespace Emanci\MysqlCompareFixer\Model;

class ChangedTable
{
    protected $errorCreatedTables = [];
    protected $successCreatedTables = [];
    protected $errorAlterTables = [];
    protected $successAlterTables = [];

    public function setErrorCreatedTables($table)
    {
        $this->errorCreatedTables[] = $table;

        return $this;
    }

    public function getErrorCreatedTables()
    {
        return $this->errorCreatedTables;
    }

    public function setSuccessCreatedTables($table)
    {
        $this->successCreatedTables[] = $table;

        return $this;
    }

    public function getSuccessCreatedTables()
    {
        return $this->successCreatedTables;
    }

    public function setErrorAlterTables($table)
    {
        $this->errorAlterTables[] = $table;

        return $this;
    }

    public function getErrorAlterTables()
    {
        return $this->errorAlterTables;
    }

    public function setSuccessAlterTables($table)
    {
        $this->successAlterTables[] = $table;

        return $this;
    }

    public function getSuccessAlterTables()
    {
        return $this->successAlterTables;
    }
}
