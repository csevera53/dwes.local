<?php
require_once __DIR__ . '/inicio.part.php';
require_once __DIR__ . '/navegacion.part.php';
?>

<div>
    <div class="container">
        <div>
            <div>
                <div class="intro-wrap">
                    <h1>Añadir imagen a exposición</h1>
                    <p>Selecciona una exposición activa</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Principal Content Start -->
<div id="anadir-imagen-exposicion">
    <div class="container">
        <div class="col-xs-12 col-sm-8 col-sm-push-2">
            
            <!-- Mostrar errores y mensajes -->
            <?php include __DIR__ . '/show-error.part.view.php'; ?>

            <!-- Información de la imagen -->
            <div>
                <div>
                    <h3 class="panel-title">Imagen seleccionada:</h3>
                </div>
                <div class="panel-body text-center">
                    <img src="<?= $imagen->getUrlSubidas() ?>" 
                         alt="<?= htmlspecialchars($imagen->getDescripcion()) ?>"
                         class="img-thumbnail" 
                         style="max-width: 300px;">
                    <p class="text-muted"><strong>Nombre:<?= htmlspecialchars($imagen->getNombre()) ?></strong></p>
                    <p>Descripción:<?= htmlspecialchars($imagen->getDescripcion()) ?></p>
                </div>
            </div>

            <hr>

            <h2>Selecciona una exposición activa:</h2>
            
            <?php if (empty($exposiciones)): ?>
                <div>
                    <p>No hay exposiciones activas disponibles en este momento.</p>
                    <a href="/exposiciones" class="btn btn-primary">Crear una exposición</a>
                </div>
            <?php else: ?>
                <div>
                    <?php foreach ($exposiciones as $exposicion): ?>
                        <div>
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="list-group-item-heading">
                                        <?= htmlspecialchars($exposicion->getNombre()) ?>
                                        <span class="label label-success">Activa</span>
                                    </h4>
                                    <p class="list-group-item-text">
                                        <?= htmlspecialchars($exposicion->getDescripcion()) ?>
                                    </p>
                                    <p class="text-muted">
                                        <small>
                                            <i class="fa fa-calendar"></i> 
                                            <?= date('d/m/Y', strtotime($exposicion->getFechaInicio())) ?> 
                                            - 
                                            <?= date('d/m/Y', strtotime($exposicion->getFechaFin())) ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <form method="POST" 
                                          action="/exposiciones/guardarimagen/<?= $imagen->getId() ?>/<?= $exposicion->getId() ?>"
                                          style="display: inline;">
                                        <button type="submit" 
                                                class ="btn btn-success"
                                                onclick="return confirm('¿Añadir esta imagen a la exposición: <?= htmlspecialchars($exposicion->getNombre()) ?>?')">
                                                Añadir a esta exposición
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/fin.part.php';
?>