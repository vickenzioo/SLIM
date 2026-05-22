<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// Mengarahkan semua akses 'auth' ke module 'login/auth'
$route['auth'] = 'login/auth';
$route['auth/(:any)'] = 'login/auth/$1';