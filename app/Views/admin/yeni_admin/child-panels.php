<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<link rel='stylesheet' href='<?= base_url('assets') ?>/js/datepicker/css/bootstrap-datepicker3.min.css'>
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
        <div class="col-md-12">

                <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
        </div>
        <div class="col-md-12 collapse ms-auto mb-3" id="search">
            <form class="form-inline" action="<?=base_url("admin/child-panels")?>" method="get">
                <div class="input-group">
                    <input type="text" name="search" id="siparis_ara" class="form-control" value="<?= $search_word ?>"
                           placeholder="Abonelikleri ara...">
                    <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if ($search_where == "order_id"): echo 'selected'; endif; ?> >ID</option>
                     <option value="order_url" <?php if ($search_where == "order_url"): echo 'selected'; endif; ?> >Link</option>
                     <option value="username" <?php if ($search_where == "username"): echo 'selected'; endif; ?> >Kullanıcı Adı</option>
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
                            <div class="card-header-title">Child Panels</div>
                            <button class="navbar-toggler float-end" style="margin-top:10px;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-bars text-white"></i>
                            </button>
                            <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">

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
                                <th class="p-l">ID</th>
                                <th>Kullanıcı Adı</th>
                                <th>Domain</th>
                                <th>Para Birimi</th>
                                <th>Ödenen Ücret</th>
                                <th>Durum</th>
                                <th>Sipariş Tarihi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="icon-animation">
                            <?php foreach($panels as $panel): ?>
                          <tr>
                             <td class="p-l"><?php echo $panel["id"] ?></td>
                             <td><?php echo $panel["username"] ?></td>
                             <td><?php echo $panel["panel_domain"] ?></td>
                             <td><?php echo $panel["panel_currency"] ?></td>
                             <td><?php echo $panel["panel_price"] ?></td>
                             <td><?php echo $panel["panel_status"] ?></td>

                             <td  nowrap=""><?php echo $panel["panel_created"] ?></td>
                                           <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button class="btn btn-secondary dropdown-toggle " type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">İşlemler <span class="caret"></span></button>
                     <ul class="dropdown-menu">
     <li  ><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?=base_url("admin/child-panels/cancel/".$panel["id"])?>" >İptal ve İade Et</a></li>

                     </ul>
                   </div>
                 </td>
                          </tr>
                        <?php endforeach; ?>
                            </tbody>



                        </table>
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
