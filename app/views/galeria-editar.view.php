<div id="galeria">
    <div class="container">
        <div class="col-xs-12 col-sm-8 col-sm-push-2">
            <h1>EDITAR IMAGEN</h1>
            <hr>
            
            <?php include __DIR__ . '/show-error.part.view.php'; ?>
            
            <div class="imagenes_galeria" style="margin-bottom: 20px;">
                <img src="<?= $imagen->getUrlSubidas() ?>" 
                     alt="<?= $imagen->getDescripcion() ?>" 
                     title="<?= $imagen->getDescripcion() ?>" 
                     width="300px">
            </div>
            
            <form class="form-horizontal" action="/galeria/actualizar/<?= $imagen->getId() ?>" method="post">
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Nombre de archivo</label>
                        <input type="text" class="form-control" value="<?= $imagen->getNombre() ?>" disabled>
                        <small class="text-muted">El nombre del archivo no se puede modificar</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="label-control">Descripción</label>
                        <textarea class="form-control" 
                                  name="descripcion" 
                                  rows="4" 
                                  required><?= htmlspecialchars($imagen->getDescripcion()) ?></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-lg sr-button">
                            <i class="fa fa-save"></i> GUARDAR CAMBIOS
                        </button>
                        <a href="/galeria/<?= $imagen->getId() ?>" class="btn btn-default btn-lg sr-button">
                            <i class="fa fa-times"></i> CANCELAR
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>