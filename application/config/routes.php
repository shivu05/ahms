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
  |	https://codeigniter.com/user_guide/general/routing.html
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
$route['dashboard'] = "home/Dashboard/index";

$route['admin-dashboard'] = "home/dashboard/admin";
$route['home/doctor'] = "home/dashboard/doctors_dashboard";
$route['home/user'] = "home/dashboard/blank_dashboard";
$route['home/xray'] = "home/dashboard/xray_dashabord";
$route['home/ecg'] = "home/dashboard/ecg_dashboard";
$route['home/usg'] = "home/dashboard/usg_dashboard";

$route['xray-info'] = "patient/xray";
$route['ecg-info'] = "patient/ecg";
$route['usg-info'] = "patient/usg";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['monthwise-ipd-report'] = "reports/Ipd/monthly_ipd_report";
$route['monthwise-opd-ipd-report'] = "reports/Ipd/monthly_io_report";
/* Test reports */
$route['x-ray'] = "reports/Test/xray";
$route['ecg'] = "reports/Test/ecg";
$route['usg'] = "reports/Test/usg";
$route['ksharasutra'] = "reports/Test/ksharasutra";
$route['surgery'] = "reports/Test/surgery";
$route['surgery-count'] = "reports/Test/surgery_count";
$route['panchakarma'] = "reports/Test/panchakarma";
$route['panchakarma-procedure-stats'] = "reports/Test/panchakarma_proc_count";
$route['lab'] = "reports/Test/lab";
$route['lab-count'] = "reports/Test/lab_count";
$route['department-list'] = "master/department";
$route['users-list'] = "master/users";
$route['add-user'] = "master/users/add";

$route['search-data'] = "auto/index";

// Purchase
$route['show-purchase-type'] = "pharmacy/purchase/purchase_items";
$route['add-purchase-type'] = "pharmacy/purchase/save_purchase_master_type";

$route['add-product'] = "pharmacy/purchase/add_product";
$route['save-product'] = "pharmacy/purchase/save_product";
$route['product-list'] = "pharmacy/purchase/product_list";
$route['export-product-list'] = "pharmacy/purchase/export_product_list";

$route['purchase-return'] = "pharmacy/purchase/purchase_return";

$route['duty-doctors'] = "master/Doctors";


