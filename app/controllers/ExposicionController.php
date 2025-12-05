<?php

namespace dwes\app\controllers;

use dwes\app\exceptions\AppException;
use dwes\app\exceptions\QueryException;
use dwes\app\exceptions\ValidationException;
use dwes\core\App;
use dwes\app\repository\ExposicionesRepository;
use dwes\app\entity\Exposicion;
use dwes\core\Response;
use dwes\core\helpers\FlashMessage;
use dwes\app\exceptions\NotFoundException;
use dwes\app\repository\ImagenesRepository;
use dwes\app\repository\ExpoImgRepository;
use dwes\app\entity\ExpoImg;

class ExposicionController
{
    public function index()
    {
        $errores = FlashMessage::get('errores', []);
        $mensaje = FlashMessage::get('mensaje');

        try {
            $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
            $usuario = App::get('appUser');
            
            if (!is_null($usuario) && $usuario->getRole() === 'ROLE_ADMIN') {
                $exposiciones = $exposicionesRepository->findAll();
            } elseif (!is_null($usuario)) {
                $exposiciones = $exposicionesRepository->findExposByUsuario($usuario->getId());
            } else {
                $exposiciones = [];
            }
            
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
            $exposiciones = [];
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
            $exposiciones = [];
        }
        
        Response::renderView(
            'exposiciones',
            'layout',
            compact('errores', 'mensaje', 'exposiciones')
        );
    }

    public function nueva()
    {
        try {
            $usuario = App::get('appUser');
            
            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado para crear exposiciones']);
                App::get('router')->redirect('exposiciones');
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = trim(htmlspecialchars($_POST['nombre'] ?? ''));
                $descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? ''));
                $activa = isset($_POST['activa']) ? true : false;

                if (empty($nombre)) {
                    throw new ValidationException('El nombre es obligatorio');
                }

                $exposicion = new Exposicion(
                    $nombre,
                    $descripcion,
                    $activa,
                    $usuario->getId()
                );

                $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
                $exposicionesRepository->save($exposicion);

