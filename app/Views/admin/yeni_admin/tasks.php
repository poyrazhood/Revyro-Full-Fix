<?= view('admin/yeni_admin/static/header'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <style>
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
            <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">
                
                    <div class="input-group">
                        <input type="text" name="search" id="siparis_ara" class="form-control" value="" placeholder="Görev Ara">
                        
                    </div>
                
            </div>
            <div class="col-md-12">
                    <?php if ($success): ?>
        <div class="alert alert-success "><?php echo $successText; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger "><?php echo $errorText; ?></div>
    <?php endif; ?>
                <div class="card">
                    <div class="card-header p-0">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="card-header-title">Tasks Kayıtları</div>
								<button class="navbar-toggler float-end" style="margin-top:10px;" type="button" data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav" aria-expanded="false" aria-label="Toggle navigation">
								<i class="fas fa-bars text-white"></i>
    </button>
                                <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
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
                        <table class="table border table-striped m-0" id="tasks">
                            <thead class="card-glycon text-white">
                            <tr>
                                <th>Task ID</th>
                                <th>Sipariş ID</th>
                                <th>Kullanıcı</th>
                                <th>Servis</th>
                                <th>Link</th>
                                <th>Başlangıç</th>
                                <th>Miktar</th>
                                <th>Talep</th>
                                <th>Task Durumu</th>
                                <th>Task Tarihi</th>
                                <th class="dropdown-th"></th>
                            </tr>
                            </thead>
                            <tbody>
                            
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
        var oTable = $('#tasks').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?= base_url('admin/getAlltasks')?>",
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
                null,
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    });
</script>