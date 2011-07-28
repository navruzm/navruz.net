<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There is one reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/



$route['user/activate'] = "user/activate";
$route['user/change_email'] = "user/change_email";
$route['user/change_password'] = "user/change_password";
$route['user/delete'] = "user/delete";
$route['user/forgot_password'] = "user/forgot_password";
$route['user/index'] = "user/index";
$route['user/login'] = "user/login";
$route['user/logout'] = "user/logout";
$route['user/reset_email'] = "user/reset_email";
$route['user/register'] = "user/register";
$route['user/reset_password'] = "user/reset_password";
$route['user/reset_email'] = "user/reset_email";
$route['user/send_again'] = "user/send_again";
$route['user/change_profile'] = "user/change_profile";
$route['user/avatar'] = "user/avatar";
$route['user/avatar_upload'] = "user/avatar_upload";
$route['user/avatar_crop'] = "user/avatar_crop";
$route['user/terms'] = "user/terms";

$route['user/([A-Za-z0-9-_]+)'] = "user/view/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */