<!-- Principal Content Start -->
<div id="galeria">
    <div class="container">
        <div class="col-xs-12 col-sm-8 col-sm-push-2">
            <h1>IMAGEN GALERIA</h1>
            <hr>
            <div class="imagenes_galeria">
                <img src="<?= $imagen->getUrlSubidas() ?>" alt="<?= $imagen->getDescripcion() ?>" title="<?=
                                                                                                            $imagen->getDescripcion() ?>" width="500px">
                <br>Descripción: <?= $imagen->getDescripcion() ?>
                <br>Número de visualizaciones: <?= $imagen->getNumVisualizaciones() ?>
                <br>Número de likes: <?= $imagen->getNumLikes() ?>
                <br>Número de downloads: <?= $imagen->getNumDownloads() ?>


            </div>
            <br>
            <a href="/galeria/editar/<?= $imagen->getId() ?>" class="btn btn-primary btn-lg sr-button">
                <i class="fa fa-edit"></i> Editar imagen
            </a>
            <a href="/galeria/eliminar/<?= $imagen->getId() ?>"
                class="btn btn-danger btn-lg sr-button"
                onclick="return confirm('¿Estás seguro de que quieres eliminar esta imagen? Esta acción no se puede deshacer.')">
                <i class="fa fa-trash"></i> Borrar imagen
            </a>
            <a href="/galeria" class="btn btn-default btn-lg sr-button">
                <i class="fa fa-arrow-left"></i> Volver a Galería
            </a>

        </div>
    </div>
</div>
<br>