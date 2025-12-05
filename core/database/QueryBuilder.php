<?php

namespace dwes\core\database;

use dwes\app\exceptions\NotFoundException;
use dwes\app\exceptions\QueryException;
use dwes\app\entity\IEntity;
use dwes\core\App;
use PDO;
use PDOException;

class QueryBuilder
{
    private $table;
    private $classEntity;
    private $connection;
    public function __construct(string $table, string $classEntity)
    {
        $this->connection = App::getConnection();
        $this->table = $table;
        $this->classEntity = $classEntity;
    }
    /**
     * @param string $tabla
     * @param string $classEntity
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        return $this->executeQuery($sql);
    }
    /**
     * @param int $id
     * @return IEntity
     * @throws NotFoundException
     * @throws QueryException
     */
    public function find(int $id): IEntity
    {
        $sql = "SELECT * FROM $this->table WHERE id=$id";
        $result = $this->executeQuery($sql);
        if (empty($result))
            throw new NotFoundException("No se ha encontrado ningún elemento con id $id.");
        return $result[0]; 
    }

    public function findByUsuario(int $idUsuario): array
    {
        $sql = "SELECT * FROM $this->table WHERE usuario_id = :usuario_id";
        return $this->executeQuery($sql, ['usuario_id' => $idUsuario]);
    }


    /**
     * @param string $sql
     * @return array
     * @throws QueryException
     */
    private function executeQuery(string $sql, array $parameters = []): array
    {
        $pdoStatement = $this->connection->prepare($sql);
        if ($pdoStatement->execute($parameters) === false)
            throw new QueryException("No se ha podido ejecutar la query solicitada.");
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
    }

    /**
     * @param IEntity $entity
     * @return void
     * @throws QueryException
     */
    public function save(IEntity $entity): void
    {
        try {
            $parametrers = $entity->toArray();
            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $this->table,
                implode(', ', array_keys($parametrers)),
                ':' . implode(', :', array_keys($parametrers))
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parametrers);
        } catch (PDOException $exception) {
            throw new QueryException("Error al insertar en la base de datos.");
        }
    }

    public function executeTransaction(callable $fnExecuteQuerys)
    {
        try {
            $this->connection->beginTransaction();
            $fnExecuteQuerys();
            $this->connection->commit();
        } catch (PDOException $pdoException) {
            $this->connection->rollBack();
            throw new QueryException("No se ha podido realizar la operación.");
        }
    }

    public function getUpdates(array $parameters)
    {
        $updates = '';
        foreach ($parameters as $key => $value) {
            if ($key !== 'id')
                if ($updates !== '')
                    $updates .= ", ";
            $updates .= $key . '=:' . $key;
        }
        return $updates;
    }
    public function update(IEntity $entity): void
    {
        try {
            $parameters = $entity->toArray();
            $sql = sprintf(
                'UPDATE %s SET %s WHERE id=:id',
                $this->table,
                $this->getUpdates($parameters)
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (PDOException $pdoException) {
            throw new QueryException("No se ha podido actualizar el elemento con id " . $parameters['id']);
        }
    }

    public function findBy(array $filters): array
    {
        $sql = "SELECT * FROM $this->table " . $this->getFilters($filters);
        return $this->executeQuery($sql, $filters);
    }

    public function getFilters(array $filters)
    {
        if (empty($filters)) return '';
        $strFilters = [];
        foreach ($filters as $key => $value)
            $strFilters[] = $key . '=:' . $key;
        return ' WHERE ' . implode(' and ', $strFilters);
    }

    public function findOneBy(array $filters): ?IEntity
    {
        $result = $this->findBy($filters);
        if (count($result) > 0)
            return $result[0];
        return null;
    }

    public function borrar(int $id): void
    {
        try {
            $sql = "DELETE FROM $this->table WHERE id = :id";
            $imagenBorrar = $this->connection->prepare($sql);
            $imagenBorrar->execute(['id' => $id]);
        } catch (PDOException $exception) {
            throw new QueryException("No se ha podido eliminar el elemento con id $id: " . $exception->getMessage());
        }
    }
    
    public function findExposByUsuario(int $idUsuario): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE usuario = :usuario ORDER BY id DESC";
        return $this->executeQuery($sql, ['usuario' => $idUsuario]);
    }

    public function findActivas(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE activa = 1 ORDER BY fecha_inicio DESC";
        return $this->executeQuery($sql);
    }

    public function imagenYaEnExposicion(int $expoId, int $imgId): bool
    {
        $result = $this->findBy([
            'id_expo' => $expoId,
            'id_img' => $imgId
        ]);
        return !empty($result);
    }

    public function getImagenesDeExposicion(int $expoId): array
    {
        $sql = "SELECT img_id FROM {$this->table} WHERE id_expo = :id_expo";
        return $this->executeQuery($sql, ['id_expo' => $expoId]);
    }

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE id IN ($placeholders)";
        
        $statement = $this->connection->prepare($sql);
        $statement->execute($ids);
        
        return $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
    }
    public function getIdsColumna(string $columnaId, string $columnaFiltro, $valorFiltro): array
    {
        $sql = "SELECT {$columnaId} FROM {$this->table} WHERE {$columnaFiltro} = :valor";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute(['valor' => $valorFiltro]);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            return array_column($results, $columnaId);
        } catch (PDOException $exception) {
            throw new QueryException("Error al obtener IDs: " . $exception->getMessage());
        }
    }
    public function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}" . $this->getFilters($filters);
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($filters);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['total'];
        } catch (PDOException $exception) {
            throw new QueryException("Error al contar registros: " . $exception->getMessage());
        }
    }

    /**
     * @param array $filters
     * @return bool
     */
    public function exists(array $filters): bool
    {
        return $this->count($filters) > 0;
    }
}