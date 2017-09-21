<?php

namespace Emanci\MysqlDiff\Core\Parser;

class ParserFactory
{
    /**
     * @param string $parserName
     *
     * @return mixed
     */
    public static function make($parserName)
    {
        $parserClassName = __NAMESPACE__.'\\'.ucfirst($parserName).'Parser';

        return new $parserClassName();
    }
}