                $mensaje = "Se ha creado la exposición: " . $exposicion->getNombre();
                App::get('logger')->add($mensaje);
                FlashMessage::set('mensaje', $mensaje);
            }

        } catch (ValidationException $validationException) {
            FlashMessage::set('errores', [$validationException->getMessage()]);
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
        }

        App::get('router')->redirect('exposiciones');
    }

    public function listado()
    {
        $errores = FlashMessage::get('errores', []);
        $mensaje = FlashMessage::get('mensaje');

        try {
            $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
            $usuario = App::get('appUser');
            
            if (!is_null($usuario)) {
                if ($usuario->getRole() === 'ROLE_ADMIN') {
                    $exposiciones = $exposicionesRepository->findAll();
                } else {
                    $exposiciones = $exposicionesRepository->findExposByUsuario($usuario->getId());
                }
            } else {
                $exposiciones = [];
            }
            
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
            $exposiciones = [];
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
            $exposiciones = [];
        }
        
        Response::renderView(
            'exposiciones-listado',
            'layout',
            compact('errores', 'mensaje', 'exposiciones')
        );
    }

    /**
     * @param int $imagenid
     */
    public function anadirImagen($id)
    {
        try {
            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagen = $imagenesRepository->find($id);

            $usuario = App::get('appUser');
            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado']);
                App::get('router')->redirect('galeria');
                return;
            }

            if ($usuario->getRole() !== 'ROLE_ADMIN' && $imagen->getUsuarioId() !== $usuario->getId()) {
                FlashMessage::set('errores', ['No puedes añadir imágenes de otros usuarios']);
                App::get('router')->redirect('galeria');
                return;
            }

            $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
            $exposiciones = $exposicionesRepository->findActivas();

            $errores = FlashMessage::get('errores', []);
            $mensaje = FlashMessage::get('mensaje');

            Response::renderView(
                'expo-add-img',
                'layout',
                compact('imagen', 'exposiciones', 'errores', 'mensaje')
            );

        } catch (NotFoundException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
            App::get('router')->redirect('galeria');
        } catch (QueryException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
            App::get('router')->redirect('galeria');
        } catch (AppException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
            App::get('router')->redirect('galeria');
        }
    }

    /**
     * @param int $imagenid
     * @param int $exposicionid
     */
    public function guardarImagen($imagenid, $exposicionid)
    {
        try {
            $usuario = App::get('appUser');

            if (is_null($usuario)) {
                FlashMessage::set('errores', ['Debes estar logueado']);
                App::get('router')->redirect('galeria');
                return;
            }

            $imagenesRepository = App::getRepository(ImagenesRepository::class);
            $imagen = $imagenesRepository->find($imagenid);

            if ($usuario->getRole() !== 'ROLE_ADMIN' && $imagen->getUsuarioId() !== $usuario->getId()) {
                FlashMessage::set('errores', ['No puedes añadir imágenes de otros usuarios']);
                App::get('router')->redirect('galeria');
                return;
            }

        
            $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
            $exposicion = $exposicionesRepository->find($exposicionid);

            if (!$exposicion->getActiva()) {
                FlashMessage::set('errores', ['Esta exposición no está activa']);
                App::get('router')->redirect('galeria');
                return;
            }

            $ExpoImgRepository = App::getRepository(ExpoImgRepository::class);

            if ($ExpoImgRepository->imagenYaEnExposicion($exposicionid, $imagenid)) {
                FlashMessage::set('errores', ['Esta imagen ya está en la exposición']);
                App::get('router')->redirect('galeria');
                return;
            }

            $ExpoImg = new ExpoImg($imagenid, $exposicionid);
            $ExpoImgRepository->save($ExpoImg);

            $mensaje = "Imagen añadida a la exposición: " . $exposicion->getNombre();
            App::get('logger')->add($mensaje);
            FlashMessage::set('mensaje', $mensaje);

        } catch (NotFoundException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
        } catch (QueryException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
        } catch (AppException $e) {
            FlashMessage::set('errores', [$e->getMessage()]);
        }

        App::get('router')->redirect('galeria');
    }
    public function show($id)
{
    $errores = FlashMessage::get('errores', []);
    $mensaje = FlashMessage::get('mensaje');

    try {
        $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
        $exposicion = $exposicionesRepository->find($id);

        if (is_null($exposicion)) {
            throw new NotFoundException('La exposición no existe');
        }

        $usuario = App::get('appUser');
        if (is_null($usuario)) {
            FlashMessage::set('errores', ['Debes estar logueado para ver las exposiciones']);
            App::get('router')->redirect('login');
            return;
        }

        if ($usuario->getRole() !== 'ROLE_ADMIN' && $exposicion->getUsuario() !== $usuario->getId()) {
            FlashMessage::set('errores', ['No tienes permiso para ver esta exposición']);
            App::get('router')->redirect('exposiciones/listado');
            return;
        }

        $expoImgRepository = App::getRepository(ExpoImgRepository::class);
        
        $imagenesIds = $expoImgRepository->getIdsColumna('id_img', 'id_expo', $id);

        $imagenesRepository = App::getRepository(ImagenesRepository::class);
        $imagenes = $imagenesRepository->findByIds($imagenesIds);

    } catch (NotFoundException $e) {
        FlashMessage::set('errores', [$e->getMessage()]);
        App::get('router')->redirect('exposiciones/listado');
        return;
    } catch (QueryException $e) {
        FlashMessage::set('errores', [$e->getMessage()]);
        $imagenes = [];
    } catch (AppException $e) {
        FlashMessage::set('errores', [$e->getMessage()]);
        $imagenes = [];
    }

    Response::renderView(
        'exposicion-show',
        'layout',
        compact('exposicion', 'imagenes', 'errores', 'mensaje')
    );
}
}