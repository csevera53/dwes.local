<?php

namespace dwes\app\controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use dwes\app\exceptions\AppException;
use dwes\app\exceptions\QueryException;
use dwes\app\exceptions\ValidationException;
use dwes\core\App;
use dwes\app\repository\ExposicionesRepository;
use dwes\app\entity\Exposicion;
use dwes\core\Response;
use dwes\core\helpers\FlashMessage;

class ExposicionController
{
    /**
     * Lista todas las exposiciones
     */
    public function index()
    {
        $errores = FlashMessage::get('errores', []);
        $mensaje = FlashMessage::get('mensaje');

        try {
            $exposicionesRepository = App::getRepository(ExposicionesRepository::class);
            $usuario = App::get('appUser');
            
            // Si es admin, mostrar todas. Si es usuario normal, solo las suyas
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

    /**
     * Muestra el formulario para crear una nueva exposición
     */
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
                // Validar y sanitizar datos
                $nombre = trim(htmlspecialchars($_POST['nombre'] ?? ''));
                $descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? ''));
                $activa = isset($_POST['activa']) ? true : false;

                // Validaciones
                if (empty($nombre)) {
                    throw new ValidationException('El nombre es obligatorio');
                }

                // Crear la exposición (sin fechas, se añaden automáticamente)
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
}