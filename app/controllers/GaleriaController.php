<?php

namespace dwes\app\controllers;

use dwes\app\exceptions\AppException;
use dwes\app\exceptions\QueryException;
use dwes\core\App;
use dwes\app\repository\ImagenesRepository;
use dwes\app\utils\File;
use dwes\app\entity\Imagen;
use dwes\app\exceptions\FileException;
use dwes\core\Response;
use dwes\core\helpers\FlashMessage;

class GaleriaController
{
    public function index()
    {
        $errores = FlashMessage::get('errores', []);
        $mensaje = FlashMessage::get('mensaje');
        $titulo = FlashMessage::get('titulo');
        $descripcion = FlashMessage::get('descripcion');
        $categoriaSeleccionada = FlashMessage::get('categoriaSeleccionada');

        unset($_SESSION['errores']);
        unset($_SESSION['mensaje']);


        try {
            $conexion = App::getConnection();
            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagenes = $imagenesRepository->findAll();
        } catch (QueryException $queryException) {
            FlashMessage::set('errores' , [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores' , [$appException->getMessage()]);
        }
        Response::renderView(
            'galeria',
            'layout',
            compact('errores',  'descripcion', 'titulo', 'mensaje', 'imagenes')
        );
    }

    public function nueva()
    {
        try {
            $imagenesRepository = App::getRepository(ImagenesRepository::class);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $titulo = trim(htmlspecialchars($_POST['titulo']));
                FlashMessage::set('titulo', $titulo);
                $descripcion = trim(htmlspecialchars($_POST['descripcion']));
                FlashMessage::set('descripcion', $descripcion);
                $tiposAceptados = ['image/jpeg', 'image/gif', 'image/png'];
                $imagen = new File('imagen', $tiposAceptados); // El nombre 'imagen' es el que se ha puesto en el formulario de galeria.view.php
                $imagen->saveUploadFile(Imagen::RUTA_IMAGENES_SUBIDAS);
                $imagenGaleria = new Imagen($imagen->getFileName(), $descripcion);
                $imagenesRepository->save($imagenGaleria);

                
                

                $mensaje = "Se ha guardado una imagen: " . $imagenGaleria->getNombre();
                App::get('logger')->add($mensaje);
                $mensaje = FlashMessage::get('mensaje');

                // $mensaje = "Se ha guardado la imagen correctamente";
                // $imagenes = $imagenesRepository->findAll();
            }
        } catch (FileException $fileException) {
            FlashMessage::set('errores' , [$fileException->getMessage()]);
        } catch (QueryException $queryException) {
            FlashMessage::set('errores' , [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores' , [$appException->getMessage()]);

            printf($appException->getMessage());
        }

        App::get('router')->redirect('galeria');

        Response::renderView(
            'galeria_nueva',
            'layout',
            compact('galeria_nueva', 'errores')
        );
    }

    public function show($id)
    {
        $imagenesRepository = App::getRepository(ImagenesRepository::class);
        $imagen = $imagenesRepository->find($id);
        Response::renderView(
            'imagen-show',
            'layout',
            compact('imagen', 'imagenesRepository')
        );
    }
}
