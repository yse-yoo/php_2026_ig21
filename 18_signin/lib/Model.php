<?php

namespace Lib;

use Lib\Database;

class Model
{
    protected static function pdo()
    {
        return Database::getInstance();
    }
}
