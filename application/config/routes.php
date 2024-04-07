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
$route['logout'] = 'login/logout';
$route['change-password'] = 'login/change_password';
$route['check-password'] = 'login/check_current_pasword';
$route['update-password'] = 'login/update_password';
$route['set-default-usr-data'] = 'master/users/update_default_password';
$route['dashboard'] = "home/Dashboard/index";

$route['admin-dashboard'] = "home/dashboard/admin";
$route['home/doctor'] = "home/dashboard/doctors_dashboard";
$route['home/user'] = "home/dashboard/blank_dashboard";
$route['home/xray'] = "home/dashboard/xray_dashabord";
$route['home/ecg'] = "home/dashboard/ecg_dashboard";
$route['home/usg'] = "home/dashboard/usg_dashboard";
$route['home/lab'] = "home/dashboard/lab_dashboard";

$route['xray-info'] = "patient/xray";
$route['ecg-info'] = "patient/ecg";
$route['usg-info'] = "patient/usg";
$route['lab-info'] = "patient/lab";

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
$route['birth'] = "reports/Test/birth";
$route['kriyakalp'] = "reports/Test/kriyakalp";
$route['delivery'] = "reports/Test/delivery";
$route['doctors_duty'] = "reports/Test/doctors_duty";

$route['search-data'] = "auto/index";
$route['analyse-data'] = "auto/move";
$route['show-ref'] = "auto/show_reference_data";
$route['get_data'] = "auto/get_reference_data";
$route['save-data'] = "auto/save_patient_details";

// Purchase
$route['show-purchase-type'] = "pharmacy/purchase/purchase_items";
$route['add-purchase-type'] = "pharmacy/purchase/save_purchase_master_type";

$route['add-product'] = "pharmacy/purchase/add_product";
$route['save-product'] = "pharmacy/purchase/save_product";
$route['product-list'] = "pharmacy/purchase/product_list";
$route['export-product-list'] = "pharmacy/purchase/export_product_list";
$route['fetch_patient_data'] = "pharmacy/sales/fetch_patient_data";

$route['purchase-return'] = "pharmacy/purchase/purchase_return";
$route['add-purchase'] = "pharmacy/purchase/add_purchase";

$route['duty-doctors'] = "master/Doctors";

$route['opd-patients-list'] = "patient/Treatment/display_all_patients";

$route['stock-entry'] = "pharmacy/Stock/stock_entry";
$route['save-stock'] = "pharmacy/Stock/save_stock";
$route['stock-view'] = "pharmacy/Stock/stock_list";

//$route['print-opd-bill/(:num)/(:num)/(:any)'] = 'patient/Treatment/print_bill/$1/$2/$3';
$route['print-opd-bill'] = 'patient/Treatment/print_bill';
$route['print-opd-prescription'] = 'patient/Treatment/print_prescription';

$route['patient-list'] = 'patient/patient_list';
$route['procedure-list'] = 'master/panchakarma/list_procedures';
$route['sub-procedure-save'] = 'master/panchakarma/update_sub_procedure';
$route['export-duty-doctors-xls'] = 'master/doctors/export_to_excel';

$route['search-patient'] = 'patient/search_patient';
$route['add-opd-treatment/(:num)/(:num)'] = 'patient/treatment/add_treatment/$1/$2';

$route['add-purchase'] = 'pharmacy/purchase/add_purchase';
$route['lab-list'] = 'master/laboratory';

$route['delete-data'] = 'home/Settings/remove_data';
$route['delete-patients'] = 'home/Settings/delete_patients';

/* pdf links */
$route['export-lab-report'] = 'reports/Test/export_lab_report';
$route['nursing-report'] = 'reports/nursing';

$route['physiotherapy'] = 'master/Physiotherapy';
$route['add-physiotherapy'] = 'master/Physiotherapy/save';
$route['delete-physiotherapy'] = 'master/Physiotherapy/delete';

$route['physiotherapy-report'] = 'reports/Test/physiotherapy';

$route['other-procedures'] = 'master/Otherprocedures';
$route['add-other-procedures'] = 'master/Otherprocedures/save';
$route['delete-other-procedures'] = 'master/Otherprocedures/delete';

$route['otherprocedures-report'] = 'reports/Test/otherprocedures';

//sales
$route['update-sales'] = 'pharmacy/sales/update_stocks';
$route['add-sales'] = 'pharmacy/sales';

$route['full-year-report'] = 'localreports/localreports/opd';
$route['panchakarma-complete-report'] = 'reports/Test/export_panchakarma_complete_report';
$route['full-opd-pharmacy-download'] = 'localreports/export_opd_sales';

$route['other-procedure-statistics'] = 'reports/statistics/other_procedures';
$route['physiotherapy-statistics'] = 'reports/statistics/physiotherapy';

$route['store-kriyakalpa'] = 'patient/treatment/save_kriyakalpa';
$route['store-other-procedures'] = 'patient/treatment/save_other_procedures';
$route['store-physiotherapy'] = 'patient/treatment/save_physiotherapy';
$route['store-xray'] = 'patient/treatment/save_xray';
$route['doctors-update'] = 'Updatedata/doctors';
$route['store-swarnaprashana'] = 'patient/treatment/store_swarnaprashana';

$route['fetch-patient-repo'] = 'reports/patient_reports/fetch_patient_repo';

$route['remove_data'] = 'common_methods/delete_records';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
