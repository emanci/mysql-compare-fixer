<?php

namespace Emanci\MysqlDiff\Core\Parser;

class TableParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':name' => '`.+?`|\S+',
        ':engine' => '\w+',
        ':auto_increment' => '\d+',
        ':charset' => '\w+',
        ':collate' => '\w+',
        ':comment' => '\'[\s\S]*\'',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/^';
        $pattern .= 'CREATE( TEMPORARY)? TABLE( IF NOT EXISTS)? (?P<name>:name)';
        $pattern .= ' \((?P<definition>(.+))\)';
        $pattern .= '( ENGINE=(?P<engine>:engine))?';
        $pattern .= '( AUTO_INCREMENT=(?P<auto_increment>:auto_increment))?';
        $pattern .= '( DEFAULT CHARSET=(?P<charset>:charset))?';
        $pattern .= '( COLLATE=(?P<collate>:collate))?';
        $pattern .= '( COMMENT=(?P<comment>:comment))?';
        $pattern .= '$/ui';

        return $pattern;
    }
}
