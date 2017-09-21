<?php

namespace Emanci\MysqlDiff\Core\Builder;

use Emanci\MysqlDiff\Core\Parser\SchemaParser;
use Emanci\MysqlDiff\Models\Schema;
use InvalidArgumentException;

class SchemaBuilder
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
     * @return \Emanci\MysqlDiff\Models\Schema
     */
    public function buildSchema($statement)
    {
        $schemaStruct = $this->schemaParser->parse($statement);

        if (empty($schemaStruct)) {
            throw new InvalidArgumentException('Failed to parse Schema');
        }

        return $this->createSchemaDefinition($schemaStruct[0]);
    }

    /**
     * @param array $data
     *
     * @return \Emanci\MysqlDiff\Models\Schema
     */
    protected function createSchemaDefinition($data)
    {
        $attributes = [
            'character_set' => array_get($data, 'character_set'),
            'collate' => array_get($data, 'collate'),
        ];

        return new Schema($data['name'], $attributes);
    }
}
