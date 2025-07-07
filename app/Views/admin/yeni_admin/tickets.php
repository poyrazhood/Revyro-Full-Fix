<?= view('admin/yeni_admin/static/header'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script>

        tinymce.init({
            selector: 'textarea',
        plugins: [
          'a11ychecker','advlist','quickbars','blocks','advcode','advtable','autolink','checklist','export',
          'lists','link autolink','charmap','preview','anchor','searchreplace','visualblocks',
          'powerpaste','fullscreen','formatpainter','insertdatetime','media','image','code','table','wordcount'
        ],
        
        toolbar: 'undo redo | link image formatpainter casechange | bold italic forecolor  backcolor color | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code'
      });
    </script>
<div class="container-fluid px-sm-5">
	<div class="row">
		<div class="col-md-12 collapse ms-auto mb-3" id="search">
                
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="" id="siparis_ara" placeholder="Ticket ara...">
                        <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">

					</span>
                    </div>
                
            </div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12 navbar-expand-lg">
							<div class="card-header-title">Destek Talepleri</div>
							<button class="navbar-toggler float-end" style="margin-top:10px;" type="button" data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav" aria-expanded="false" aria-label="Toggle navigation">
									<i class="fas fa-bars text-white"></i>
    							</button>
							<div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
								<button class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto text-center" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_ticket"><i class="fas fa-plus"></i> Yeni Oluştur</button>
							<a href="<?= base_url("admin/tickets?search=unread")?>" class="btn-glycon btn-glycon-warning btn-card-header w-100 w-sm-auto text-center"><i class="fas fa-plus"></i> Okunmayanlar</a>
							<a href="<?= base_url("admin/tickets?status=pending")?>" class="btn-glycon btn-glycon-danger btn-card-header w-100 w-sm-auto text-center"><i class="fas fa-plus"></i> Cevap Bekleyenler</a>
							<a href="<?= base_url("admin/tickets")?>" class="btn-glycon btn-glycon-info btn-card-header w-100 w-sm-auto text-center"><i class="fas fa-plus"></i> Tümü</a>
								<a class="btn-glycon btn-glycon-secondary text-center btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#search" role="button" aria-expanded="false"
                                   aria-controls="search">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0" id="ticket">
						<thead class="card-glycon text-white">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Müşteri</th>
								<th scope="col">Konu</th>
								<th scope="col" class="text-center">Durum</th>
								<th scope="col">Oluşturulma Tarihi</th>
								<th scope="col">Güncellenme Tarihi</th>
								<th></th>
								<th></th>
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
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <?php 
    $url = base_url('admin/getAllTickets');
    if(isset($_GET['status'])){
        
    $url = base_url('admin/getAllTickets?durum='.$_GET['status']);
    }elseif(isset($_GET['search'])){
    $url = base_url('admin/getAllTickets?durum='.$_GET['search']);
        
    }
    ?>
    
<script>

    $(document).ready(function () {
        var oTable = $('#ticket').DataTable({

            "order": [[0, "desc"]],
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?= $url?>",
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
            ]
        });
        $('#siparis_ara').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    });
</script>
<?= view('admin/yeni_admin/static/footer'); ?>