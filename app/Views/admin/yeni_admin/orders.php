<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
    span.label.label-api {
        background: #122645;
        padding: 1px 5px;
        border-radius: 3px;
        color: white;
    }

    span.label-id {
        background: #122645;
        padding: 0px 5px;
        border-radius: 3px;
        margin-right: 5px;
        color: white;
    }
</style>
<style>
    .collapse.in {
        display: block !important;
    }

    .w-10 {
        width: 10%;
    }

    .w-30 {
        width: 30%;
    }

    .w-5 {
        width: 5%;
    }

    .action-block {
        width: 100%;
        position: absolute;
        left: 0px;
        top: 50px;
        height: 44px;
        line-height: 40px;
        background: #0d2443;
        z-index: 1;
        padding-left: 35px;
    }
</style>
<div class="container-fluid px-sm-5">
    <div class="row">
        <div class="col-md-12 collapse ms-auto mb-3" id="filter">
            <a href="<?= base_url(); ?>/admin/orders"
               class="btn btn-primary bg-gradient w-100 w-sm-auto my-1 my-0">Tümü</a>
            <a href="javascript:void(0)" id="pending_siparis"
               class="btn btn-warning bg-gradient w-100 w-sm-auto my-1 my-0">Sipariş Alındı</a>
            <a href="javascript:void(0)" id="processing_siparis"
               class="btn btn-secondary bg-gradient w-100 w-sm-auto my-1 my-0">Gönderim Sırasında</a>
            <a href="javascript:void(0)" id="inprogress_siparis"
               class="btn btn-info bg-gradient w-100 w-sm-auto my-1 my-0">Yükleniyor</a>
            <a href="javascript:void(0)" id="completed_siparis"
               class="btn btn-success bg-gradient w-100 w-sm-auto my-1 my-0">Tamamlandı</a>
            <a href="javascript:void(0)" id="partial_siparis"
               class="btn btn-light bg-gradient w-100 w-sm-auto my-1 my-0" style="background:#8e44ad;color:white;">Kısmen Tamamlandı</a>
            <a href="javascript:void(0)" id="canceled_siparis"
               class="btn btn-dark bg-gradient w-100 w-sm-auto my-1 my-0">İptal Edildi</a>
            <a href="javascript:void(0)" id="fail_siparis"
               class="btn btn-danger bg-gradient w-100 w-sm-auto my-1 my-0">Fail</a>
            <a href="javascript::void(0)" id="manuel_siparis"
               class="btn btn-info bg-gradient w-100 w-sm-auto my-1 my-0">Manuel Siparişler</a>
        </div>
        <div class="col-md-12 collapse ms-auto mb-3" id="search">
            <form class="form-inline" action="" method="get">
                <div class="input-group">
                    <input type="text" name="search" id="siparis_ara" class="form-control" value="<?= $arama ?>"
                           placeholder="Siparişleri ara...">
                    <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
							<option value="order_id">ID</option>
							<option value="order_url">Bağlantı</option>
							<option value="username">Müşteri</option>
						</select>
						<button type="submit" class="btn btn-dark ms-2"><span class="fa fa-search"
                                                                              aria-hidden="true"></span></button>
					</span>
                </div>
            </form>
        </div>
        <?php if(!route(4)): ?>
        <div class="col-md-12">

            <div class="card">
                <div class="card-header p-0">
                    <div class="row align-items-center">
                        <div class="col-md-12 navbar-expand-lg">
                            <div class="card-header-title">Siparişler</div>
                            <button class="navbar-toggler float-end" style="margin-top:10px;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-bars text-white"></i>
                            </button>
                            <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
                               <a class="btn-glycon btn-glycon-warning btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false"
                                   aria-controls="filter">
                                    <i class="fas fa-search"></i> Filtrele
                                </a>
                                <a class="btn-glycon btn-glycon-danger btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#search" role="button" aria-expanded="false"
                                   aria-controls="search">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body mobile-overflow p-0">
                         <form id="changebulkForm" action="<?php echo base_url("admin/orders/multi-action") ?>"
                              method="post">

                    <table class="table border table-striped m-0" id="orders">
                        <thead class="card-glycon text-white">
                        <tr>
                            <th scope="col">
                                <input class="form-check-input me-2" type="checkbox" id="checkAll"
                                       style="position:relative;z-index:9;">
                                <input type="hidden" id="checkAllText" value="order">
                                <div class="action-block countblok" style="display: none;">
                                    <button type="button" class="btn btn-primary dropdown-toggle ms-5 py-1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="countOrders"></span> sipariş seçili
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php if (1): ?>
                                                <a class="dropdown-item bulkorder" data-type="pending">Sipariş
                                                    Alındı</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if (1): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="inprogress">Yükleniyor</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if (1): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="completed">Tamamlandı</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if (1): ?>
                                                <a class="dropdown-item bulkorder" data-type="canceled">İptal ve
                                                    İade Et</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if (1): ?>
                                                <a class="dropdown-item bulkorder" data-type="resend"> Yeniden
                                                    Gönder</a>
                                            <?php endif; ?>
                                        </li>

                                    </ul>
                                </div>
                                ID
                            </th>
                            <th scope="col">Müşteri</th>
                            <th scope="col">Bağlantı</th>
                            <th scope="col">Servis</th>
                            <th scope="col" class="text-center">Başlangıç</th>
                            <th scope="col" class="text-center">Miktar</th>
                            <th scope="col" class="text-center">Tutar</th>
                            <th scope="col" class="text-center">Durum</th>
                            <th scope="col" class="text-center">Oluşturma Tarihi</th>
                            <th></th>
                        </tr>
                        </thead>
                            <tbody class="icon-animation">
                            </tbody>
                            <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">


                    </table>
                             </form>
                </div>
            </div>
        </div>
        <?php else: ?>

        <div class="col-md-12">

            <div class="card">
                <div class="card-header p-0">
                    <div class="row align-items-center">
                        <div class="col-md-12 navbar-expand-lg">
                            <div class="card-header-title">Siparişler</div>
                            <button class="navbar-toggler float-end" style="margin-top:10px;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-bars text-white"></i>
                            </button>
                            <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
                                <a class="btn-glycon btn-glycon-warning btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false"
                                   aria-controls="filter">
                                    <i class="fas fa-search"></i> Filtrele
                                </a>
                                <a class="btn-glycon btn-glycon-danger btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#search" role="button" aria-expanded="false"
                                   aria-controls="search">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body mobile-overflow p-0">
                    <table class="table border table-striped m-0" id="orders">
                        <thead class="card-glycon text-white">
                        <tr>
                            <th scope="col">
                                <input class="form-check-input me-2" type="checkbox" id="checkAll"
                                       style="position:relative;z-index:9;">
                                <input type="hidden" id="checkAllText" value="order">
                                <div class="action-block countblok" style="display: none;">
                                    <button type="button" class="btn btn-primary dropdown-toggle ms-5 py-1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="countOrders"></span> sipariş seçili
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php if ($status == "inprogress" || $status == "processing"): ?>
                                                <a class="dropdown-item bulkorder" data-type="pending">Sipariş
                                                    Alındı</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status == "pending" || $status == "processing"): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="inprogress">Yükleniyor</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status == "pending" || $status == "inprogress" || $status == "processing" || $status == "fail"): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="completed">Tamamlandı</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status == "all" || $status == "pending" || $status == "inprogress" || $status == "completed" || $status == "processing" || $status == "partial" || $status == "fail"): ?>
                                                <a class="dropdown-item bulkorder" data-type="canceled">İptal ve
                                                    İade Et</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status == "fail"): ?>
                                                <a class="dropdown-item bulkorder" data-type="resend"> Yeniden
                                                    Gönder</a>
                                            <?php endif; ?>
                                        </li>

                                    </ul>
                                </div>
                                ID
                            </th>
                            <th scope="col">Müşteri</th>
                            <th scope="col">Bağlantı</th>
                            <th scope="col">Servis</th>
                            <th scope="col" class="text-center">Başlangıç</th>
                            <th scope="col" class="text-center">Miktar</th>
                            <th scope="col" class="text-center">Tutar</th>
                            <th scope="col" class="text-center">Durum</th>
                            <th scope="col" class="text-center">Oluşturma Tarihi</th>
                            <th></th>
                        </tr>
                        </thead>
                        <form id="changebulkForm" action="<?php echo base_url("admin/orders/multi-action") ?>"
                              method="post">
                            <tbody class="icon-animation">
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="text-start services-provider"><input
                                                type="checkbox" <?php if ($status == "canceled"): echo "disabled"; else: echo 'class="form-check-input me-2 selectOrder"'; endif; ?>
                                                name="order[<?php echo $order["order_id"] ?>]"
                                                value="1">#<?php echo $order["order_id"] ?> <?php if ($order["order_where"] == "api"): echo ' <span class="label label-api">API</span>'; endif; ?>
                                        <br>
                                        <small class="ms-4">#<?= $order['api_orderid'] ?></small>
                                    </td>
                                    <td><a href="<?= base_url() ?>/admin/client-detail/<?= $order["client_id"]; ?>"
                                           target="_blank"
                                           class="text-glycon"><?php echo $order["username"];
                                            ?>
                                            <i class="fas fa-external-link-alt"></i></a></td>
                                    <td><?php echo $order["order_url"]; ?></td>
                                    <td><?php if ($order["service_id"]): ?> <span
                                                class="label-id"><?php echo $order["service_id"]; ?></span><?php endif;
                                        echo $order["service_name"] != "" ? $order["service_name"] : "Servis Silinmiş"; ?>
                                        <?= $order['birlestirme'] ? "<small style='color:#c0392b'>Birleştirilmiş Servis | </small>" : "" ?>
                                        <?= $order['sirali_islem'] ? "<small style='color:#c0392b'>Sıralı İşlem</small>" : "" ?>
                                    </td>
                                    <td><?= $order['order_start']?></td>
                                    <td class="text-center services-provider"><?php echo $order["order_quantity"]; ?><br><small><?= $order['order_remains']?></small></td>
                                    <td class="text-center services-provider"><?php echo $order["order_charge"]; ?>
                                        <br>
                                        <small><?= $order['api_charge'] ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $durum = orderStatu($order["order_status"], $order["order_error"], $order["order_detail"]);
                                        if ($durum == "completed") { ?>
                                            <div class="glycon-badge badge-success">Tamamlandı</div>
                                        <?php } elseif ($order["order_error"] != "-" && $order["service_api"] != 0) { ?>
                                            <div class="glycon-badge badge-danger">Fail</div>
                                        <?php } elseif ($durum == "pending") { ?>
                                            <div class="glycon-badge badge-primary">Sipariş Alındı</div>
                                        <?php } elseif ($durum == "inprogress") { ?>
                                            <div class="glycon-badge badge-secondary">Gönderim Sırasında</div>
                                        <?php } elseif ($durum == "processing") { ?>
                                            <div class="glycon-badge badge-info">Yükleniyor</div>
                                        <?php } elseif ($durum == "canceled") { ?>
                                            <div class="glycon-badge badge-danger">İptal Edildi</div>
                                        <?php } elseif ($durum == "partial") { ?>
                                            <div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>
                                        <?php } else { ?>

                                            <div class="glycon-badge badge-success"><?= $durum ?></div>


                                        <?php }
                                        ?>
                                        <?php if ($order['birlestirme'] && $order['sirali_islem']) {
                                            ?>

                                            <?php
                                            $tum = $cift->where('order_id', $order['order_id'])->get()->getResultArray();
                                            if (isset($tum[0])) {
                                                $tum = $tum[0];

                                                $durum = orderStatu($tum['status'], $order["order_error2"], $order["order_detail2"]);
                                                if ($order["order_error"] != "-" && $order["service_api2"] != 0) { ?>
                                                    <div class="glycon-badge badge-danger">Fail</div>
                                                <?php }elseif ($durum == "completed") { ?>
                                                    <div class="glycon-badge badge-success">Tamamlandı</div>

                                                <?php } elseif ($durum == "pending") { ?>
                                                    <div class="glycon-badge badge-warning">Sipariş Alındı</div>
                                                <?php } elseif ($durum == "processing") { ?>
                                                    <div class="glycon-badge badge-secondary">Gönderim Sırasında</div>
                                                <?php } elseif ($durum == "inprogress") { ?>
                                                    <div class="glycon-badge badge-info">Yükleniyor</div>
                                                <?php } elseif ($durum == "canceled") { ?>
                                                    <div class="glycon-badge badge-danger">İptal Edildi</div>
                                                <?php } elseif ($durum == "partial") { ?>
                                                    <div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>
                                                <?php } else { ?>

                                                    <div class="glycon-badge badge-info"><?= $durum ?></div>


                                                <?php }
                                            }
                                            ?>
                                            <?php
                                        } ?>
                                    </td>
                                    <td><?= $order['order_create']?></td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                İşlemler
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                       href="<?= base_url("admin/order-detail/" . $order['order_id']) ?>">Servis
                                                        Detayı</a></li>
                                                <hr>
                                                <?php if ($order["order_error"] != "-" && $order["service_api"] != 0): ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_errors"
                                                           data-id="<?php echo $order["order_id"] ?>">Fail
                                                            Detayı</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="<?= base_url("admin/orders/order_resend/" . $order["order_id"]) ?>">Yeniden
                                                            Gönder</a></li>
                                                <?php endif; ?>
                                                <hr>
                                                <?php if ($order["order_error"] == "-" && $order["service_api"] != 0): ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_details"
                                                           data-id="<?php echo $order["order_id"] ?>">Sipariş
                                                            Detayı</a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($order["service_api"] == 0 || $order["order_error"] != "-"): ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_orderurl"
                                                           data-id="<?php echo $order["order_id"] ?>">Sipariş
                                                            Linkini
                                                            Düzenle</a></li>
                                                <?php endif; ?>
                                                <?php if ($order["service_api"] == 0): ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_startcount"
                                                           data-id="<?php echo $order["order_id"] ?>">Başlangıç
                                                            Miktarını Düzenle</a></li>
                                                <?php endif; ?>
                                                <?php if ($order["order_status"] != "partial"): ?>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#modalDiv"
                                                           data-action="order_partial"
                                                           data-id="<?php echo $order["order_id"] ?>">Kalan Miktarı
                                                            Düzenle</a></li>
                                                <?php endif; ?>
                                                <hr>
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#" data-bs-toggle="modal"
                                                       data-bs-target="#confirmChange"
                                                       data-href="<?= base_url("admin/orders/order_cancel/" . $order["order_id"]) ?>">İptal
                                                        ve İade Et</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="<?= base_url("admin/orders/order_complete/" . $order["order_id"]) ?>">Tamamlandı</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       data-bs-toggle="modal" data-bs-target="#confirmChange"
                                                       data-href="<?= base_url("admin/orders/order_inprogress/" . $order["order_id"]) ?>">Yükleniyor</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
                        </form>

                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= view('admin/yeni_admin/static/footer'); ?>
