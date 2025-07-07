<!DOCTYPE html>
<html lang='tr'>
<head>
<base href='<?=base_url()?>'>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title><?= $title ?> </title>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
<![endif]-->  
<link href='<?= base_url('assets')?>/css/admin/custom.css' rel='stylesheet'>
<link rel='stylesheet' href='<?= base_url('assets')?>/css/admin/bootstrap.css'>
<link rel='stylesheet' href='<?= base_url('assets')?>/css/admin/style.css'>
<link rel='stylesheet' href='<?= base_url('assets')?>/css/admin/toastDemo.css'>
<link rel='stylesheet' href='<?= base_url('assets')?>//js/datepicker/css/bootstrap-datepicker3.min.css'>
<link rel='stylesheet' href='<?= base_url('assets')?>/css/admin/tinytoggle.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css'>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">

<script>

        tinymce.init({
            selector: 'textarea',
            plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export code',
          menubar: 'file edit view insert format tools table tc help',
          toolbar: 'undo redo | code | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed link codesample | a11ycheck ltr rtl | showcomments addcomment',

      });
</script>

</head>
<body class='<?php if($user['admin_theme'] == 2){ echo 'dark-mode'; } ?>'>
<nav  class='navbar navbar-fixed-top navbar-default'>
<div class='container-fluid'>
<div class='navbar-header'>
<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-navbar-collapse'>
<span class='sr-only'>Toggle navigation</span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
</button>
</div>
<div class='collapse navbar-collapse' data-nav='navbar-priority' id='bs-navbar-collapse'>
<ul id='navResponsive' class='nav navbar-nav navbar-left-block'>
<?php if( $user['access']['admin_access']): ?>
<?php if( $user['access']['users'] ): ?>
<li class='<?php if( $route == 'clients' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/clients') ?>'>Kullanıcılar</a></li>
<?php endif; ?>  
<?php if( $user['access']['orders'] ): ?>    
<li class='<?php if( $route == 'orders' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/orders') ?>'>Siparişler</a></li>
<?php endif; ?>



<?php if( $user['access']['tasks'] ): ?>
<?php if( countRow(['table'=>'tasks'])): ?>
<li class='<?php if( $route == 'tasks' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/tasks') ?>'>Tasks
<?php if( countRow(['table'=>'tasks','where'=>['task_status'=>'pending']]) ): ?>
<span class='badge' style='background-color: #6d47bb'><?=countRow(['table'=>'tasks','where'=>['task_status'=>'pending']]);?></span>
<?php endif; ?>
</a></li>
<?php endif; ?>
<?php endif; ?>


<?php if( $user['access']['services'] ): ?>    
<li class='<?php if( $route == 'services' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/services') ?>'>Servisler</a></li>
<?php endif; ?>  
<?php if( $user['access']['payments'] ): ?>
<li class='<?php if( $route == 'payments' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/payments') ?>'> Ödemeler <?php if(countRow(['table'=>'payments','where'=>['payment_method'=>7,'payment_status'=>1]])): ?><span class='badge' style='background-color: #6d47bb'><?=countRow(['table'=>'payments','where'=>['payment_method'=>7,'payment_status'=>1]]);?></span> <?php endif; ?></a></li>
<?php endif; ?>     
<?php if( $user['access']['tickets'] ): ?>       
<li class='<?php if( $route == 'tickets' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/tickets') ?>'>Destek <?php if( countRow(['table'=>'tickets','where'=>['client_new'=>2]]) ): ?> <span class='badge' style='background-color: #6d47bb'><?=countRow(['table'=>'tickets','where'=>['client_new'=>2]]);?></span><?php endif; ?> </a></li>
<?php endif; ?>

<?php if( $settings['panel_selling'] == 2 || countRow(['table'=>'child_panels','where'=>['panel_status'=>'active']])): ?>
<li class='<?php if( $route == 'child-panels' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/child-panels') ?>'>Child Panels 

<?php if( countRow(['table'=>'child_panels','where'=>['panel_status'=>'pending']]) ): ?> 

<span class='badge' style='background-color: #6d47bb'><?=countRow(['table'=>'child_panels','where'=>['panel_status'=>'pending']]);?></span>

<?php endif; ?> 
</a></li> 
<?php endif; ?>
<?php if( $user['access']['reports'] ): ?>
<li class='<?php if( $route == 'reports' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/reports') ?>'>İstatistikler</a></li> 
<?php endif; ?>          
<li class='<?php if( $route == 'appearance' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/appearance') ?>'>Görünüm</a></li> 

<li class='<?php if( $route == 'settings' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/settings') ?>'>Ayarlar</a></li>

<?php if( $user['access']['logs'] ): ?>
<li class='<?php if( $route == 'logs' || $route == 'provider_logs' || $route == 'guard_logs' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/logs') ?>'>Loglar <?php if(countRow(['table'=>'guard_log'])): ?>
<span class='badge' style='background-color: #6d47bb'><?=countRow(['table'=>'guard_log']);?></span>
<?php endif; ?></a></li> 
<?php endif; ?>
<?php endif; ?>

</ul>  
<ul id='w4' class='nav navbar-nav navbar-right'>
<?php 

if( $user['admin_theme'] == 2 ):
echo "<li><a href='#' data-toggle='modal' data-target='#managerModal'><img src='https://res.cloudinary.com/glycon/image/upload/v1600125263/wisecp-turkiye-nin-dijital-hizmetler-otomasyonu-2_ux7iwz.png' width='20' height='20'></a></li>";
else:
echo "<li><a href='#' data-toggle='modal' data-target='#managerModal'><img src='https://res.cloudinary.com/glycon/image/upload/v1600124924/Favicon_e3zlyq.png' width='20' height='20'></a></li>";
endif;

$e = $route;

if( $user['admin_theme'] == 2 ):
echo "<li class='nav-dark-mode'><a href='/admin?theme=1&refer=".$e."'><i class='fa fa-sun'></i></a></li>";
else:
echo "<li class='nav-dark-mode'><a href='/admin?theme=2&refer=".$e."'><i class='fa fa-moon'></i></a></li>";
endif;
?>

<li class='<?php if( $route == 'account' ): echo 'active'; endif; ?>'><a href='<?php echo base_url('admin/account') ?>'>Hesabım</a></li>      
<li><a href='<?php echo base_url('logout') ?>'>Çıkış Yap </a></li></ul>        
</div>
</div>
</nav>

