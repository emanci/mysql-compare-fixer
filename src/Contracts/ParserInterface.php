<?php

namespace Emanci\MysqlDiff\Contracts;

interface ParserInterface
{
    /**
     * Parse an SQL statement.
     *
     * @param string $statement
     *
     * @return mixed
     */
    public function parse($statement);
}
