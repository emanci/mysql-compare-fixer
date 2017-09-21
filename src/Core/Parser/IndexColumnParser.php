<?php

namespace Emanci\MysqlDiff\Core\Parser;

class IndexColumnParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':column_name' => '[^\(]+',
        ':sub_part' => '\d+',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/^';
        $pattern .= '(?P<column_name>:column_name)\s*';
        $pattern .= '(?:\((?P<sub_part>:sub_part)\))?';
        $pattern .= '$/ui';

        return $pattern;
    }
}
