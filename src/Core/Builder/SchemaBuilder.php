<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Contracts\BuilderInterface;
use Emanci\MysqlDiff\Core\Parser\SchemaParser;
use Emanci\MysqlDiff\Models\Schema;
use InvalidArgumentException;

class SchemaBuilder implements BuilderInterface
{
    /**
     * @var SchemaParser
     */
    protected $schemaParser;

    /**
     * SchemaBuilder construct.
     *
     * @param SchemaParser $schemaParser
     */
    public function __construct(SchemaParser $schemaParser)
    {
        $this->schemaParser = $schemaParser;
    }

    /**
     * @param string $statement
     *
     * @return Schema
     */
    public function create($statement)
    {
        $result = $this->schemaParser->parse($statement);

        if (empty($result)) {
            throw new InvalidArgumentException('Failed to parse Schema');
        }

        $attributes = [
            'character_set' => array_get($result, 'character_set'),
            'collate' => array_get($result, 'collate'),
        ];

        return new Schema($result['name'], $attributes);
    }
}
