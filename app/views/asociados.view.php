<?php
// require_once __DIR__ . '/inicio.part.php';
// require_once __DIR__ . '/navegacion.part.php';
?>



<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
                <div class="alert alert-<?= empty($errores) ? 'info' : 'danger'; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <?php if (empty($errores)) : ?>
                        <p><?= $mensaje ?></p>
                    <?php else : ?>
                        <ul>
                            <?php foreach ($errores as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
<form clas="form-horizontal" action="/asociados/nuevo" method="post"
                enctype="multipart/form-data">
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Imagen</label>
                        <input class="form-control-file" type="file" name="logo">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?=$nombre?>" required>
                        <label class="label-control">Descripción</label>
                        <textarea class="form-control" name="descripcion"></textarea>
                        <!-- CAPTCAHA -->
                        <label class="label-control">Introduce el captcha <img style="border: 1px solid #D3D0D0 "
                        src="C:\xampp\htdocs\servidor\dwes.local\src\utils\Captcha.php" id='captcha'></label>
                        <input class="form-control" type="text" name="captcha">
                        <button class="pull-right btn btn-lg sr-button">ENVIAR</button>
                    </div>
                </div>
            </form>

<?php 
// require_once __DIR__ . "/fin.part.php";