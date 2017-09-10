<?php

namespace Emanci\MysqlDiff\Contracts;

interface BuilderInterface
{
    /**
     * Creates model from statement.
     *
     * @param string $statement
     *
     * @return mixed
     */
    public function create($statement);
}
