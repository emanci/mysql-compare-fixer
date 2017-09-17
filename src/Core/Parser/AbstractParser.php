<?php

namespace Emanci\MysqlDiff\Core\Parser;

use Emanci\MysqlDiff\Contracts\ParserInterface;

abstract class AbstractParser implements ParserInterface
{
    /**
     * @var array
     */
    protected $placeholders = [];

    /**
     * @return string
     */
    abstract protected function getRegexPattern();

    /*
     * Parse an SQL statement.
     *
     * @param string $statement
     *
     * @return mixed
     */
    public function parse($statement)
    {
        $regexPattern = $this->getRegexPattern();

        return $this->match($regexPattern, $statement);
    }

    /**
     * Perform a regular expression match.
     *
     * @param string $regexPattern
     * @param string $statement
     *
     * @return array
     */
    protected function match($regexPattern, $statement)
    {
        $regexPattern = $this->prepareRegex($regexPattern);
        $statement = $this->filterStatement($statement);

        preg_match_all($regexPattern, $statement, $matches, PREG_SET_ORDER);

        return $matches;
    }

    /**
     * Prepare the regex string.
     *
     * @param string $regex
     *
     * @return string
     */
    protected function prepareRegex($regex)
    {
        return strtr($regex, $this->placeholders);
    }

    /**
     * Filters the SQL statement string.
     *
     * @param string $regex
     *
     * @return string
     */
    protected function filterStatement($statement)
    {
        $statement = trim($statement);
        $statement = rtrim($statement, ';');
        $statement = str_replace(["\n", "\r"], ' ', $statement);
        $statement = preg_replace("~\s+~", ' ', $statement);
        $statement = preg_replace(["~\/\*!\d{5}\s*(.*?)\s*\*\/~ui"], ['$1'], $statement);

        return $statement;
    }
}
