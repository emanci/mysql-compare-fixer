<?php

namespace Emanci\MysqlDiff\Core\Parser;

/**
 * @link https://dev.mysql.com/doc/refman/5.7/en/show-create-database.html
 */
class SchemaParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':name' => '`.+?`|\S+',
        ':character_set' => '[\w\-]+',
        ':collate' => '\w+',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/^';
        $pattern .= 'CREATE DATABASE (?P<name>:name)';
        $pattern .= '( DEFAULT CHARACTER SET (?P<character_set>:character_set))?';
        $pattern .= '( COLLATE (?P<collate>:collate))?';
        $pattern .= '$/ui';

        return $pattern;
    }
}
