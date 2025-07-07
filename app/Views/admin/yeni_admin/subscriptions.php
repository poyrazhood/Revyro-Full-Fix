<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<link rel='stylesheet' href='<?= base_url('assets')?>/js/datepicker/css/bootstrap-datepicker3.min.css'>
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
            <a href="<?=base_url("admin/subscriptions")?>"
               class="btn btn-primary bg-gradient w-100 w-sm-auto my-1 my-0">Tümü</a>
            <a href="<?=base_url("admin/subscriptions/1/active")?>"
               class="btn btn-warning bg-gradient w-100 w-sm-auto my-1 my-0">Aktif</a>
            <a href="<?=base_url("admin/subscriptions/1/paused")?>"
               class="btn btn-secondary bg-gradient w-100 w-sm-auto my-1 my-0">Durduruldu</a>
            <a href="<?=base_url("admin/subscriptions/1/completed")?>"
               class="btn btn-info bg-gradient w-100 w-sm-auto my-1 my-0">Tamamlandı</a>
            <a href="<?=base_url("admin/subscriptions/1/canceled")?>"
               class="btn btn-success bg-gradient w-100 w-sm-auto my-1 my-0">İptal Edildi</a>
            <a href="<?=base_url("admin/subscriptions/1/expired")?>"
               class="btn btn-secondary bg-gradient w-100 w-sm-auto my-1 my-0">Süresi Doldu</a>
            <a href="<?=base_url("admin/subscriptions/1/limit")?>"
               class="btn btn-danger bg-gradient w-100 w-sm-auto my-1 my-0">Süreli Paketler</a>
        </div>
        <div class="col-md-12 collapse ms-auto mb-3" id="search">
            <form class="form-inline" action="<?=base_url("admin/subscriptions")?>" method="get">
                <div class="input-group">
                    <input type="text" name="search" id="siparis_ara" class="form-control" value="<?=$search_word?>"
                           placeholder="Abonelikleri ara...">
                    <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if( $search_where == "order_id" ): echo 'selected'; endif; ?> >ID</option>
                     <option value="order_url" <?php if( $search_where == "order_url" ): echo 'selected'; endif; ?> >Link</option>
                     <option value="username" <?php if( $search_where == "username" ): echo 'selected'; endif; ?> >Kullanıcı Adı</option>
                  </select>
						<button type="submit" class="btn btn-dark ms-2"><span class="fa fa-search"
                                                                              aria-hidden="true"></span></button>
					</span>
                </div>
            </form>
        </div>
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
                         <form id="changebulkForm" action="<?php echo base_url("admin/subscriptions/multi-action") ?>" method="post">

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
                                        <span class="countOrders"></span> abonelik seçili</li>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php if( $status  ==  "active" ): ?>
                                                <a class="dropdown-item bulkorder" data-type="paused">Durduruldu</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status  ==  "active" || $status  ==  "paused"): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="completed">Tamamlandı</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status  ==  "active" || $status  ==  "paused"): ?>
                                                <a class="dropdown-item bulkorder"
                                                   data-type="canceled">İptal Edildi</a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if ($status  ==  "expired" || $status  ==  "paused" || $status  ==  "canceled"): ?>
                                                <a class="dropdown-item bulkorder" data-type="active">Aktif Et</a>
                                            <?php endif; ?>
                                        </li>

                                    </ul>
                                </div>
                                ID
                            </th>
            <th>ID</th>
            <th>Kullanıcı</th>
            <th>Kullanıcı Adı</th>
            <th>Miktar</th>
            <th>Gönderi</th>
            <th>Gecikme</th>
            <th class="dropdown-th">
              Servis
            </th>
            <th>Statü</th>
            <th>Oluşturma Tarihi</th>
            <th>Son Güncelleme</th>
            <th>Bitiş Tarihi</th>
            <th></th>
                        </tr>
                        </thead>
                            <tbody class="icon-animation">
                             <?php foreach( $orders as $order ): ?>
              <tr>
                 <td><input type="checkbox" <?php if( $status == "all" || $status == "canceled" ): echo "disabled"; else: echo 'class="selectOrder"'; endif; ?> name="order[<?php echo $order["order_id"] ?>]" value="1" style="border:1px solid #fff"></td>
                 <td class="p-l"><?php echo $order["order_id"] ?></td>
                 <td><?php echo $order["username"] ?></td>
                 <td><?php echo $order["order_url"]; ?></td>
                 <td><?php echo $order["subscriptions_min"]."-".$order["subscriptions_max"]; ?></td>
                 <td><?php echo $order["subscriptions_delivery"]."/".$order["subscriptions_posts"]; ?></td>
                 <td><?php if( $order["subscriptions_delay"] == 0 ): echo "Gecikme yok"; else: echo $order["subscriptions_delay"]/60; echo " dakika"; endif; ?></td>
                 <td><?php echo $order["service_name"]; ?></td>
                 <td><?php echo orderStatu($order["subscriptions_status"]); ?></td>
                 <td><?php echo date("d.m.Y H:i:s", strtotime($order["order_create"])); ?></td>
                 <td><?php echo date("d.m.Y H:i:s", strtotime($order["last_check"])); ?></td>
                 <td><?php if( $order["subscriptions_expiry"] != "1970-01-01" && $order["subscriptions_expiry"] != "1969-12-31" ): echo date("d.m.Y", strtotime($order["subscriptions_expiry"])); endif; ?></td>
                 <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                İşlemler
                                            </button>
                     <ul class="dropdown-menu">
                       <?php if( $order["subscriptions_status"] == "active" || $order["subscriptions_status"] == "paused" ): ?>
                         <li><a href="#"  data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="subscriptions_expiry" data-subs="1" data-id="<?php echo $order["order_id"] ?>">Bitiş Tarihi Ayarla</a></li>
                       <?php endif; ?>
                       <?php if( $order["subscriptions_status"] == "active" ): ?>
                         <li><a href="#" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?=base_url("admin/subscriptions/subscriptions_pause/".$order["order_id"])?>">Durdur</a></li>
                       <?php endif; ?>
                       <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "active" ): ?>
                         <li><a href="#" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?=base_url("admin/subscriptions/subscriptions_complete/".$order["order_id"])?>">Tamamla</a></li>
                       <?php endif; ?>
                       <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "expired" || $order["subscriptions_status"] == "canceled" ): ?>
                         <li><a href="#" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?=base_url("admin/subscriptions/subscriptions_active/".$order["order_id"])?>">Aktifleştir</a></li>
                       <?php endif; ?>
                       <?php if( $order["subscriptions_status"] == "paused" || $order["subscriptions_status"] == "active" ): ?>
                         <li><a href="#" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?=base_url("admin/subscriptions/subscriptions_canceled/".$order["order_id"])?>">İptal Et</a></li>
                       <?php endif; ?>
                     </ul>
                   </div>
                 </td>
              </tr>
            <?php endforeach; ?>
                            </tbody>
                            <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">


                    </table>
                             </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('admin/yeni_admin/static/footer'); ?>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        var oTable = $('#orders').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
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
                {"sClass": "text-start services-provider"},
                {"sClass": "text-start services-provider"},
                null,
                null
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    });
    //siparis_ara
</script>
