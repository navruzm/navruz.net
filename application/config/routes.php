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
|	example.com/class/method/id/
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = "home/error_404";

$route['page-(:num)'] = "home/index/$1";

$route['admin'] = "admin/admin";
$route['rss'] = "rss/index";
$route['s/(:any)'] = "page/index/$1";
$route['file/(:any)'] = "file/index/$1";

$route['admin/database/(:any)'] = "admin/database/$1";
$route['admin/manager/(:any)'] = "admin/manager/$1";
$route['admin/uploader/(:any)'] = "admin/uploader/$1";
$route['home'] = "home/index";
$route['rss'] = "rss/index";
$route['contact'] = "contact/index";
$route['search'] = "search/index";
$route['credits'] = "site/credits";

$route['user'] = "user/index";
$route['category/([A-Za-z0-9-_]+)-page-([0-9]+)'] = "category/index/$1/$2";
$route['category/([A-Za-z0-9-_]+)'] = "category/index/$1";
$route['tag/([A-Za-z0-9-_]+)-page-([0-9]+)'] = "tag/index/$1/$2";
$route['tag/([A-Za-z0-9-_]+)'] = "tag/index/$1";

$route['page/([A-Za-z0-9-_]+)-s([0-9]+)'] = "page/index/$1/$2";
$route['page/([A-Za-z0-9-_]+)'] = "page/index/$1";
$route['pages-([0-9]+)'] = "page/all_pages/$1";
$route['pages'] = "page/all_pages";


$route['admin/([a-z]+)/(.*)'] = "$1/admin/$2";
$route['post/upload/index'] = "post/upload/index";

$route['archive'] = "post/archive";

$route['([A-Za-z0-9-_]+)'] = "post/index/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
