<?php

namespace App\model\Entities;

use Nette\Database\Table\ActiveRow;

class User extends AEntity {

    public ?int $id = null;

    public string $login;

    public string $password;

    public string $name;

    protected static function getTableName(): string {
        return "my_user";
    }

    protected static function fromDatabase(ActiveRow $array): AEntity {
        $user = new self();
        $user->id = $array["id"];
        $user->name = $array["name"] ?? "";
        $user->login = $array["login"] ?? "";
        $user->password = $array["password"] ?? "";

        return $user;
    }

    public static function fromArray(array $array): AEntity {
        $user = new self();
        $user->id = $array["id"];
        $user->name = $array["name"] ?? "";
        $user->login = $array["login"] ?? "";
        $user->password = $array["password"] ?? "";

        return $user;
    }

    protected function toDatabase(): array {
        $entityAsArray = [];
        $entityAsArray["id"] = $this->id;
        $entityAsArray["login"] = $this->login;
        $entityAsArray["name"] = $this->name;
        $entityAsArray["password"] = $this->password;

        return $entityAsArray;
    }

    public function isValid(): bool {
        return true;
    }
}
