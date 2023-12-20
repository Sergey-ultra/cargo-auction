<?php

declare(strict_types=1);

namespace App\BulkInsert;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class BulkInsert
{
    public function __construct(private Connection $connection){}

    public function execute(string $table, array $dataset, array $types = []): int
    {
        if (empty($dataset)) {
            return 0;
        }

        $sql = $this->sql($this->connection->getDatabasePlatform(), new Identifier($table), $dataset);

        if (method_exists($this->connection, 'executeStatement')) {
            return $this->connection->executeStatement($sql, $this->parameters($dataset), $this->types($types, count($dataset)));
        }

        return $this->connection->executeStatement($sql, $this->parameters($dataset), $this->types($types, count($dataset)));
    }

    public function transactional(string $table, array $dataset, array $types = [])
    {
        return $this->connection->transactional(fn () => $this->execute($table, $dataset, $types));
    }



    private function sql(AbstractPlatform $platform, Identifier $table, array $dataset): string
    {
        $columns = $this->quote_columns($platform, $this->extract_columns($dataset));

        $sql = sprintf(
            'INSERT INTO %s %s VALUES %s;',
            $table->getQuotedName($platform),
            $this->stringify_columns($columns),
            $this->generate_placeholders(count($columns), count($dataset))
        );

        return $sql;
    }

    private function extract_columns(array $dataset): array
    {
        if (empty($dataset)) {
            return [];
        }

        $first = reset($dataset);

        return array_keys($first);
    }

    private function quote_columns(AbstractPlatform $platform, array $columns): array
    {
        $mapper = static fn (string $column) => (new Identifier($column))->getQuotedName($platform);

        return array_map($mapper, $columns);
    }

    private function stringify_columns(array $columns): string
    {
        return empty($columns) ? '' : sprintf('(%s)', implode(', ', $columns));
    }

    private function generate_placeholders(int $columnsLength, int $datasetLength): string
    {
        // (?, ?, ?, ?)
        $placeholders = sprintf('(%s)', implode(', ', array_fill(0, $columnsLength, '?')));

        // (?, ?), (?, ?)
        return implode(', ', array_fill(0, $datasetLength, $placeholders));
    }

    private function parameters(array $dataset): array
    {
        $reducer = static fn (array $flattenedValues, array $dataset) => array_merge($flattenedValues, array_values($dataset));

        return array_reduce($dataset, $reducer, []);
    }

    private function types(array $types, int $datasetLength): array
    {
        if (empty($types)) {
            return [];
        }

        $types = array_values($types);

        $positionalTypes = [];

        for ($idx = 1; $idx <= $datasetLength; $idx++) {
            $positionalTypes = array_merge($positionalTypes, $types);
        }

        return $positionalTypes;
    }
}
