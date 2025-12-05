<div>
    <div class="container">
        <div>
            <div>
                <div class="intro-wrap">
                    <h1><?= htmlspecialchars($exposicion->getNombre()) ?></h1>
                    <p class="text-white">
                        <?php if ($exposicion->getActiva()): ?>
                            <span class="label label-success" style="font-size: 16px;">
                                Exposición Activa
                            </span>
                        <?php else: ?>
                            <span class="label label-default" style="font-size: 16px;">
                                Exposición Inactiva
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <div class="col-xs-12">
            <?php include __DIR__ . '/show-error.part.view.php'; ?>

            <!-- Información de la exposición -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Información de la Exposición
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Descripción:</strong></p>
                            <p class="text-muted">
                                <?= !empty($exposicion->getDescripcion()) 
                                    ? htmlspecialchars($exposicion->getDescripcion()) 
                                    : '<em>Sin descripción</em>' ?>
                            </p>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Fecha de Inicio:</strong></p>
                                    <p class="text-muted">
                                        <?php if ($exposicion->getFechaInicio()): ?>
                                            <i class="fa fa-calendar"></i> 
                                            <?= date('d/m/Y H:i', strtotime($exposicion->getFechaInicio())) ?>
                                        <?php else: ?>
                                            <em>No disponible</em>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Fecha de Fin:</strong></p>
                                    <p class="text-muted">
                                        <?php if ($exposicion->getFechaFin()): ?>
                                            <i class="fa fa-calendar"></i> 
                                            <?= date('d/m/Y H:i', strtotime($exposicion->getFechaFin())) ?>
                                        <?php else: ?>
                                            <em>No disponible</em>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Sección de imágenes -->
            <h2>
                Imágenes de la Exposición 
            </h2>
            <hr>

            <?php if (empty($imagenes)): ?>
                <div>
                    <h4>Esta exposición no tiene imágenes todavía</h4>
                    <p>Puedes añadir imágenes desde la <a href="/galeria">galería</a>.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($imagenes as $imagen): ?>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="margin-bottom: 30px;">
                            <div class="thumbnail">
                                <a href="<?= $imagen->getUrlSubidas() ?>" 
                                   title="<?= htmlspecialchars($imagen->getDescripcion()) ?>">
                                    <img src="<?= $imagen->getUrlSubidas() ?>" 
                                         alt="<?= htmlspecialchars($imagen->getDescripcion()) ?>"
                                         style="width: 100%; height: 250px; object-fit: cover;">
                                </a>
                                <div class="caption">
                                    <h5>
                                        <strong><?= htmlspecialchars($imagen->getNombre()) ?></strong>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <hr>
        </div>
    </div>
</div>