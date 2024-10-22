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
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'login';
$route['register'] = 'login/register';
$route['forgot_password'] = 'login/forgot_password';
$route['login_post'] = 'login/login_post';
$route['register_post'] = 'login/register_post';
$route['logout'] = 'login/logout';
$route['dashboard'] = 'dashboard';
$route['edit_profile'] = 'dashboard/edit';
$route['settings'] = 'users/settings';

$route['contacts'] = 'clients';
$route['contacts/add'] = 'clients/edit';
$route['contacts/add_multiple'] = 'clients/add_multiple';
$route['contacts/edit/(:any)'] = 'Clients/edit/$1';
$route['contacts/action/delete/(:any)'] = 'Clients/action/delete/$1';


$route['(:any)/add'] = '$1/edit';
$route['(:any)/add_custom'] = '$1/edit_custom';
$route['(:any)/(:any)'] = "$1/$2";

$route['(:any)/delete/(:any)'] = '$1/action/delete/$2';
$route['(:any)/activate/(:any)'] = '$1/action/activate/$2';
$route['(:any)/block/(:any)'] = '$1/action/block/$2';
$route['(:any)/(:any)/(:any)'] = "$1/$2/$3";

$route['text-logs'] = 'ChatLogs/index';
$route['api-logs'] = 'ChatLogs/api_log';
$route['live-chat'] = 'ChatLogs/chat_live';
$route['crm-leads'] = 'indiamart_leads/index';
$route['crm-message-logs'] = 'indiamart_leads/message_logs';
$route['api-docs'] = 'api/docs';

$route['campaigns'] = 'Campaigns';
$route['new-campaign'] = 'Campaigns/new_campaign';





