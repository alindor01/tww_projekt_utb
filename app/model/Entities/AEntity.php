<?php

namespace App\model\Entities;

use App\model\Services\DatabaseProvider;
use Nette\Database\Table\ActiveRow;

abstract class AEntity {
    public ?int $id;

    abstract protected static function getTableName(): string;
    abstract protected static function fromDatabase(ActiveRow $array): self;
    abstract public static function fromArray(array $array): self;
    abstract protected function toDatabase(): array;
    abstract public function isValid(): bool;

    public static function getAll(): array {
        $db = DatabaseProvider::getDatabaseExplorer();
        $activeRows = $db->table(static::getTableName()) ?? [];

        $entities = [];
        foreach ($activeRows as $row) {
            $entities[] = static::fromDatabase($row);
        }

        return $entities;
    }

    public static function save(self $entity): ?self {
        $saved = null;
        $db = DatabaseProvider::getDatabaseExplorer();
        $statement = $entity->toDatabase();

        if ($entity->id) {
            $rows = $db->table(static::getTableName())
                ->where("id", $entity->id)
                ->update($statement);

            if ($rows > 0) {
                $activeRow = $db->table(static::getTableName())->get($entity->id);
                $saved = static::fromDatabase($activeRow);
            } else {
                $saved = null;
            }
        } else {
            // insert
            $activeRow = $db->table(static::getTableName())->insert($statement);
            $saved = static::fromDatabase($activeRow);
        }

        return $saved;
    }

    public static function get(int $id): self {
        $db = DatabaseProvider::getDatabaseExplorer();
        $activeRow = $db->table(static::getTableName())->get($id);

        return static::fromDatabase($activeRow);
    }

    public static function delete(int $id): void {
        $db = DatabaseProvider::getDatabaseExplorer();
        $db->table(static::getTableName())->where("id", $id)->delete();
    }

}
