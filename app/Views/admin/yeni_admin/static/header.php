<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/uploads/sites/' . $settings['favicon']) ?>" type="image/x-icon" />

    <!-- <link rel='stylesheet' href='<?= base_url('assets') ?>/css/admin/bootstrap.css'> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/yeni_admin/css/style.css">
    <script src="https://kit.fontawesome.com/f382d823ab.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='<?= base_url('assets') ?>/css/admin/toastDemo.css'>
    <link rel='stylesheet' href='<?= base_url('assets') ?>//js/datepicker/css/bootstrap-datepicker3.min.css'>
    <link rel='stylesheet' href='<?= base_url('assets') ?>/css/admin/tinytoggle.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link rel='stylesheet' href='<?= base_url('assets') ?>/css/admin/toastDemo.css'>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/jfk2x7w8fmses3o7a303ckj8fy1f8wpcydcmki4x8fothoe9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.3/codemirror.min.css" integrity="sha512-6sALqOPMrNSc+1p5xOhPwGIzs6kIlST+9oGWlI4Wwcbj1saaX9J3uzO3Vub016dmHV7hM+bMi/rfXLiF5DNIZg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/mode/xml/xml.min.js"></script>

    <script>
        tinymce.init({
            selector: 'textarea#tinymce',
            plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export code',
            menubar: 'file edit view insert format tools table tc help',
            toolbar: 'undo redo | code | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed link codesample | a11ycheck ltr rtl | showcomments addcomment',

        });
    </script>
    <style>
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 60%;
                margin: 1.75rem auto;
            }
        }
    </style>
    <title>Yönetim Paneli | Glycon V2 SMM Panel Scripti</title>
</head>

