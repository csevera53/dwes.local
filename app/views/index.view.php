<?php
//Se quitan en el 16.2.5
// require_once __DIR__ . '/inicio.part.php';
// require_once __DIR__ . '/navegacion.part.php';

use dwes\app\utils\Utils;
?>
<!-- End of Navigation Bar -->
<!-- Principal Content Start -->
<div id="index">

  <!-- Header -->
  <div class="row">
    <div class="col-xs-12 intro">
      <div class="carousel-inner">
        <div class="item active">
          <img class="img-responsive" src="../public/images/index/woman.jpg" alt="header picture">
        </div>
        <div class="carousel-caption">
          <h1>FIND NICE PICTURES HERE</h1>
          <hr>
        </div>
      </div>
    </div>
  </div>

  <div id="index-body">
    <!-- Pictures Navigation table -->
    <div class="table-responsive">
      <table class="table text-center">
        <thead>
          <tr>
            <td><a class="link active" href="#category1" data-toggle="tab">category I</a></td>
            <td><a class="link" href="#category2" data-toggle="tab">category II</a></td>
            <td><a class="link" href="#category3" data-toggle="tab">category III</a></td>
          </tr>
        </thead>
      </table>
      <hr>
    </div>

    <!-- Navigation Table Content -->
    <div class="tab-content">

      <!-- First Category pictures -->

      <?php
      $idCategoria = 1;
      shuffle($imagenesHome);
      require_once "index_imagenes.part.php";
      ?>

      <!-- End of First category pictures -->

      <!--second category pictures -->

      <?php
      $idCategoria = 2;
      shuffle($imagenesHome);
      require_once "index_imagenes.part.php";
      ?>

      <!-- End of second category pictures -->

      <!-- Third Category Pictures -->

      <?php
      $idCategoria = 3;
      shuffle($imagenesHome);
      require_once "index_imagenes.part.php";
      ?>

      <!-- Third Category Pictures -->

      <nav class="text-center">
        <ul class="pagination">
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#" aria-label="suivant">
              <span aria-hidden="true">&raquo;</span>
            </a></li>
        </ul>
      </nav>

    </div>
    <!-- End of Navigation Table Content -->
  </div><!-- End of Index-body box -->

  <!-- Newsletter form -->
  <div class="index-form text-center">
    <h3>SUSCRIBE TO OUR NEWSLETTER </h3>
    <h5>Suscribe to receive our News and Gifts</h5>
    <form class="form-horizontal">
      <div class="form-group">
        <div class="col-xs-12 col-sm-6 col-sm-push-3 col-md-4 col-md-push-4">
          <input class="form-control" type="text" placeholder="Type here your email address">
          <a href="" class="btn btn-lg sr-button">SUBSCRIBE</a>
        </div>
      </div>
    </form>
  </div>
  <!-- End of Newsletter form -->

  <!-- Box within partners name and logo -->
  <?php
  $logosLista = Utils::extraeElementosAleatorios($asociadosHome, 3);
  require_once "indexlogos.view.part.php";
  ?>
  <!-- End of Box within partners name and logo -->

</div><!-- End of index box -->

<?php
//Se quitan en el 16.2.5
// require_once __DIR__ . "/fin.part.php";
