<?php

namespace dwes\app\entity;

use dwes\app\entity\IEntity;

class ExpoImg implements IEntity
{
    /**
     */
    private $id_expo;

    /**
     */
    private $id_img;

    /**
     * @param int $id_img
     * @param int $id_expo
     */
    public function __construct(int $id_img = 0, int $id_expo = 0)
    {
        $this->id_img = $id_img;
        $this->id_expo = $id_expo;
    }

    /**
     * @return int
     */
    public function getIdImg(): int
    {
        return $this->id_img;
    }

    /**
     * @return ExpoImg
     */
    public function setIdImg(int $id_img): ExpoImg
    {
        $this->id_img = $id_img;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdExpo(): int
    {
        return $this->id_expo;
    }

    /**
     * @return ExpoImg
     */
    public function setIdExpo(int $id_expo): ExpoImg
    {
        $this->id_expo = $id_expo;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id_img' => $this->getIdImg(),
            'id_expo' => $this->getIdExpo()
        ];
    }
}