<?php
$url = $subs!=""?base_url('admin/getallOrdersSubs?subs='.$subs):base_url('admin/getallOrders');
if(isset($_GET['services'])){
    $url.= "?services=".$_GET['services'];
}
?>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    <?php if(!route(4)): ?>
    $(document).ready(function () {
        var oTable = $('#orders').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?= $url ?>",
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            dom: 'lrtp',
            'ordering': false,
            pageLength: 50,
            "aoColumns": [
                {"sClass": "text-start services-provider"},
                null,
                null,
                null,
                null,
                {"sClass": "text-start services-provider"},
                {"sClass": "text-start services-provider"},
                null,
                null,
                null,
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
        oTable.search($('#siparis_ara').val()).draw();
    });
    <?php endif; ?>
    $(document).on('click','#baglanti_adres', function() {
       navigator.clipboard.writeText($(this).data('url'));
               swal({
            title: 'Başarıyla Aşşağıdaki Adresi Kopyaladınız',
            text: $(this).data('url')

        })
    });
    //siparis_ara

    function tablo_olustur(url){
                var oTable = $('#orders').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": url,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            dom: 'lrtp',
            'ordering': false,
            pageLength: 50,
            "aoColumns": [
                {"sClass": "text-start services-provider"},
                null,
                null,
                null,
                null,
                {"sClass": "text-start services-provider"},
                {"sClass": "text-start services-provider"},
                null,
                null,
                null,
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
        oTable.search($('#siparis_ara').val()).draw();
    }
    $('#manuel_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=manuel')?>");
    });
    $('#pending_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=pending')?>");
    });
    $('#inprogress_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=inprogress')?>");
    });
    $('#processing_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=processing')?>");
    });
    $('#completed_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=completed')?>");
    });
    $('#partial_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=partial')?>");
    });
    $('#canceled_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=canceled')?>");
    });
    $('#fail_siparis').click(function(){
        $('#orders').DataTable().destroy();
        $('#orders tbody').empty();
        tablo_olustur("<?= base_url('admin/getallOrders?mode=fail')?>");
    });
</script>
