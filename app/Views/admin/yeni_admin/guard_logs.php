<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <div class="container-fluid px-sm-5">
        <div class="row">
            <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">
                <form class="form-inline" action="<?= base_url("admin/guard_logs") ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="<?= $search_word ?>"
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
                                <th scope="col">Yetkili</th>
                                <th class="text-center" scope="col">Olay</th>
                                <th class="text-center" scope="col">Tarih</th>
								<th scope="col">Detaylı IP</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>

                            <tbody></tbody>
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
            "ajax": "<?= base_url('admin/getallGuardLogs')?>",
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