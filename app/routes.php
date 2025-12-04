<?php 
$router->get  ('', 'PageController@index');
$router->get ('about', 'PageController@about');

$router->get ('imagenes-galeria', 'ImagenGaleriaController@index', 'ROLE_USER');
$router->get ('imagenes-galeria/:id', 'ImagenGaleriaController@show', 'ROLE_USER');
$router->post('imagenes-galeria/nueva', 'ImagenGaleriaController@nueva', 'ROLE_USER');

$router->get ('asociados', 'AsociadoController@index');
$router->post('asociados/nuevo', 'AsociadoController@nueva', 'ROLE_ADMIN');


$router->get  ('blog', 'PageController@blog');
$router->get ('contact', 'PageController@contact');
$router->get  ('post', 'PageController@post');

$router->get ('galeria', 'GaleriaController@index');
$router->post('galeria/nueva', 'GaleriaController@nueva', 'ROLE_USER');
$router->get ('galeria/:id', 'GaleriaController@show');
$router->get ('galeria/editar/:id', 'GaleriaController@editar', 'ROLE_ADMIN');
$router->post('galeria/actualizar/:id', 'GaleriaController@actualizar', 'ROLE_ADMIN');
$router->get ('galeria/eliminar/:id', 'GaleriaController@eliminar', 'ROLE_ADMIN');

$router->get ('exposiciones', 'ExposicionController@index', 'ROLE_USER');
$router->post ('exposiciones/nueva', 'ExposicionController@nueva', 'ROLE_USER');

$router->get ('login', 'AuthController@login');
$router->post('check-login', 'AuthController@checkLogin');
$router->get ('logout', 'AuthController@logout');

$router->get ('registro', 'AuthController@registro');
$router->post('check-registro', 'AuthController@checkRegistro');

