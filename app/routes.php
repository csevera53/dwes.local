<?php 
$router->get  ('', 'PageController@index');
$router->get ('about', 'PageController@about');
$router->get ('imagenes-galeria', 'ImagenGaleriaController@index', 'ROLE_USER');
$router->get ('imagenes-galeria/:id', 'ImagenGaleriaController@show', 'ROLE_USER');
$router->post('imagenes-galeria/nueva', 'ImagenGaleriaController@nueva', 'ROLE_ADMIN');
$router->get ('asociados', 'AsociadoController@index');
$router->get  ('blog', 'PageController@blog');
$router->get ('contact', 'PageController@contact');
$router->get ('galeria', 'GaleriaController@index');
$router->get  ('post', 'PageController@post');

$router->post('galeria/nueva', 'GaleriaController@nueva', 'ROLE_ADMIN');
$router->post('asociados/nuevo', 'AsociadoController@nueva', 'ROLE_ADMIN');

$router->get ('galeria/:id', 'GaleriaController@show');

$router->get ('login', 'AuthController@login');
$router->post('check-login', 'AuthController@checkLogin');
$router->get ('logout', 'AuthController@logout');

$router->get ('registro', 'AuthController@registro');
$router->post('check-registro', 'AuthController@checkRegistro');

