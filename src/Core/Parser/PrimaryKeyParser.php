<?php

namespace Emanci\MysqlDiff\Core\Parser;

class PrimaryKeyParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':primary_key' => '(?:`[^`]+`\s*(?:\(\d+\))?,?)+',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/';
        $pattern .= 'PRIMARY KEY \((?P<primary_key>:primary_key)\)';
        $pattern .= '/ui';

        return $pattern;
    }
}
