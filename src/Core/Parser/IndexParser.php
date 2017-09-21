<?php

namespace Emanci\MysqlDiff\Core\Parser;

class IndexParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':name' => '\S+?',
        ':columns' => '(?:`[^`]+`(?:\(\d+\))?,?)+',
        ':options' => '[^,]+?',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/\s*';
        $pattern .= '(?P<spatial>SPATIAL)?\s*';
        $pattern .= '(?P<unique>UNIQUE)?\s*';
        $pattern .= '(?P<fullText>FULLTEXT)?\s*';
        $pattern .= 'KEY\s+`(?<name>:name)`\s+';
        $pattern .= '\((?P<columns>:columns)\)\s*';
        $pattern .= '(?P<options>:options)?\s*';
        $pattern .= '(?:,|$)';
        $pattern .= '/ui';

        return $pattern;
    }
}
