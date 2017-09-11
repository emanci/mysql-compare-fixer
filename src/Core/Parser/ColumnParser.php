<?php

namespace Emanci\MysqlDiff\Core\Parser;

class ColumnParser extends AbstractParser
{
    /**
     * @var array
     */
    protected $placeholders = [
        ':name' => '`.+?`|\S+',
        ':character_set' => '\S+',
        ':collate' => '\S+',
        ':nullable' => 'NULL|NOT NULL',
        ':default' => '\'(.*?)\'|NULL',
        ':comment' => '\'([^\']|\'\')+\'',
    ];

    /**
     * @var array
     */
    protected static $columnTypeRegex = [
        '(?:tiny|small|medium|big)?int(?:\((?<int_length>\d+)\))?(?:\s+unsigned)?',
        'float(?:\s+unsigned)?(?:\((?<float_length>\d+),(?<float_precision>\d+)\))?',
        'binary',
        'real',
        'decimal\((?<decimal_length>\d+),(?<decimal_precision>\d+)\)',
        'double(?:\((?<double_length>\d+),(?<double_precision>\d+)\))?(?:\s+unsigned)?',
        'datetime',
        'date',
        'time',
        'timestamp',
        'year\((?<year_length>\d)\)',
        'geometry',
        '(?:var|nvar)?char\((?<char_length>\d+)\)',
        '(?:var)?binary\((?<binary_length>\d+)\)',
        '(?:tiny|medium|long)?text',
        '(?:tiny|medium|long)?blob',
        'enum\(.+\)',
        'set\(.+\)',
    ];

    /**
     * @return string
     */
    protected function getRegexPattern()
    {
        $dataTypeRegex = $this->getDataTypeRegex();

        $pattern = '/\s*';
        $pattern .= '`(?P<name>:name)`\s+';
        $pattern .= '(?P<data_type>'.$dataTypeRegex.')\s+';
        $pattern .= '(?:CHARACTER SET\s+(?P<character_set>:character_set))?\s*';
        $pattern .= '(?:COLLATE\s+(?P<collate>:collate))?\s*';
        $pattern .= '(?P<nullable>:nullable)?\s*';
        $pattern .= '(?P<auto_increment>AUTO_INCREMENT)?\s*';
        $pattern .= '(?:DEFAULT (?P<default>:default))?\s*';
        $pattern .= '(?:COMMENT (?P<comment>:comment))?\s*';
        $pattern .= '(?:,|$)';
        $pattern .= '/ui';

        return $pattern;
    }

    /**
     * @return string
     */
    protected function getDataTypeRegex()
    {
        return implode('|', self::$columnTypeRegex);
    }
}
