<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';

// authentication
$route['api/login']   = 'API/AuthenticationController/login';
$route['api/logout']   = 'API/AuthenticationController/logout';

// add user
$route['api/user'] = 'API/UserController/register';
$route['api/user/(:num)']['PUT'] = 'API/UserController/update/$1';

// group
$route['api/group'] = 'API/GroupController/create';
$route['api/group/(:num)']['DELETE'] = 'API/GroupController/group/$1';
$route['api/group-search'] = 'API/GroupController/group_search';

// add member to group
$route['api/add-member']  = 'API/GroupController/add_memeber';
// send message
$route['api/send-message']  = 'API/GroupController/send_message';
// add like message
$route['api/add-like-message'] = 'API/GroupController/add_like_message';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
