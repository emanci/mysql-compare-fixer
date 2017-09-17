<?php

namespace Emanci\MysqlDiff\Core\Parser;

class ForeignKeyParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':index_name' => '\S+?',
        ':column_name' => '\S+?',
        ':referenced_table' => '\S+?',
        ':reference_column' => '\S+?',
        ':on_delete' => '.+?',
        ':on_update' => '.+?',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $pattern = '/\s*';
        $pattern .= 'CONSTRAINT `(?P<index_name>:index_name)`\s+FOREIGN KEY\s+';
        $pattern .= '\(`(?P<column_name>:column_name)`\)\s+';
        $pattern .= 'REFERENCES\s+`(?P<referenced_table>:referenced_table)`\s*';
        $pattern .= '\(`(?P<reference_column>:reference_column)`\)\s*';
        $pattern .= '(?P<onDelete>ON DELETE :on_delete)?\s*';
        $pattern .= '(?P<onUpdate>ON UPDATE :on_update)?\s*';
        $pattern .= '(?:,|$)';
        $pattern .= '/ui';

        return $pattern;
    }
}
