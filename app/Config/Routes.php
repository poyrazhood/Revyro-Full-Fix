<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('main/Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
//['filter' => 'adminFilter']
$routes->group('admin', ['filter' => 'adminLogin'], function ($routes) {
    $routes->add('login', 'admin/Main::login');
});
$routes->group('admin', ['filter' => 'adminControl'], function ($routes) {
    $routes->add('', 'admin/Main::index');
    $routes->post('notkaydet', 'admin/Main::ajax');
    $routes->add('adminApi', 'admin/Main::adminapi');
    $routes->add('clients', 'admin/Clients::index');
    $routes->add('clients/(:any)', 'admin/Clients::index');
    $routes->add('client-detail/(:num)', 'admin/Clients::detail');
    $routes->add('orders', 'admin/Orders::index');
    $routes->add('orders/(:any)', 'admin/Orders::index');
    $routes->add('order-detail/(:num)', 'admin/Orders::detail');
    $routes->add('services', 'admin/Services::index');
    $routes->add('services-ajax', 'admin/Services::services_ajax');
    $routes->add('gizle-ajax', 'admin/Services::gizle_ajax');
    $routes->add('services/(:any)', 'admin/Services::index');
    $routes->add('services-detail/(:num)', 'admin/Services::detail');
    $routes->add('service-modal', 'admin/Services::service_modal');
    $routes->add('new-service', 'admin/Services::new');
    $routes->add('payments', 'admin/Payments::index');
    $routes->add('payments/(:any)', 'admin/Payments::index');
    $routes->add('tickets/read/(:num)', 'admin/Tickets::read');
    $routes->add('tickets/ajax', 'admin/Tickets::ajax');
    $routes->add('tickets/saglayici_ajax', 'admin/Tickets::saglayici_ajax');
    $routes->add('tickets', 'admin/Tickets::index');
    $routes->add('tickets/ready', 'admin/Tickets::ready');
    $routes->add('tickets/getReady', 'admin/Tickets::getReady');
    $routes->add('tickets/deleteReady', 'admin/Tickets::deleteReady');
    $routes->add('tickets/(:any)', 'admin/Tickets::index');
    $routes->add('child-panels/(:any)', 'admin/Child_Panels::index');
    $routes->add('child-panels/', 'admin/Child_Panels::index');
    $routes->add('subscriptions/', 'admin/Subscriptions::index');
    $routes->add('subscriptions/(:any)', 'admin/Subscriptions::index');
    $routes->add('dripfeeds', 'admin/Dripfeeds::index');
    $routes->add('dripfeeds/(:any)', 'admin/Dripfeeds::index');

    //Log
    $routes->add('logs', 'admin/Logs::index');
    $routes->add('logs/(:any)', 'admin/Logs::index');
    $routes->add('provider_logs', 'admin/Logs::provider');
    $routes->add('provider_logs/(:any)', 'admin/Logs::provider');
    $routes->add('guard_logs', 'admin/Logs::guard');
    $routes->add('guard_logs/(:any)', 'admin/Logs::guard');
    $routes->add('tasks', 'admin/Logs::tasks');
    $routes->add('tasks/(:any)', 'admin/Logs::tasks');
    $routes->add('mail-sablon', 'admin/Mail::sablon');
    $routes->add('popup', 'admin/Popup::popup');
    $routes->add('update-log', 'admin/UpdateNote::not');

    $routes->add('reports/(:any)', 'admin/Reports::index');
    $routes->add('reports', 'admin/Reports::index');
    $routes->add('appearance', 'admin/Appearance::index');
    $routes->add('appearance/(:any)', 'admin/Appearance::index');
    $routes->add('settings', 'admin/Settings::index');
    $routes->add('settings/(:any)', 'admin/Settings::index');
    $routes->add('logs', 'admin/Logs');
    $routes->post('ajax_data', 'admin/Ajax_Data::index');
    $routes->add('provider_logs', 'admin/Logs::provider');
    $routes->add('guard_logs', 'admin/Logs::guard');

    //datatable
    $routes->add('getallOrders', 'admin/Datatable::getallOrders');

    $routes->add('getallPayments', 'admin/Datatable::getallPayments');
    $routes->add('getallPaymentsBank', 'admin/Datatable::getallPaymentsBank');
    $routes->add('getallLogs', 'admin/Datatable::getallLogs');
    $routes->add('getallGuardLogs', 'admin/Datatable::getallGuardLogs');
    $routes->add('getallServiceLogs', 'admin/Datatable::getallServiceLogs');
    $routes->add('getallOrdersSubs', 'admin/Datatable::getallOrdersSubs');
    $routes->add('getallClients', 'admin/Datatable::getallClients');
    $routes->add('getAlltasks', 'admin/Datatable::getAlltasks');
    $routes->add('getAllTickets', 'admin/Datatable::getAllTickets');

    //version
    $routes->add('version_check', 'admin/Main::version_check');
    $routes->add('update_version', 'admin/Main::update');
    $routes->add('create_db_update', 'admin/Main::create_db_update');
    $routes->add('popup_db_create', 'admin/Main::popup_db_create');
    $routes->add('version_update_settings', 'admin/Main::version_update_settings');
    $routes->add('mysql_backup', 'admin/Main::mysql_backup');


    $routes->group('admin_ajax', ['filter' => 'adminFilter'], function ($routes) {
        $routes->post('(:any)', 'admin/Ajax');
    });
});

//Giriş Yaptıktan sonra
$routes->group('/', ['filter' => 'loginControl'], function ($routes) {
    //Siparişlerim
    $routes->add('orders', 'main/Home::index');
    $routes->add('order/(:any)', 'main/Home::index');
    $routes->add('orders/(:any)', 'main/Home::index');
    //Bakiye
    $routes->add('addfunds', 'main/Home::index');

    //Account
    $routes->add('account', 'main/Home::index');
    $routes->add('account/(:any)', 'main/Home::index');

    //Ticket
    $routes->add('tickets', 'main/Home::index');
    $routes->add('tickets/(:num)', 'main/Home::index');

    //Ekstra
    $routes->add('affiliates', 'main/Home::index');
    $routes->add('child-panels', 'main/Home::index');
    $routes->add('subscriptions', 'main/Home::index');
    $routes->add('subscriptions/(:any)', 'main/Home::index');

    $routes->get('dripfeeds', 'main/Home::index');
    $routes->get('dripfeeds/(:any)', 'main/Home::index');
    //onay
    $routes->add('verify/(:any)', 'main/Home::index');

    // Çıkış
    $routes->add('logout', 'main/Home::index');
});

//Giriş Yapmadan Önce
$routes->group('/', ['filter' => 'loginFilter'], function ($routes) {
    //Kayıt Ol
    $routes->add('signup', 'main/Home::index');
    $routes->add('resetpassword', 'main/Home::index');
    $routes->add('resetpassword/(:any)', 'main/Home::index');

    $routes->add('ref', 'main/Home::index');
    $routes->add('ref/(:any)', 'main/Home::index');
    //Hızlı Sipariş
    $routes->add('fast', 'main/Home::index');
});


$routes->add('api_balance', 'admin/Settings::api_balance');

$routes->add('/', 'main/Home::index');
$routes->add('signup', 'main/Home::index');
$routes->add('services', 'main/Home::index');
$routes->add('blog', 'main/Home::index');
$routes->add('blog/(:any)', 'main/Home::index');
$routes->add('select-theme/(:any)', 'main/Home::index');
$routes->add('terms', 'main/Home::index');
$routes->add('contact', 'main/Home::index');
$routes->add('faq', 'main/Home::index');

$routes->post('auth', 'main/Home::index');
$routes->post('ajax_data', 'main/Ajax_Data::index');

$routes->add('payment/(:any)', 'main/Home::index');

// Api
$routes->add('api/v2', 'main/Api::index');
$routes->add('adminapi/v1', 'admin/Admin_Api::index');
$routes->add('api', 'main/Home::index');

//popup
$routes->post('popup', 'main/Popup::ajax');

//cron status
$routes->post('cronSetStatus', 'main/Cron_Status::index');
$routes->post('cronStatus', 'main/Cron_Status::getset');
$routes->get('cronApiDetail', 'Cron::api_detail');
$routes->get('api_orders', 'Cron::api_orders');
$routes->get('site_orders', 'Cron::site_orders');
$routes->get('dripfeed', 'Cron::dripfeed');
$routes->get('providers', 'Cron::providers');
$routes->get('send_tasks', 'Cron::send_tasks');
$routes->get('run', 'Cron::run');
$routes->get('balance_alert', 'Cron::balance_alert');
$routes->get('sync', 'Cron::sync');
$routes->get('kurcek', 'Cron::kurcek');
$routes->get('cift_servis', 'Cron::cift_servis');
$routes->get('proxy_control', 'Cron::proxy_control');
$routes->get('create_db', 'Cron::create_db_update');

$routes->get("admin-server-update", 'admin/Main::update_admin');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