<body class="bg-light">

    <div class="topbar text-white">
        <div class="container-fluid px-5 align-items-center">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <a href="<?= base_url('') ?>/admin"><img style="filter: invert(1%) sepia(1%) saturate(1%) hue-rotate(1deg) brightness(1000%) contrast(100%);" class="img-fluid glycon heartbeat" src="<?= base_url('') ?>/image/glycon.png"></a>
                            <a href="javascript::void(0)" onclick="guncelleme_denetle();" class="mx-2 my-2 text-end topbar-a text-success"></i>
                                Güncelleme Denetle</a>

                        </div>
                    </div>
                </div>
                <div class="col-md-8 d-none d-sm-block">
                    <div class="d-flex float-end align-items-center">
                        <div class="ust d-block header-set">
                            <span class="mx-5"><strong><?= isset($user['username']) ? $user['username'] : "Admin" ?></strong></span>
                            <div class="d-block my-2">

                                <a href="<?= base_url('') ?>" class="mx-2 my-2 text-end topbar-a text-primary"><i class="fas fa-eye"></i>
                                    Siteyi Görüntüle</a>
                                <a href="<?= base_url('') ?>/logout" class="mx-2 my-2 text-end topbar-a text-danger"><i class="fas fa-power-off"></i>
                                    Çıkış Yap</a>
                            </div>
                        </div>
                        <img src="https://wisecp.glycon.com.tr/resources/uploads/admin/profile/default.jpg" class="img-fluid rounded-circle avatar">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-navbar mb-3 shadow d-sm-block">
        <div class="container-fluid px-sm-5">
            <button class="navbar-toggler w-100 bg-primary bg-gradient text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                Menü
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url("admin") ?>"><i class="fas fa-home"></i>
                            Ana Sayfa</a>
                    </li>
                    <?php if ($user['access']['orders']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/orders') ?>"><i class="fas fa-boxes"></i>
                                Siparişler</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['access']['subscriptions']) :

                        $a1 = countRow(['table' => 'services', 'where' => ['service_package' => 11]]);
                        $a2 = countRow(['table' => 'services', 'where' => ['service_package' => 12]]);
                        $a3 = countRow(['table' => 'services', 'where' => ['service_package' => 13]]);
                        $a4 = countRow(['table' => 'services', 'where' => ['service_package' => 14]]);
                        $a5 = countRow(['table' => 'services', 'where' => ['service_package' => 15]]); ?>
                        <?php if ($a1 > 0 || $a2 > 0 || $a3 > 0 || $a4 > 0 || $a5 > 0) : ?>
                            <li class='nav-item border-right'><a class="nav-link" href='<?php echo base_url('admin/subscriptions') ?>'><i class="fas fa-clock"></i> Abonelikler</a>
                            </li>
                    <?php endif;
                    endif; ?>
                    <?php if ($user['access']['dripfeed']) : ?>
                        <?php if (countRow(['table' => 'services', 'where' => ['service_dripfeed' => 2]]) > 0) :    ?>
                            <li class='nav-item border-right'><a class="nav-link" href='<?php echo base_url('admin/dripfeeds') ?>'> Drip-feed</a></li>
                    <?php endif;
                    endif; ?>
                    <?php if ($user['access']['users']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/clients') ?>"><i class="fas fa-users"></i>
                                Müşteriler</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['access']['services']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/services') ?>"><i class="fas fa-stream"></i>
                                Servisler</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['access']['tasks']) : ?>
                        <?php if (countRow(['table' => 'tasks'])) : ?>
                            <li class="nav-item border-right">
                                <a class="nav-link" href="<?= base_url('admin/tasks') ?>"><i class="fas fa-tasks"></i>
                                    Görevler <?php if (countRow(['table' => 'tasks', 'where' => ['task_status' => 'pending']])) : ?>
                                        <span><?= countRow(['table' => 'tasks', 'where' => ['task_status' => 'pending']]); ?><?php endif; ?></span></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($user['access']['reports']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/reports') ?>"><i class="fas fa-chart-line"></i>
                                <strong>G</strong>Analytics</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['access']['payments']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/payments') ?>"><i class="fas fa-coins"></i>
                                Ödemeler <?php if (countRow(['table' => 'payments', 'where' => ['payment_method' => 7, 'payment_status' => 1]])) : ?>
                                    <span><?= countRow(['table' => 'payments', 'where' => ['payment_method' => 7, 'payment_status' => 1]]); ?></span> <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['access']['tickets']) : ?>
                        <li class="nav-item border-right">
                            <a class="nav-link" href="<?= base_url('admin/tickets') ?>"><i class="fas fa-headset"></i>
                                Destek
                                Sistemi<?php if (countRow(['table' => 'tickets', 'where' => ['status' => 'pending']])) : ?>
                                <span><?= countRow(['table' => 'tickets', 'where' => ['status' => 'pending']]); ?></span><?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($settings['panel_selling'] == 2 || countRow(['table' => 'child_panels', 'where' => ['panel_status' => 'active']])) : ?>
                        <li class="nav-item border-right"><a class="nav-link" href='<?php echo base_url('admin/child-panels') ?>'>Child Panels

                                <?php if (countRow(['table' => 'child_panels', 'where' => ['panel_status' => 'pending']])) : ?>

                                    <span><?= countRow(['table' => 'child_panels', 'where' => ['panel_status' => 'pending']]); ?></span>

                                <?php endif; ?>
                            </a></li>
                    <?php endif; ?>
                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url('admin/appearance') ?>"><i class="fab fa-atlassian"></i> Görünüm</a>
                    </li>

                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cogs"></i> Ayarlar</a>
                    </li>
                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url('admin/mail-sablon') ?>"><i class="fas fa-envelope-open"></i> Mail Şablonu</a>
                    </li>
                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url('admin/popup') ?>"><i class="fas fa-fire"></i> Popup</a>
                    </li>
                    <?php if ($user['access']['logs']) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/logs') ?>"><i class="fas fa-book-open"></i> Loglar</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item border-right">
                        <a class="nav-link" aria-current="page" href="<?= base_url('admin/update-log') ?>"><i class="fas fa-check"></i> </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    if (isset($_GET['alertclose']) && $_GET['alertclose'] != NULL && $_GET['alertclose'] == "1") {

        $s_model = model("settings");
        $up = $s_model->protect(false)->set("alertclosetime", date('Y-m-d H:i:s'))->update();
    }


    $kaynak = file_get_contents("https://i62.net/classes/alert.php");
    if ($kaynak == true) {
        $data = json_decode($kaynak, true);


        if ($data['0']['date'] >= $settings["alertclosetime"]) {
            if ($data['0']['alert'] == "1") {
    ?>
                <script>
                    alert('<?= $data['0']['alert_data'] ?>')
                </script>
            <?php
            }
            ?>
            <form action="" method="get">
                <div class="container-fluid px-sm-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <button type="submit" name="alertclose" value="1" class="close" style="border-color: transparent;background: transparent;">×</button>
                                <?= $data['0']['data'] ?> <?php if ($data['0']['ozelactive'] == 1) { ?><a href="//<?= $_SERVER['SERVER_NAME'] . $data['0']['ozelveri'] ?>"><?= $data['0']['ozelveri_baslik'] ?></a><?php } ?> <?php if ($data['0']['ozelactive'] == 2) { ?><a href="//<?= $data['0']['ozelveri'] ?>"><?= $data['0']['ozelveri_baslik'] ?></a><?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    <?php
        }
    }
