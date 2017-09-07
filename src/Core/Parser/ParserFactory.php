<?php

namespace Emanci\MysqlDiff\Core\Parser;

class ParserFactory
{
    /**
     * @param string $parserName
     * @param string $statement
     *
     * @return mixed
     */
    public static function make($parserName, $statement)
    {
        $parserClassName = __NAMESPACE__.'\\'.ucfirst($parserName).'Parser';

        return new $parserClassName($statement);
    }
}
