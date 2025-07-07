<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
	.action-block{
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
            <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">
                <form class="form-inline" action="<?= base_url("admin/logs") ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="search" id="siparis_ara" class="form-control" value="<?= $search_word ?>"
                               placeholder="Log Ara">
                        <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
							<option value="username" <?php if ($search_where == "username"): echo 'selected'; endif; ?>>Kullanıcı Adı</option>
							<option value="action" <?php if ($search_where == "action"): echo 'selected'; endif; ?>>IP Adresi</option>
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
                            <div class="col-md-12">
                                <div class="card-header-title">Log Kayıtları</div>
                                <a class="btn-glycon btn-glycon-danger btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
								<a href="<?= base_url("admin/provider_logs") ?>" class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto">
                                    <i class="fas fa-search"></i> Sağlayıcı Logları
                                </a>
								<a href="<?= base_url("admin/guard_logs") ?>" class="btn-glycon btn-glycon-warning btn-card-header w-100 w-sm-auto">
                                    <i class="fas fa-search"></i> Guard Logları
                                </a>
								<a href="<?= base_url("admin/logs") ?>" class="btn-glycon btn-glycon-primary btn-card-header w-100 w-sm-auto">
                                    <i class="fas fa-search"></i> Sistem Logları
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mobile-overflow p-0">
                        <table class="table border table-striped m-0" id="logs">
                            <thead class="card-glycon text-white">
                            <tr>
                                <th scope="col">Kullanıcı Adı</th>
                                <th scope="col">Detay</th>
                                <th class="text-center" scope="col">IP Adresi</th>
                                <th class="text-center" scope="col">Tarih</th>
                                <th class="text-center">İşlem</th>
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
        var oTable = $('#logs').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?= base_url('admin/getallLogs')?>",
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
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    });
</script>