<?= view('admin/yeni_admin/static/header'); ?>


<div class="container-fluid px-sm-5">
	<div class="row">
		<div class="col-md-2">
			<ul class="nav flex-column">
                <?php foreach($menuList as $menuName => $menuLink ): ?>
				<li class="nav-item">
					<a class="nav-link <?php if( $route == $menuLink ): echo "active"; endif; ?>" aria-current="page" href="<?=base_url("admin/appearance/".$menuLink)?>"><?=$menuName?></a>
				</li>
            <?php endforeach; ?>


			</ul>
		</div>
      <?php   if( $access ):
            echo view('admin/yeni_admin/appearance/'.$route);
          else:
            echo view('admin/yeni_admin/appearance/access');
          endif;
    ?>
	</div>
</div>
<?= view('admin/yeni_admin/static/footer'); ?>