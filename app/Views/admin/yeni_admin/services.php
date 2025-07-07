<?= view('admin/yeni_admin/static/header'); ?>
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

    .loading {
        margin: 0;
    }

    .loading img {
        margin-top: 10px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50px;
    }

    .services-import__scroll-wrap {
        overflow-x: auto;
        max-height: 550px
    }
</style>
<script>
    window.icerik_sayi = [];
</script>
<div class="container-fluid px-sm-5">
    <?php if ($success): ?>
        <div class="alert alert-success "><?php echo $successText; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger "><?php echo $errorText; ?></div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">

                <div class="input-group">
                    <input type="text" id="search" name="search" class="form-control" value="" placeholder="Servisleri ara...">
                </div>

        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-0">
                    <div class="row align-items-center">
                        <div class="col-md-12 navbar-expand-lg">
                            <div class="card-header-title">Servisler</div>
                            <button class="navbar-toggler float-end" style="margin-top:10px;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-bars text-white"></i>
                            </button>
                            <div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
                                <a href="<?= base_url("admin/new-service") ?>"
                                   class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto"><i
                                            class="fas fa-plus"></i> Yeni Servis Oluştur</a>
                                <a href="#" class="btn-glycon btn-glycon-primary btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_category"><i
                                            class="fas fa-plus"></i> Yeni Kategori Oluştur</a>
                                <a href="#" class="btn-glycon btn-glycon-info btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_subscriptions"><i
                                            class="fas fa-plus"></i> Yeni Abonelik Oluştur</a>
                                <a href="#" class="btn-glycon btn-glycon-warning btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="import_services"><i
                                            class="fas fa-plus"></i> Toplu Servis Ekle</a>
                                <a class="btn-glycon btn-glycon-danger btn-card-header w-100 w-sm-auto"
                                   data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    <i class="fas fa-search"></i> Gelişmiş Arama
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="loading" style="display:none">
                    <img src="<?= base_url("assets/yeni_admin/loading.jpg") ?>" alt="">
                </div>
                <div id="services_ajax">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>İşlemi Onaylıyor Musunuz?</h4>
                <div align="center">
                    <a class="btn btn-primary" href="" id="confirmYes">Evet</a>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Hayır</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in modal-fullscreen-xl" id="modalDivDuzenle" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <i data-bs-dismiss="modal" aria-label="Close" class="fas fa-times text-light"
                   style="cursor:pointer;"></i>
            </div>
            <div class="modal-body" id="modalContentServices">

            </div>

        </div>
    </div>
</div>

<?= view('admin/yeni_admin/static/footer'); ?>
<script>

    function servis_sil() {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Poof! Your imaginary file has been deleted!", {
                        icon: "success",
                    });
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
    }

    $(document).ready(function () {
        $('.loading').show();
        $.get("<?= base_url("admin/services-ajax")?>", {}, function (data) {
            $('.loading').hide();
            $('#services_ajax').html(data)
        });
    });

    function icerik_cek() {
        $.ajax({
            url: window.url,
            type: "get",
            success: function (data) {

                $(".Servicecategory-" + window.bol[1]).html(data);
                window.degissayi = window.degissayi + 1;
                if(window.degissayi < window.katsayi){
                window.bol = window.icerik_sayi[window.degissayi].split("|");
                window.url = "<?= base_url('admin/gizle-ajax')?>?category=" + window.bol[0];
                icerik_cek();
                }
            },
            error: function () {
                connectionError();
            }
        });
        /* $.get(url, {}, function (data) {
            console.log(data);
        }); */
    }

    $("#search").on("keyup", function() {
    var value = $(this).val();
    $("table tr").each(function(index) {
        if (index !== 0) {

            $row = $(this);

            var id = $(this).data('id');

            if (id.indexOf("service-"+value) !== 0) {
                $row.hide();
            }
            else {
                $row.show();
            }
        }
    });
});
</script>
