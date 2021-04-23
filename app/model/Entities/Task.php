<?php

namespace App\model\Entities;

use Nette\Database\Table\ActiveRow;

class Task extends AEntity {

    public ?int $id = null;

    public string $name = "";

    public string $description = "";

    public string $created = "";

    public string $userId = "";

    public bool $isCompleted = false;

    protected static function getTableName(): string {
        return "task";
    }

    protected static function fromDatabase(ActiveRow $array): AEntity {
        $task = new self();
        $task->id = $array["id"];
        $task->name = $array["name"] ?? "";
        $task->description = $array["description"] ?? "";
        $task->created = $array["created"] ?? "";
        $task->userId = $array["user"] ?? "";
        $task->isCompleted = $array["completed"] ?? false;

        return $task;
    }

    public static function fromArray(array $array): AEntity {
        $task = new self();
        $task->id = $array["id"] ?? null;
        $task->name = $array["name"] ?? "";
        $task->description = $array["description"] ?? "";
        $task->created = $array["created"] ?? (new \DateTime())->format("now");
        $task->userId = $array["user"] ?? "";
        $task->isCompleted = $array["completed"] === "false" ? false : true;

        return $task;
    }

    protected function toDatabase(): array {
        $entityAsArray = [];
        $entityAsArray["id"] = $this->id;
        $entityAsArray["description"] = $this->description;
        $entityAsArray["name"] = $this->name;
        $entityAsArray["created"] = $this->created;
        $entityAsArray["user"] = $this->userId;
        $entityAsArray["completed"] = $this->isCompleted;

        return $entityAsArray;
    }

    public function isValid(): bool {
        return true;
    }
}
