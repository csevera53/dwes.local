<div class="hero hero-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mx-auto text-center">
                <div class="intro-wrap">
                    <h1 class="mb-0">Exposiciones</h1>
                    <p class="text-white">Organiza y gestiona tus exposiciones temporales</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Principal Content Start -->
<div id="exposiciones">
    <div class="container">
        <div class="col-xs-12 col-sm-8 col-sm-push-2">
            <h2>Crear nueva exposición:</h2>
            <hr>
            
            <!-- Mostrar errores y mensajes -->
            <?php include __DIR__ . '/show-error.part.view.php'; ?>
            
            <!-- Formulario para crear exposición -->
            <form class="form-horizontal" action="/exposiciones/nueva" method="post">
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Nombre *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="activa" value="1" checked> Activa (permite añadir imágenes)
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <button type="submit" class="pull-right btn btn-lg sr-button">CREAR EXPOSICIÓN</button>
                    </div>
                </div>
            </form>
            
            <hr class="divider">
            
            <!-- Listado de exposiciones -->
            <h2>Mis exposiciones:</h2>
            <div class="tabla-exposiciones">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($exposiciones)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No has creado ninguna exposición todavía</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($exposiciones as $exposicion): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($exposicion->getNombre()) ?></strong></td>
                                    <td><?= htmlspecialchars($exposicion->getDescripcion()) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($exposicion->getFechaInicio())) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($exposicion->getFechaFin())) ?></td>
                                    <td>
                                        <?php if ($exposicion->getActiva()): ?>
                                            <span class="label label-success">Activa</span>
                                        <?php else: ?>
                                            <span class="label label-default">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>