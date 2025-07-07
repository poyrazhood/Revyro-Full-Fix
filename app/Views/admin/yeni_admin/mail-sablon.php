<?= view('admin/yeni_admin/static/header'); ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="container-fluid px-sm-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card integration-p">
                <div class="card-header">
                    Mail Şablon Ayarı
                    <small>
                        Mail içeriği otomatik çekilir. Otomatik çekilcek yeri belirtmek için {mail_icerik_cek} kısaltmasını kullanın
                    </small>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <textarea id="tinymce" name="sablon"><?= $settings['mail_sablon'] ?></textarea>

                        <div class="edit-theme-body-buttons text-right">
                            <button class="btn btn-primary click">Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?= view('admin/yeni_admin/static/footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function () {
        $('#summernote').summernote();
    });
</script>