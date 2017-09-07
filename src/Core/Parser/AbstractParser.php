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
     * @param string $subject
     *
     * @return array
     */
    protected function match($regexPattern, $subject)
    {
        $regexPattern = $this->prepareRegex($regexPattern);
        $matchCounts = preg_match($regexPattern, $subject, $matches);

        return $matchCounts ? $matches : [];
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
        $filterRegex = $this->filterRegex($regex);

        return strtr($regex, $this->placeholders);
    }

    /**
     * Filters the regex string.
     *
     * @param string $regex
     *
     * @return string
     */
    protected function filterRegex($regex)
    {
        $regex = trim($regex);
        $regex = rtrim($regex, ';');
        $regex = str_replace(["\n", "\r"], ' ', $regex);
        $regex = preg_replace("~\s+~", ' ', $regex);
        $regex = preg_replace(["~\/\*!\d{5}\s*(.*?)\s*\*\/~ui"], ['$1'], $regex);

        return $regex;
    }
}
