<?php

namespace dwes\app\controllers;

use dwes\app\exceptions\QueryException;
use dwes\app\entity\Imagen;
use dwes\app\entity\Asociado;
use dwes\core\Response;

class PageController
{
    /**
     * @throws QueryException
     */
    public function index()
    {
        // Esta sería la forma de obtener el array de imágenes a partir de la base de datos:
        //$imagenGaleria = App::getRepository( ImagenGaleriaRepository::class)->findAll();
        //$asociadosLista = App::getRepository( AsociadosRepository::class)->findAll();
        // Forma estática de crear los arrays de imágenes:
        $imagenesHome[] = new Imagen('1.jpg', 'Descripción imagen 1', '1', 456, 610, 130);
        $imagenesHome[] = new Imagen('2.jpg', 'Descripción imagen 2', '1', 320, 450, 85);
        $imagenesHome[] = new Imagen('3.jpg', 'Descripción imagen 3', '1', 280, 390, 72);
        $imagenesHome[] = new Imagen('4.jpg', 'Descripción imagen 4', '1', 510, 720, 145);
        $imagenesHome[] = new Imagen('5.jpg', 'Descripción imagen 5', '1', 380, 520, 95);
        $imagenesHome[] = new Imagen('6.jpg', 'Descripción imagen 6', '1', 425, 610, 108);
        $imagenesHome[] = new Imagen('7.jpg', 'Descripción imagen 7', '1', 310, 480, 88);
        $imagenesHome[] = new Imagen('8.jpg', 'Descripción imagen 8', '1', 560, 780, 165);
        $imagenesHome[] = new Imagen('9.jpg', 'Descripción imagen 9', '1', 290, 410, 76);
        $imagenesHome[] = new Imagen('10.jpg', 'Descripción imagen 10', '1', 470, 650, 120);
        $imagenesHome[] = new Imagen('11.jpg', 'Descripción imagen 11', '1', 340, 490, 92);
        $imagenesHome[] = new Imagen('12.jpg', 'Descripción imagen 12', '1', 505, 700, 138);

        $asociadosHome[] = new Asociado("A1", "log1.jpg", "descripcion A1");
        $asociadosHome[] = new Asociado("A2", "log2.jpg", "descripcion A2");
        $asociadosHome[] = new Asociado("A3", "log3.jpg", "descripcion A2");

        Response::renderView(
            'index',
            'layout',
            compact('imagenesHome', 'asociadosHome')
        );
    }
    public function about()
    {
        $imagenesClientes[] = new Imagen('client1.jpg', 'MISS BELLA');
        $imagenesClientes[] = new Imagen('client2.jpg', 'DON LUIS');
        $imagenesClientes[] = new Imagen('client3.jpg', 'MISS ARABELLA');
        $imagenesClientes[] = new Imagen('client4.jpg', 'DON LORENZO');
        Response::renderView(
            'about',
            'layout',
            compact('imagenesClientes')
        );
    }

    public function blog()
    {
        Response::renderView(
            'blog',
            'layout',
        );
    }
    public function post()
    {
        Response::renderView(
            'post',
            'layout',
        );
    }

    public function contact()
    {
        Response::renderView(
            'contact',
            'layout',
        );
    }
}
