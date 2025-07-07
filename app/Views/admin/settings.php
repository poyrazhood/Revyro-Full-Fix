<?= view('admin/static/header'); ?>
<div class="container">
  <div class="row">
    <?php if( route(2) !== "subject" && route(2) !== "bank-accounts"):  ?>
          <div class="col-md-2 col-md-offset-1">
            <ul class="nav nav-pills nav-stacked p-b">
              <?php foreach($menuList as $menuName => $menuLink ): ?>
                <li class="settings_menus <?php if( $route == $menuLink): echo "active"; endif; ?>"><a href="<?=base_url("admin/settings/".$menuLink)?>"><?=$menuName?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
    <?php  endif;
          if( $access ):
            echo view('admin/settings/'.$route);
          else:
            echo view('admin/settings/access');
          endif;
    ?>
  </div>
</div>

<?= view('admin/static/footer'); ?>
