<?= view('admin/yeni_admin/static/header'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <div class="container-fluid px-sm-5">
        <?php if ($success): ?>
            <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">
                <form class="form-inline" action="" method="get">
                    <div class="input-group">
                        <input type="text" name="search" id="siparis_ara" class="form-control" value="" placeholder="Kullanıcı Ara...">
                        <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
							<option value="order_id">ID</option>
							<option value="order_url">Kullanıcı Adı</option>
							<option value="username">E-Mail</option>
							<option value="username">Telefon No</option>
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
                                <div class="card-header-title">Müşteriler</div>
								<button class="navbar-toggler float-end" style="margin-top:10px;" type="button" data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav" aria-expanded="false" aria-label="Toggle navigation">
								<i class="fas fa-bars text-white"></i>
    </button>
                                <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
									<button class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto"  data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_user"><i class="fas fa-plus"></i> Yeni Müşteri Oluştur</button>
									<button class="btn-glycon btn-glycon-primary btn-card-header w-100 w-sm-auto"  data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="alert_user"><i class="fas fa-plus"></i> Bildirim Gönder</button>
                                <a class="btn-glycon btn-glycon-danger text-center btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mobile-overflow p-0">
                        <table class="table border table-striped m-0" id="clients">
                            <thead class="card-glycon text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Kullanıcı Adı</th>
                                <th scope="col">E-Mail</th>
                                <th scope="col">Telefon Numarası</th>
                                <th class="text-center" scope="col">Bakiye</th>
                                <th class="text-center" scope="col">Harcanan</th>
                                <th class="text-center" scope="col">Kayıt Tarihi</th>
                                <th class="text-center" scope="col">Son Giriş</th>
                                <th class="text-center" scope="col">Durum</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody class="icon-animation">
                           
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
        var oTable = $('#clients').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?= base_url('admin/getallClients')?>",
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
                null,
                null,
                null,
                null,
                null,
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    });
</script>
