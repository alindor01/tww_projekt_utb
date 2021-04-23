<?php

namespace App\model\Services;

use Nette\Caching\IStorage;
use Nette\Caching\Storages\FileStorage;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\Structure;

class DatabaseProvider {

    private static ?Explorer $explorer = null;
    private static ?Connection $connection = null;
    private static ?Structure $structure = null;
    private static ?IStorage $storage = null;

    public static function getDatabaseExplorer(): Explorer {
        if (!self::$explorer) {
            self::$storage ??= new FileStorage(__DIR__ . "/../../../temp");
            self::$connection ??=  new Connection("mysql:host=127.0.0.1;dbname=test", "admin", "admin");
            self::$structure ??= new Structure(self::$connection, self::$storage);

            self::$explorer = new Explorer(
                self::$connection,
                self::$structure,
                null,
                self::$storage
            );
        }

        return self::$explorer;
    }
}
