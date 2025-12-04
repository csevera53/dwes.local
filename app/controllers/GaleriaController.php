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
            
            $usuario = App::get('appUser');
            if (!is_null($usuario)) {
                if ($usuario->getRole() === 'ROLE_ADMIN') {
                    $imagenes = $imagenesRepository->findAll();
                } else {
                    $imagenes = $imagenesRepository->findByUsuario($usuario->getId());
                }
            } else {
                $imagenes = [];
            }
            
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

            $usuario = App::get('appUser');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $titulo = trim(htmlspecialchars($_POST['titulo']));
                FlashMessage::set('titulo', $titulo);
                $descripcion = trim(htmlspecialchars($_POST['descripcion']));
                FlashMessage::set('descripcion', $descripcion);
                $tiposAceptados = ['image/jpeg', 'image/gif', 'image/png'];
                $imagen = new File('imagen', $tiposAceptados);
                $imagen->saveUploadFile(Imagen::RUTA_IMAGENES_SUBIDAS);
                $imagenGaleria = new Imagen($imagen->getFileName(), $descripcion, 0, 0, 0, 0, $usuario->getId());
                $imagenesRepository->save($imagenGaleria);

                $mensaje = "Se ha guardado una imagen: " . $imagenGaleria->getNombre();
                App::get('logger')->add($mensaje);
                $mensaje = FlashMessage::get('mensaje');

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

    public function editar($id)
    {
        $errores = FlashMessage::get('errores', []);
        $mensaje = FlashMessage::get('mensaje');
        
        try {
            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagen = $imagenesRepository->find($id);
            
            $usuario = App::get('appUser');
            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado para editar imágenes']);
                App::get('router')->redirect('login');
                return;
            }
            
            if ($usuario->getRole() !== 'ROLE_ADMIN' && $imagen->getUsuarioId() !== $usuario->getId()) {
                FlashMessage::set('errores', ['No tienes permiso para editar esta imagen']);
                App::get('router')->redirect('galeria');
                return;
            }
            
            Response::renderView(
                'galeria-editar',
                'layout',
                compact('imagen', 'errores', 'mensaje')
            );
            
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
            App::get('router')->redirect('galeria');
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
            App::get('router')->redirect('galeria');
        }
    }

    public function actualizar($id)
    {
        try {
            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagen = $imagenesRepository->find($id);
            
            $usuario = App::get('appUser');
            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado para actualizar imágenes']);
                App::get('router')->redirect('login');
                return;
            }
            
            if ($usuario->getRole() !== 'ROLE_ADMIN' && $imagen->getUsuarioId() !== $usuario->getId()) {
                FlashMessage::set('errores', ['No tienes permiso para actualizar esta imagen']);
                App::get('router')->redirect('galeria');
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $descripcion = trim(htmlspecialchars($_POST['descripcion']));
                
                $imagen->setDescripcion($descripcion);
                $imagenesRepository->update($imagen);

                $mensaje = "Se ha actualizado la imagen: " . $imagen->getNombre();
                App::get('logger')->add($mensaje);
                FlashMessage::set('mensaje', $mensaje);
            }
            
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
        }

        App::get('router')->redirect('galeria');
    }

    public function eliminar($id)
    {
        try {
            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagen = $imagenesRepository->find($id);
            
            $usuario = App::get('appUser');
            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado para eliminar imágenes']);
                App::get('router')->redirect('login');
                return;
            }
            
            if ($usuario->getRole() !== 'ROLE_ADMIN' && $imagen->getUsuarioId() !== $usuario->getId()) {
                FlashMessage::set('errores', ['No tienes permiso para eliminar esta imagen']);
                App::get('router')->redirect('galeria');
                return;
            }

            $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . $imagen->getUrlSubidas();
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }

            $imagenesRepository->borrar($id);

            $mensaje = "Se ha eliminado la imagen: " . $imagen->getNombre();
            App::get('logger')->add($mensaje);
            FlashMessage::set('mensaje', $mensaje);
            
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
        }

        App::get('router')->redirect('galeria');
    }
}
