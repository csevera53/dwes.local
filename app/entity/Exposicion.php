<?php
namespace dwes\app\entity;

Use dwes\app\entity\IEntity;

class Exposicion implements IEntity
{
    private $id;
    private $nombre;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_fin;
    private $activa;
    private $usuario;

    public function __construct(
        string $nombre = '',
        string $descripcion = '',
        bool $activa = true,
        int $usuario = 0
    ) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->activa = $activa;
        $this->usuario = $usuario;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getFechaInicio(): string
    {
        return $this->fecha_inicio ?? '';
    }

    public function getFechaFin(): string
    {
        return $this->fecha_fin ?? '';
    }

    public function getActiva(): bool
    {
        return (bool)$this->activa;
    }

    public function getUsuario(): int
    {
        return $this->usuario;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function setFechaInicio(string $fecha_inicio): void
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin(string $fecha_fin): void
    {
        $this->fecha_fin = $fecha_fin;
    }

    public function setActiva(bool $activa): void
    {
        $this->activa = $activa;
    }

    public function setUsuario(int $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'activa' => $this->getActiva() ? 1 : 0,
            'usuario' => $this->getUsuario()
        ];
    }
}