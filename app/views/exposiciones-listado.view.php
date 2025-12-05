<div>
    <div class="container">
        <div>
            <div class="col-lg-6 mx-auto text-center">
                <div>
                    <h1>Listado de Exposiciones</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exposiciones-listado">
    <div class="container">
        <div class="col-xs-12">
            <h2>Exposiciones</h2>
            <hr>
            <?php include __DIR__ . '/show-error.part.view.php'; ?>


            <div class="tabla-exposiciones">
                <?php if (empty($exposiciones)): ?>
                    <div>
                        <strong>No hay exposiciones disponibles.</strong>
                        <p>Comienza creando tu primera exposición.</p>
                    </div>
                <?php else: ?>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exposiciones as $exposicion): ?>
                                <tr>
                                    <td><?= $exposicion->getId() ?></td>
                                    <td><a href="/exposiciones/<?= $exposicion->getId() ?>">
                                            <strong><?= htmlspecialchars($exposicion->getNombre()) ?></strong>
                                        </a></td>
                                    <td><?= htmlspecialchars($exposicion->getDescripcion()) ?></td>
                                    <td class="text-center">
                                        <?php if ($exposicion->getFechaInicio()): ?>
                                            <?= date('d/m/Y H:i', strtotime($exposicion->getFechaInicio())) ?>
                                        <?php else: ?>
                                            <em class="text-muted">No disponible</em>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($exposicion->getFechaFin()): ?>
                                            <?= date('d/m/Y H:i', strtotime($exposicion->getFechaFin())) ?>
                                        <?php else: ?>
                                            <em class="text-muted">No disponible</em>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($exposicion->getActiva()): ?>
                                            <span class="label label-success">
                                                Activa
                                            </span>
                                        <?php else: ?>
                                            <span class="label label-default">
                                                Inactiva
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>