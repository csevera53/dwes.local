<?php
namespace dwes\app\repository;

use dwes\app\entity\Exposicion;
use dwes\core\database\QueryBuilder;

class ExposicionesRepository extends QueryBuilder
{
    public function __construct(
        string $table = 'exposiciones',
        string $classEntity = Exposicion::class
    ) {
        parent::__construct($table, $classEntity);
    }
}