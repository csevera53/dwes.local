<?php

namespace dwes\app\controllers;

use dwes\app\entity\Asociado;
use dwes\app\exceptions\FileException;
use dwes\app\repository\AsociadosRepository;
use dwes\core\App;
use dwes\app\utils\File;
use dwes\core\Response;

class AsociadoController
{
    public function index()
    {
        $errores = [];
        $nombre = "";
        $descripcion = "";
        $titulo = "";
        $logo = "";

        try {

            $asociadosRepository =  App::getRepository(AsociadosRepository::class);
        } catch (FileException $fileException) {
            $errores[] = $fileException->getMessage();
        }
        
        Response::renderView(
            'asociados',
            'layout',
            compact('errores', 'nombre', 'descripcion', 'titulo', 'logo')
        );
    }
    public function nueva()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $asociadosRepository =  new AsociadosRepository();
            $nombre = trim(htmlspecialchars($_POST['nombre'] ?? ""));
            $descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? ""));
            if ($nombre == "") {
                $mensaje = "";
                $errores[] = "El nombre no debe estar vacío";
            } else {
                $captcha = $_POST['captcha'] ?? "";
                if ($captcha != "") {
                    if ($_SESSION['captchaGenerado'] != $captcha) {
                        $mensaje = "";
                        $errores[] = "¡Ha introducido un código de seguridad incorrecto! Inténtelo de nuevo";
                    } else { // Todo correcto y se guardan los datos
                        $tiposAceptados = ['image/jpeg', 'image/gif', 'image/png'];
                        $logo = new File('logo', $tiposAceptados); // El nombre logo' es el que se ha puesto en el formulario de asociados.view.php
                        $logo->saveUploadFile(Asociado::RUTA_LOGOS_ASOCIADOS);
                        $asociado = new Asociado($nombre, $logo->getFileName(), $descripcion);
                        $asociadosRepository->save($asociado);
                        $mensaje = "Datos subidos correctamente";
                    }
                } else {
                    $mensaje = "";
                    $errores[] = "Introduzca el código de seguridad";
                }
            }
        }

        App::get('router')->redirect('asociados');

        Response::renderView(
            'asociados_nueva',
            'layout',
        );
    }
}
