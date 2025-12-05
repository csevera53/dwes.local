<?php

namespace dwes\app\repository;

use dwes\core\database\QueryBuilder;
use dwes\app\entity\ExpoImg;
use dwes\app\exceptions\QueryException;
use PDO;
use PDOException;
use dwes\core\App;

class ExpoImgRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(
        string $table = 'expo_img',
        string $classEntity = ExpoImg::class
    ) {
        parent::__construct($table, $classEntity);
    }
}