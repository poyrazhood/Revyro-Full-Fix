<?php

namespace App\Controllers\admin;

use App\Models\cift_servis;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Datatable extends Ana_Controller
{
    public function __construct()
    {
        helper('function_helper');
        require_once APPPATH . 'ThirdParty/ssp.php';
        $this->db = db_connect();
        $this->currentdb = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db" => $this->db->database,
        );
    }

    public function getallOrders()
    {
        $table = "orders";
        $joinQuery = "FROM `orders` AS `c` LEFT JOIN `clients` AS `cn` ON (`cn`.`client_id` = `c`.`client_id`)";
        $where = "";
        if ($this->request->getGet('services')) {
            $where .= "service_id = '" . $this->request->getGet('services') . "' and";
        }
        if (!$this->request->getGet('mode')) {
        $where .=" dripfeed = 1 and subscriptions_type = 1";
        }
        if ($this->request->getGet('mode')) {
            $mode = $this->request->getGet('mode');
            if ($mode == "manuel") {
            $where .=" dripfeed = 1 and subscriptions_type = 1 and ";
                $where .= "api_orderid = 0 and order_detail is null";
                
            } elseif ($mode == "pending") {
                $where .= "order_status = 'pending' and order_error = '-'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "inprogress") {
                $where .= "order_status = 'inprogress' and order_error = '-'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "processing") {
                $where .= "order_status = 'processing' and order_error = '-'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "completed") {
                $where .= "order_status = 'completed'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "partial") {
                $where .= "order_status = 'partial' and order_error = '-'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "canceled") {
                $where .= "order_status = 'canceled'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            } elseif ($mode == "fail") {
                $where .= "order_error != '-'";
                $where .="and dripfeed = 1 and subscriptions_type = 1";
            }
        }
        $primary = "order_id";
        $colums = array(
            array(
                'db' => 'c.order_extras',
                'dt' => 0
            ), array(
                'db' => 'c.order_id',
                'dt' => 0
            ),
            array(
                'db' => 'c.api_orderid',
                'dt' => 0
            ),
            array(
                'db' => 'c.client_id',
                'dt' => 0
            ),
            array(
                'db' => 'c.order_where',
                'dt' => 0
            ),
            array(
                'db' => 'c.order_id',
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $d = $row['order_id'];
                    $api_orderid = $row['api_orderid'];
                    if ($row['order_where'] == "api"):
                        $api = ' <span class="label label-api">API</span>';
                    else:
                        $api = "";
                    endif;
                    $status = "xx";
                    if ($status == "canceled"): $cancel = "disabled";
                    else: $cancel = 'class="form-check-input me-2 selectOrder"'; endif;
                    return '<input type="checkbox" ' . $cancel . ' name="order[' . $d . ']"
                                                value="1">#' . $d . $api . ' 
                                        <br>
                                        <small class="ms-4">#' . $api_orderid . '</small>';
                }
            ),
            array(
                'db' => 'cn.username',
                "dt" => 1,
                "formatter" => function ($d, $row) {

                    $link = base_url("/admin/client-detail/" . $row['client_id']);
                    return '<a href="' . $link . '"
                                           target="_blank"
                                           class="text-glycon">
                                            <i class="fas fa-external-link-alt">' . $row['username'] . '</i></a>';
                }
            ),
            array(
                'db' => 'c.order_url',
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    $d = $row['order_url'];
                    if (strlen($d) > 30) {
                        $cikti = '<div class="btn-group">';
                        $cikti .= '<button type="button" class="btn btn-primary dropdown-toggle py-1" data-bs-toggle="dropdown" aria-expanded="false">
								Bağlantı
							  </button><ul id="baglanti_adres" data-url="' . $d . '" class="dropdown-menu px-2">
								  ' . $d . '
							  </ul>';

                        $cikti .= "</div>";
                        return $cikti;
                    } else {
                        $cikti = '<div class="btn-group">';
                        $cikti .= $d . "</div>";

                        return $cikti;
                    }
                }
            ),
            array(
                'db' => 'c.service_id',
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    $d = $row['service_id'];
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $d)->get()->getResultArray();
                    if (isset($servis_bul[0])):
                        $service_name = isset($servis_bul[0]['service_name']) ? $servis_bul[0]['service_name'] : "Servis Silinmiş";
                        $birlestirme = $servis_bul[0]['birlestirme'] ? "<small style='color:#c0392b'>Birleştirilmiş Servis | </small>" : "";
                        $sirali = $servis_bul[0]['sirali_islem'] ? "<small style='color:#c0392b'>Sıralı İşlem</small>" : "";
                    else:
                        $service_name = "Servis Silinmiş";
                        $birlestirme = "";
                        $sirali = "";
                    endif;
                    $cikti = "";
                    if (empty($row["order_extras"]) || $row["order_extras"] == "[]") {
                    } else {

                        $cikti .= '<small><button type="button" class="btn btn-primary dropdown-toggle py-1" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="order_comment" data-id="' . $row["order_id"] . '">
								Ekstra
							  </button></small>';
                    }
                    return '<span class="label-id">' . $d . '</span> ' . $service_name . $birlestirme . $sirali . "  " . $cikti;
                }
            ),
            array(
                'db' => 'c.order_start',
                "dt" => 4,
                'field' => 'order_start'
            ),
            array(
                'db' => 'c.order_remains',
                "dt" => 5,

                'field' => 'order_remains'
            ),
            array(
                'db' => 'c.order_quantity',
                "dt" => 5,
                "formatter" => function ($d, $row) {
                    $d = $row['order_quantity'];
                    return $d . "<br><small>" . $row['order_remains'] . "</small>";
                }
            ),
            array(
                'db' => 'c.api_charge',
                "dt" => 6,
                'field' => 'api_charge'
            ),
            array(
                'db' => 'c.order_charge',
                "dt" => 6,
                "formatter" => function ($d, $row) {
                    $d = $row['order_charge'];
                    return $d . "<br><small>" . $row['api_charge'] . "</small>";
                }
            ),
            array(
                'db' => 'c.order_error',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_detail',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_error2',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_detail2',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_status',
                "dt" => 7,
                "formatter" => function ($d, $row) {
                    $d = $row['order_status'];
                    $cikti = "";
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $row['service_id'])->get()->getResultArray();
                    $durum = orderStatu($d, $row["order_error"], $row["order_detail"]);
                    if ($durum == "completed") {
                        $cikti = '<div class="glycon-badge badge-success">Tamamlandı</div>';
                    } elseif ($durum == "Tamamlandı") {
                        $cikti = '<div class="glycon-badge badge-success">Tamamlandı</div>';

                    } elseif ($row["order_error"] != "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0) {
                        $cikti = '<div class="glycon-badge badge-danger">Fail</div>';

                    } elseif ($durum == "pending") {
                        $cikti = '<div class="glycon-badge badge-warning">Sipariş Alındı</div>';

                    } elseif ($durum == "processing") {
                        $cikti = '<div class="glycon-badge badge-secondary">Gönderim Sırasında</div>';

                    } elseif ($durum == "inprogress") {
                        $cikti = ' <div class="glycon-badge badge-info">Yükleniyor</div>';

                    } elseif ($durum == "Kısmi Tamamlandı") {
                        $cikti = ' <div class="glycon-badge badge-secondary">Kısmi Tamamlandı</div>';

                    } elseif ($durum == "canceled") {
                        $cikti = '<div class="glycon-badge badge-warning">İptal Edildi</div>';

                    } elseif ($durum == "partial") {
                        $cikti = '<div class="glycon-badge badge-light" style="background:#8e44ad;color:white;">Kısmi Tamamlandı</div>';

                    } elseif ($durum == "İptal") {
                        $cikti = '<div class="glycon-badge badge-dark">İptal</div>';

                    } else {
                        $cikti = '<div class="glycon-badge badge-info">' . $durum . '</div>';

                    }
                    if ($servis_bul[0]['birlestirme']) {

                        $cift = new cift_servis();
                        $tum = $cift->where('order_id', $row["order_id"])->get()->getResultArray();
                        if (isset($tum[0])) {
                            $tum = $tum[0];

                            $durum = orderStatu($tum['status'], $row["order_error2"], $row["order_detail2"]);
                            if ($durum == "completed") {
                                $cikti .= '<div class="glycon-badge badge-success">Tamamlandı</div>';
                            } elseif ($row["order_error2"] != "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0) {
                                $cikti .= '<div class="glycon-badge badge-danger">Fail</div>';

                            } elseif ($durum == "pending") {
                                $cikti .= '<div class="glycon-badge badge-warning">Sipariş Alındı</div>';

                            } elseif ($durum == "processing") {
                                $cikti .= '<div class="glycon-badge badge-secondary">Gönderim Sırasında</div>';

                            } elseif ($durum == "inprogress") {
                                $cikti .= ' <div class="glycon-badge badge-info">Yükleniyor</div>';

                            } elseif ($durum == "canceled") {
                                $cikti .= '<div class="glycon-badge badge-danger">İptal Edildi</div>';

                            } elseif ($durum == "partial") {
                                $cikti .= '<div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>';

                            } elseif ($durum == "İptal") {
                                $cikti .= '<div class="glycon-badge badge-danger">İptal</div>';

                            } else {
                                $cikti .= '<div class="glycon-badge badge-info">' . $durum . '</div>';

                            }
                        }
                    }
                    return $cikti;
                }
            ),

            array(
                'db' => 'c.order_create',
                "dt" => 8,
                'field' => 'order_create'
            ),
            array(
                'db' => 'c.order_id',
                "dt" => 9,
                "formatter" => function ($d, $row) {
                    $d = $row['order_id'];
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $row['service_id'])->get()->getResultArray();
                    $return = '<div class="dropdown text-center">
                                            <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                İşlemler
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                       href="' . base_url("admin/order-detail/" . $row['order_id']) . '">Servis
                                                        Detayı</a></li>
                                                <hr>';

                    if (($row["order_error"] != "-" || ($row['order_error2'] !="-"&& $servis_bul[0]['birlestirme'])) && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0):

                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_errors"
                                                           data-id="' . $row["order_id"] . '">Fail
                                                            Detayı</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="' . base_url("admin/orders/order_resend/" . $row["order_id"]) . '">Yeniden
                                                            Gönder</a></li> <hr>';
                    endif;

                    if ($row["order_error"] == "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_details"
                                                           data-id="' . $row["order_id"] . '">Sipariş
                                                            Detayı</a>
                                                    </li>';
                    endif;
                    if (isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] == 0 || $row["order_error"] != "-"):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_orderurl"
                                                           data-id="' . $row["order_id"] . '">Sipariş
                                                            Linkini
                                                            Düzenle</a></li>';
                    endif;
                    if (isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] == 0):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_startcount"
                                                           data-id="' . $row["order_id"] . '">Başlangıç
                                                            Miktarını Düzenle</a></li>';
                    endif;
                    if ($row["order_status"] != "partial"):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_partial"
                                                           data-id="' . $row["order_id"] . '">Kalan Miktarı
                                                            Düzenle</a></li><hr>';
                    endif;
                    $return .= '
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#" data-bs-toggle="modal"
                                                       data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_cancel/" . $row["order_id"]) . '">İptal
                                                        ve İade Et</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_complete/" . $row["order_id"]) . '">Tamamlandı</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_inprogress/" . $row["order_id"]) . '">Yükleniyor</a>
                                                </li>
                                            </ul>
                                        </div>';
                    return $return;
                }
            ),
        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, $where, 'ORDER BY order_id DESC')
        );
    }

    public function getallOrdersSubs()
    {
        $subs = $this->request->getGet('subs');
        $table = "orders";
        $joinQuery = "FROM `orders` AS `c` LEFT JOIN `clients` AS `cn` ON (`cn`.`client_id` = `c`.`client_id`)";

        $primary = "order_id";
        $colums = array(
            array(
                'db' => 'c.order_extras',
                'dt' => 0
            ), array(
                'db' => 'c.order_id',
                'dt' => 0
            ),
            array(
                'db' => 'c.api_orderid',
                'dt' => 0
            ),
            array(
                'db' => 'c.client_id',
                'dt' => 0
            ),
            array(
                'db' => 'c.order_where',
                'dt' => 0
            ),
            array(
                'db' => 'c.order_id',
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $d = $row['order_id'];
                    $api_orderid = $row['api_orderid'];
                    if ($row['order_where'] == "api"):
                        $api = ' <span class="label label-api">API</span>';
                    else:
                        $api = "";
                    endif;
                    $status = "xx";
                    if ($status == "canceled"): $cancel = "disabled";
                    else: $cancel = 'class="form-check-input me-2 selectOrder"'; endif;
                    return '<input type="checkbox" ' . $cancel . ' name="order[' . $d . ']"
                                                value="1">#' . $d . $api . ' 
                                        <br>
                                        <small class="ms-4">#' . $api_orderid . '</small>';
                }
            ),
            array(
                'db' => 'cn.username',
                "dt" => 1,
                "formatter" => function ($d, $row) {

                    $link = base_url("/admin/client-detail/" . $row['client_id']);
                    return '<a href="' . $link . '"
                                           target="_blank"
                                           class="text-glycon">
                                            <i class="fas fa-external-link-alt">' . $row['username'] . '</i></a>';
                }
            ),
            array(
                'db' => 'c.order_url',
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    $d = $row['order_url'];
                    if (strlen($d) > 30) {
                        $cikti = '<div class="btn-group">';
                        $cikti .= '<button type="button" class="btn btn-primary dropdown-toggle py-1" data-bs-toggle="dropdown" aria-expanded="false">
								Bağlantı
							  </button><ul id="baglanti_adres" data-url="' . $d . '" class="dropdown-menu px-2">
								  ' . $d . '
							  </ul>';

                        $cikti .= "</div>";
                        return $cikti;
                    } else {
                        $cikti = '<div class="btn-group">';
                        $cikti .= $d . "</div>";

                        return $cikti;
                    }
                }
            ),
            array(
                'db' => 'c.service_id',
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    $d = $row['service_id'];
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $d)->get()->getResultArray();
                    if (isset($servis_bul[0])):
                        $service_name = isset($servis_bul[0]['service_name']) ? $servis_bul[0]['service_name'] : "Servis Silinmiş";
                        $birlestirme = $servis_bul[0]['birlestirme'] ? "<small style='color:#c0392b'>Birleştirilmiş Servis | </small>" : "";
                        $sirali = $servis_bul[0]['sirali_islem'] ? "<small style='color:#c0392b'>Sıralı İşlem</small>" : "";
                    else:
                        $service_name = "Servis Silinmiş";
                        $birlestirme = "";
                        $sirali = "";
                    endif;
                    $cikti = "";
                    if (empty($row["order_extras"]) || $row["order_extras"] == "[]") {
                    } else {

                        $cikti .= '<small><button type="button" class="btn btn-primary dropdown-toggle py-1" style="font-size:12px" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="order_comment" data-id="' . $row["order_id"] . '">
								Yorumlar
							  </button></small>';
                    }
                    return '<span class="label-id">' . $d . '</span> ' . $service_name . $birlestirme . $sirali . "  " . $cikti;
                }
            ),
            array(
                'db' => 'c.order_start',
                "dt" => 4,
                'field' => 'order_start'
            ),
            array(
                'db' => 'c.order_remains',
                "dt" => 5,

                'field' => 'order_remains'
            ),
            array(
                'db' => 'c.order_quantity',
                "dt" => 5,
                "formatter" => function ($d, $row) {
                    $d = $row['order_quantity'];
                    return $d . "<br><small>" . $row['order_remains'] . "</small>";
                }
            ),
            array(
                'db' => 'c.api_charge',
                "dt" => 6,
                'field' => 'api_charge'
            ),
            array(
                'db' => 'c.order_charge',
                "dt" => 6,
                "formatter" => function ($d, $row) {
                    $d = $row['order_charge'];
                    return $d . "<br><small>" . $row['api_charge'] . "</small>";
                }
            ),
            array(
                'db' => 'c.order_error',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_detail',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_error2',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_detail2',
                "dt" => 7,
            ),
            array(
                'db' => 'c.order_status',
                "dt" => 7,
                "formatter" => function ($d, $row) {
                    $d = $row['order_status'];
                    $cikti = "";
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $row['service_id'])->get()->getResultArray();
                    $durum = orderStatu($d, $row["order_error"], $row["order_detail"]);
                    if ($durum == "completed") {
                        $cikti = '<div class="glycon-badge badge-success">Tamamlandı</div>';
                    } elseif ($row["order_error"] != "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0) {
                        $cikti = '<div class="glycon-badge badge-danger">Fail</div>';

                    } elseif ($durum == "pending") {
                        $cikti = '<div class="glycon-badge badge-warning">Sipariş Alındı</div>';

                    } elseif ($durum == "inprogress") {
                        $cikti = '<div class="glycon-badge badge-info">Yükleniyor</div>';

                    } elseif ($durum == "processing") {
                        $cikti = ' <div class="glycon-badge badge-secondary">Gönderim Sırasında</div>';

                    } elseif ($durum == "canceled") {
                        $cikti = '<div class="glycon-badge badge-danger">İptal Edildi</div>';

                    } elseif ($durum == "partial") {
                        $cikti = '<div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>';

                    } elseif ($durum == "İptal") {
                        $cikti = '<div class="glycon-badge badge-danger">İptal</div>';

                    } else {
                        $cikti = '<div class="glycon-badge badge-info">' . $durum . '</div>';

                    }
                    if ($servis_bul[0]['birlestirme'] && $servis_bul[0]['sirali_islem']) {

                        $cift = new cift_servis();
                        $tum = $cift->where('order_id', $row["order_id"])->get()->getResultArray();
                        if (isset($tum[0])) {
                            $tum = $tum[0];

                            $durum = orderStatu($tum['status'], $row["order_error2"], $row["order_detail2"]);
                            if ($durum == "completed") {
                                $cikti .= '<div class="glycon-badge badge-success">Tamamlandı</div>';
                            } elseif ($row["order_error2"] != "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0) {
                                $cikti .= '<div class="glycon-badge badge-danger">Fail</div>';

                            } elseif ($durum == "pending") {
                                $cikti .= '<div class="glycon-badge badge-warning">Sipariş Alındı</div>';

                            } elseif ($durum == "processing") {
                                $cikti .= '<div class="glycon-badge badge-info">Yükleniyor</div>';

                            } elseif ($durum == "inprogress") {
                                $cikti .= ' <div class="glycon-badge badge-secondary">Gönderim Sırasında</div>';

                            } elseif ($durum == "canceled") {
                                $cikti .= '<div class="glycon-badge badge-danger">İptal Edildi</div>';

                            } elseif ($durum == "partial") {
                                $cikti .= '<div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>';

                            } elseif ($durum == "İptal") {
                                $cikti .= '<div class="glycon-badge badge-danger">İptal</div>';

                            } else {
                                $cikti .= '<div class="glycon-badge badge-info">' . $durum . '</div>';

                            }
                        }
                    }
                    return $cikti;
                }
            ),

            array(
                'db' => 'c.order_create',
                "dt" => 8,
                'field' => 'order_create'
            ),
            array(
                'db' => 'c.order_id',
                "dt" => 9,
                "formatter" => function ($d, $row) {
                    $d = $row['order_id'];
                    $services = new \App\Models\services();
                    $servis_bul = $services->where("service_id", $row['service_id'])->get()->getResultArray();
                    $return = '<div class="dropdown text-center">
                                            <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                İşlemler
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                       href="' . base_url("admin/order-detail/" . $row['order_id']) . '">Servis
                                                        Detayı</a></li>
                                                <hr>';

                    if ($row["order_error"] != "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0):

                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_errors"
                                                           data-id="' . $row["order_id"] . '">Fail
                                                            Detayı</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="' . base_url("admin/orders/order_resend/" . $row["order_id"]) . '">Yeniden
                                                            Gönder</a></li> <hr>';
                    endif;

                    if ($row["order_error"] == "-" && isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] != 0):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_details"
                                                           data-id="' . $row["order_id"] . '">Sipariş
                                                            Detayı</a>
                                                    </li>';
                    endif;
                    if (isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] == 0 || $row["order_error"] != "-"):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_orderurl"
                                                           data-id="' . $row["order_id"] . '">Sipariş
                                                            Linkini
                                                            Düzenle</a></li>';
                    endif;
                    if (isset($servis_bul[0]["service_api"]) && $servis_bul[0]["service_api"] == 0):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_startcount"
                                                           data-id="' . $row["order_id"] . '">Başlangıç
                                                            Miktarını Düzenle</a></li>';
                    endif;
                    if ($row["order_status"] != "partial"):
                        $return .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_partial"
                                                           data-id="' . $row["order_id"] . '">Kalan Miktarı
                                                            Düzenle</a></li><hr>';
                    endif;
                    $return .= '
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#" data-bs-toggle="modal"
                                                       data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_cancel/" . $row["order_id"]) . '">İptal
                                                        ve İade Et</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_complete/" . $row["order_id"]) . '">Tamamlandı</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="' . base_url("admin/orders/order_inprogress/" . $row["order_id"]) . '">Yükleniyor</a>
                                                </li>
                                            </ul>
                                        </div>';
                    return $return;
                }
            ),
        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, "dripfeed='1' && subscriptions_type='1' && subscriptions_id=" . $subs, 'ORDER BY order_id DESC')
        );
    }

    public function getallPayments()
    {
        $table = "payments";
        $joinQuery = "FROM `payments` AS `c` LEFT JOIN `payment_methods` AS `cn` ON (`cn`.`id` = `c`.`payment_method`) LEFT JOIN `clients` AS `cl` ON (`cl`.`client_id` = `c`.`client_id`)";

        $primary = "payment_id";
        $colums = array(
            array(
                'db' => 'c.payment_id',
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $d = $row['payment_id'];
                    return '#' . $d;
                }
            ),
            array(
                'db' => 'cl.username',
                "dt" => 1,
                'field' => 'username'
            ),
            array(
                'db' => 'c.client_balance',
                "dt" => 2,
                'field' => 'client_balance'
            ),
            array(
                'db' => 'c.payment_amount',
                "dt" => 3,
                'field' => 'payment_amount'
            ),
            array(
                'db' => 'cn.method_name',
                "dt" => 4,
                'field' => 'method_name'
            ),
            array(
                'db' => 'c.payment_status',
                "dt" => 5,
                "formatter" => function ($d, $row) {
                    $d = $row['payment_status'];
                    if ($d == 3) {
                        $cikis = ' <div class="glycon-badge badge-success">';
                        $cikis .= "Tamamlandı";
                    } elseif ($d == 2) {
                        $cikis = ' <div class="glycon-badge badge-danger">';
                        $cikis .= "İptal Edildi";
                    } else {
                        $cikis = ' <div class="glycon-badge badge-info">';
                        $cikis .= "Banka Ödemesi";

                    }
                    $cikis .= "</div>";
                    return $cikis;
                }
            ),
            array(
                'db' => 'c.payment_mode',
                "dt" => 6,
                'field' => 'payment_mode'
            ),
            array(
                'db' => 'c.payment_note',
                "dt" => 7,
                'field' => 'payment_note'
            ),
            array(
                'db' => 'c.payment_create_date',
                "dt" => 8,
                'field' => 'payment_create_date'
            ),
            array(
                'db' => 'c.payment_update_date',
                "dt" => 9,
                'field' => 'payment_update_date'
            ),
            array(
                'db' => 'c.payment_mode',
                "dt" => 10,
                "formatter" => function ($d, $row) {
                    $cikis = "";
                    if ($row["payment_mode"] == "Otomatik"):
                        $cikis .= '<a href="#"
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#modalDiv"
                                                                                                                data-action="payment_detail"
                                                                                                                data-id="' . $row['payment_id'] . '"><i class="fas fa-search text-dark"></i></a>';
                    endif;
                    return $cikis;
                }
            ),
            array(
                'db' => 'c.payment_status',
                "dt" => 11,
                "formatter" => function ($d, $row) {
                    $cikis = "";

                    $cikis .= '<a href="#" data-bs-toggle="modal" data-bs-target="#modalDiv"
                                           data-action="payment_edit" data-id="' . $row["payment_id"] . '">Düzenle</a>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, 'payment_method != 7 and payment_status = 3', 'ORDER BY payment_id DESC')
        );
    }

    public function getallPaymentsBank()
    {
        $table = "payments";
        $joinQuery = "FROM `payments` AS `c` LEFT JOIN `payment_methods` AS `cn` ON (`cn`.`id` = `c`.`payment_method`) LEFT JOIN `clients` AS `cl` ON (`cl`.`client_id` = `c`.`client_id`)";

        $primary = "payment_id";
        $colums = array(
            array(
                'db' => 'c.payment_id',
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $d = $row['payment_id'];
                    return '#' . $d;
                }
            ),
            array(
                'db' => 'cl.username',
                "dt" => 1,
                'field' => 'username'

            ),
            array(
                'db' => 'c.client_balance',
                "dt" => 2,
                'field' => 'client_balance'
            ),
            array(
                'db' => 'c.payment_amount',
                "dt" => 3,
                'field' => 'payment_amount'
            ),
            array(
                'db' => 'cn.method_name',
                "dt" => 4,
                'field' => 'method_name'
            ),
            array(
                'db' => 'c.payment_status',
                "dt" => 5,
                "formatter" => function ($d, $row) {
                    $d = $row['payment_status'];
                    if ($d == 3) {
                        $cikis = ' <div class="glycon-badge badge-success">';
                        $cikis .= "Tamamlandı";
                    } elseif ($d == 2) {
                        $cikis = ' <div class="glycon-badge badge-danger">';
                        $cikis .= "İptal Edildi";
                    } else {
                        $cikis = ' <div class="glycon-badge badge-info">';
                        $cikis .= "Banka Ödemesi";

                    }
                    $cikis .= "</div>";
                    return $cikis;
                }
            ),
            array(
                'db' => 'c.payment_mode',
                "dt" => 6,
                'field' => 'payment_mode'
            ),
            array(
                'db' => 'c.payment_note',
                "dt" => 7,
                'field' => 'payment_note'
            ),
            array(
                'db' => 'c.payment_create_date',
                "dt" => 8,
                'field' => 'payment_create_date'
            ),
            array(
                'db' => 'c.payment_update_date',
                "dt" => 9,
                'field' => 'payment_update_date'
            ),
            array(
                'db' => 'c.payment_mode',
                "dt" => 10,
                "formatter" => function ($d, $row) {
                    $cikis = "";
                    if ($row["payment_mode"] == "Otomatik"):
                        $cikis .= '<a href="#"
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#modalDiv"
                                                                                                                data-action="payment_detail"
                                                                                                                data-id="' . $row['payment_id'] . '"><i class="fas fa-search text-dark"></i></a>';
                    endif;
                    return $cikis;
                }
            ),
            array(
                'db' => 'c.payment_status',
                "dt" => 11,
                "formatter" => function ($d, $row) {

                    $cikis = '<div class="dropdown"><button class="btn btn-secondary dropdown-toggle py-1"
                                type="button"
                                id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            İşlemler
                        </button>
                        <ul class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton1">
                        ';


                    $cikis .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="payment_bankedit" data-id="' . $row["payment_id"] . '">Düzenle</a></li>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, 'payment_method = 7', 'ORDER BY payment_id DESC')
        );
    }

    public function getallLogs()
    {
        $table = "client_report";
        $joinQuery = "FROM `client_report` AS `c` LEFT JOIN `clients` AS `cl` ON (`cl`.`client_id` = `c`.`client_id`)";

        $primary = "id";
        $colums = array(
            array(
                'db' => 'cl.username',
                "dt" => 0,
                'field' => 'username'

            ),
            array(
                'db' => 'c.action',
                "dt" => 1,
                'field' => 'action'
            ),
            array(
                'db' => 'c.report_ip',
                "dt" => 2,
                'field' => 'report_ip'
            ),
            array(
                'db' => 'c.report_date',
                "dt" => 3,
                'field' => 'report_date'
            ),
            array(
                'db' => 'c.id',
                "dt" => 4,
                "formatter" => function ($d, $row) {
                    $d = $row['id'];
                    $cikis = ' <a href="' . base_url() . '/admin/logs/delete/' . $d . '"><i class="fas fa-trash text-danger"></i></a>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, '', 'ORDER BY id DESC')
        );
    }

    public function getallGuardLogs()
    {
        $table = "guard_log";
        $joinQuery = "FROM `guard_log` AS `c` LEFT JOIN `clients` AS `cl` ON (`cl`.`client_id` = `c`.`client_id`)";

        $primary = "id";
        $colums = array(
            array(
                'db' => 'cl.username',
                "dt" => 0,
                'field' => 'username'

            ),
            array(
                'db' => 'c.action',
                "dt" => 1,
                'field' => 'action'
            ),
            array(
                'db' => 'c.date',
                "dt" => 2,
                'field' => 'date'
            ),
            array(
                'db' => 'c.ip',
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    $d = $row['ip'];
                    return "<a href='https://ipapi.co/" . $d . "/json/'><i class='fa fa-search'></i> Görüntüle </a>";
                }
            ),
            array(
                'db' => 'c.id',
                "dt" => 4,
                "formatter" => function ($d, $row) {
                    $d = $row['id'];
                    $cikis = ' <a href="' . base_url() . '/admin/guard_logs/delete/' . $d . '"><i class="fas fa-trash text-danger"></i></a>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, '', 'ORDER BY id DESC')
        );
    }

    public function getallServiceLogs()
    {
        $table = "serviceapi_alert";

        $primary = "id";
        $colums = array(
            array(
                'db' => 'serviceapi_alert',
                "dt" => 0,

            ),
            array(
                'db' => 'servicealert_date',
                "dt" => 1,
            ),
            array(
                'db' => 'servicealert_extra',
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    $d = $row['servicealert_extra'];
                    $extra = json_decode($d, true);
                    return "Eski değer: " . $extra["old"] . " / Güncel değer:" . $extra["new"];
                }
            ),
            array(
                'db' => 'id',
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    $d = $row['id'];
                    $cikis = ' <a href="' . base_url() . '/admin/provider_logs/delete/' . $d . '"><i class="fas fa-trash text-danger"></i></a>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, null, '', 'ORDER BY id DESC')
        );
    }

    public function getallClients()
    {
        $table = "clients";

        $primary = "client_id";
        $colums = array(
            array(
                'db' => 'client_id',
                "dt" => 0,

            ),
            array(
                'db' => 'username',
                "dt" => 1,
            ),
            array(
                'db' => 'email',
                "dt" => 2,

            ),
            array(
                'db' => 'telephone',
                "dt" => 3,
            ),
            array(
                'db' => 'balance',
                "dt" => 4,
            ),
            array(
                'db' => 'spent',
                "dt" => 5,
            ),
            array(
                'db' => 'register_date',
                "dt" => 6,
            ),
            array(
                'db' => 'login_date',
                "dt" => 7,
            ),
            array(
                'db' => 'client_type',
                "dt" => 8,
                "formatter" => function ($d, $row) {
                    return $row["client_type"] == 2 ? '<div class="glycon-badge badge-success">Aktif</div>' : '<div class="glycon-badge badge-danger">Pasif</div>';
                }
            ),
            array(
                'db' => 'client_id',
                "dt" => 9,
                "formatter" => function ($d, $row) {
                    $d = $row['client_id'];
                    $cikis = '<div class="dropdown text-center">
                                            <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                İşlemler
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                    $cikis .= '<li><a class="dropdown-item" href="' . base_url("admin/client-detail/" . $d) . '">Müşteri Detayı</a></li>';
                    $cikis .= '<li><a class="dropdown-item" href="' . base_url("admin/clients/login/" . $d) . '">Giriş Yap</a></li>';
                    $cikis .= '<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="secret_user" data-id="' . $d . '" href="#">Özel Kategoriler</a></li>';
                    $cikis .= '<li><a class="dropdown-item" href="' . base_url("admin/clients/del_price/" . $d) . '">İndirimleri Sil</a></li>';
                    $cikis .= '<li><a class="dropdown-item" href="' . base_url("admin/orders?client=" . $row["username"]) . '">Siparişleri Gör</a></li>';
                    $cikis .= '</ul></div>';
                    return $cikis;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, null, '', 'ORDER BY client_id DESC')
        );
    }

    public function getAlltasks()
    {
        $table = "tasks";
        $joinQuery = "FROM `tasks` AS `c` LEFT JOIN `clients` AS `cn` ON (`cn`.`client_id` = `c`.`client_id`) LEFT JOIN `orders` AS `cl` ON (`cl`.`order_id` = `c`.`order_id`) LEFT JOIN `services` AS `sv` ON (`sv`.`service_id` = `c`.`service_id`)";

        $primary = "task_id";
        $colums = array(

            array(
                'db' => 'c.refill_orderid',
                "dt" => 0,
                'field' => 'refill_orderid'

            ),

            array(
                'db' => 'cl.api_orderid',
                "dt" => 0,
                'field' => 'api_orderid'

            ),
            array(
                'db' => 'c.task_id',
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $d = $row['task_id'];
                    $cikti = $d;
                    if ($row['refill_orderid']) {
                        $cikti .= '<div class="service-block__provider-value">' . $row['refill_orderid'] . '</div>';
                    }
                    return $cikti;
                }

            ),
            array(
                'db' => 'cl.order_id',
                "dt" => 1,
                "formatter" => function ($d, $row) {
                    $d = $row['order_id'];
                    $cikti = $d;
                    if ($row['api_orderid']) {
                        $cikti .= '<div class="service-block__provider-value">' . $row['api_orderid'] . '</div>';
                    }
                    return $cikti;
                }
            ),
            array(
                'db' => 'cn.username',
                "dt" => 2,
                'field' => 'username'
            ),
            array(
                'db' => 'sv.service_name',
                "dt" => 3,
                'field' => 'service_name'
            ),
            array(
                'db' => 'cl.order_url',
                "dt" => 4,
                'field' => 'order_url'
            ),
            array(
                'db' => 'cl.order_start',
                "dt" => 5,
                'field' => 'order_start'
            ),
            array(
                'db' => 'cl.order_quantity',
                "dt" => 6,
                'field' => 'order_quantity'
            ),
            array(
                'db' => 'c.task_type',
                "dt" => 7,
                "formatter" => function ($d, $row) {
                    $d = $row['task_type'];
                    if ($d):
                        return "İptal";
                    else:
                        return "Refill";

                    endif;
                }
            ),
            array(
                'db' => 'c.task_status',
                "dt" => 8,
                "formatter" => function ($d, $row) {
                    $d = $row['task_status'];
                    if ($d == "pending"):
                        return "Onay Bekleniyor";
                    elseif ($d == "success"):
                        return "Onaylandı";
                    elseif ($d == "canceled"):
                        return "Reddedildi";
                    endif;
                }
            ),

            array(
                'db' => 'c.task_date',
                "dt" => 9,
                'field' => 'task_date'
            ),
            array(
                'db' => 'c.task_id',
                "dt" => 10,
                "formatter" => function ($d, $row) {
                    $d = $row['task_id'];
                    $cikti = '<div class="dropdown pull-right">
                     <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">İşlemler <span class="caret"></span></button>
                       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                    $cikti .= '<li><a class="dropdown-item" href="' . base_url("admin/tasks/no/" . $row["task_id"]) . '">Reddet</a></li>';
                    if ($row["task_type"] == 2) {
                        $cikti .= '<li><a class="dropdown-item" href="' . base_url("admin/tasks/success/" . $row["task_id"]) . '">Onayla</a></li>';
                    } elseif ($row["task_type"] == 1) {
                        $cikti .= '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="' . base_url("admin/tasks/canceled/" . $row["task_id"]) . '">İptal ve iade et</a></li>';

                    }
                    return $cikti;
                }
            ),


        );

        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, '', 'ORDER BY task_id DESC')
        );
    }

    public function getAllTickets()
    {
        $table = "tickets";
        $joinQuery = "FROM `tickets` AS `c` LEFT JOIN `clients` AS `cn` ON (`cn`.`client_id` = `c`.`client_id`)";

        $primary = "ticket_id";
        $colums = array(

            array(
                'db' => 'c.ticket_id',
                "dt" => 0,
                'field' => 'ticket_id'

            ),

            array(
                'db' => 'cn.username',
                "dt" => 1,
                "formatter" => function ($d, $row) {
                    $username = $row['username'];
                    return '<a href="' . base_url("admin/tickets/read/" . $row["ticket_id"]) . '" class="text-glycon">' . $row["username"] . '<i class="fas fa-external-link-alt"></i></a>';
                }

            ),
            array(
                'db' => 'c.subject',
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    $username = $row['username'];
                    return '<a href="' . base_url("admin/tickets/read/" . $row["ticket_id"]) . '" class="text-glycon">' . $row["subject"] . '<i class="fas fa-external-link-alt"></i></a>';
                }

            ),
            array(
                'db' => 'c.status',
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    $status = $row['status'];
                    $cikti = '';
                    if ($status == 'answered') {
                        $cikti .= '<div class="glycon-badge badge-info">
                                    Cevaplandı
                                </div>';
                    } elseif ($status == 'pending') {
                        $cikti .= '<div class="glycon-badge badge-danger">
                                    Cevap Bekliyor
                                </div>';
                    } elseif ($status == 'closed') {
                        $cikti .= '<div class="glycon-badge badge-success">
                                    Çözümlendi
                                </div>';
                    }
                    return $cikti;
                }

            ),
            array(
                'db' => 'c.time',
                "dt" => 4,
                'field' => 'time'

            ),
            array(
                'db' => 'c.lastupdate_time',
                "dt" => 5,
                'field' => 'lastupdate_time'

            ),
            array(
                'db' => 'c.ticket_id',
                "dt" => 6,
                "formatter" => function ($d, $row) {
                    $username = $row['username'];
                    return '<a href="' . base_url("admin/tickets/read/" . $row["ticket_id"]) . '" class="text-glycon"><i class="fas fa-search text-dark"></i></a>';
                }

            ),
            array(
                'db' => 'c.ticket_id',
                "dt" => 7,
                "formatter" => function ($d, $row) {
                    $username = $row['username'];
                    return '<a href="' . base_url("admin/tickets/close/" . $row["ticket_id"]) . '" class="text-glycon"><i class="fas fa-trash text-danger"></i></a>';
                }

            ),


        );
        $where = '';
        if($this->request->getGet('durum')){
            if($this->request->getGet('durum') == 'unread'){

            $where .= "support_new = '2'";
            }else{

            $where .= "status = '" . $this->request->getGet('durum') . "'";
            }
        }
        echo json_encode(
            \SSP::simple($_GET, $this->currentdb, $table, $primary, $colums, $joinQuery, $where, 'ORDER BY ticket_id DESC')
        );
    }

}