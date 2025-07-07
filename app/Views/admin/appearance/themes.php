<div class="container">
  <div class="row">
<?php
$eye = ' <i class="fa fa-eye"></i>';
?>
<?php if( !route(4) || (route(4) == "active" && route(4)) ): ?>
<div class="col-md-2 col-md-offset-1">
            <ul class="nav nav-pills nav-stacked p-b">
                              <li class="settings_menus "><a href="<?=base_url("admin/appearance/pages")?>">Sayfalar</a></li>
                              <li class="settings_menus "><a href="<?=base_url("admin/appearance/news")?>">Duyurular</a></li>
                              <li class="settings_menus "><a href="<?=base_url("admin/appearance/blog")?>">Blog</a></li>
                              <li class="settings_menus "><a href="<?=base_url("admin/appearance/menu")?>">Menü</a></li>
                              <li class="settings_menus active"><a href="<?=base_url("admin/appearance/themes")?>">Temalar</a></li>
                              <li class="settings_menus "><a href="<?=base_url("admin/appearance/language")?>">Diller</a></li>
                               <li class="settings_menus "><a href="<?=base_url("admin/appearance/files")?>">Dosyalar</a></li>
                          </ul>
          </div>
<div class="container">
                <div class="row">
				<div class="col-lg-8">
                   <div class="settings-themes">

         <?php foreach($themes as $theme):
         
            $x = $theme['theme_dirname'];
            $yol = base_url("select-theme/$x");
            
         ?>
              <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="settings-themes__card settings-themes__card-active">
                                
                                <?php if( $settings["site_theme"] != $theme["theme_dirname"] ): ?>
                                <div class="settings-themes__card-preview" style="background-image: url(https://image.thum.io/get/<?=$yol?>?v=1)">
                                <?php endif;  
                                    
                                    if($_SESSION['theme']):
                                        
                                        $eye = ' <i class="fa fa-eye"></i>'; 
                                        
                                        endif;
                                    
                                    if( $settings["site_theme"] == $theme["theme_dirname"] ): echo '<div class="settings-themes__card-preview" style="background-image: url(https://image.thum.io/get/'.$yol.')"><span class="badge">Aktif'.$eye.'</span>'; endif; ?>  
                                    <?php if( $settings["site_theme"] != $theme["theme_dirname"] ): ?>
                  <div class="settings-themes__card--activate">
					  <a class="btn btn-success" href="<?php echo base_url('admin/appearance/themes/'.$theme["theme_dirname"]) ?>">Düzenle</a>                      
					  <a class="btn btn-primary" href="<?php echo base_url('admin/appearance/themes/active/'.$theme["theme_dirname"]) ?>">Aktif Et</a>                                        </div>
                  <?php endif; ?>
                                                                            
                                                   
                                                                    </div>
                                <div class="settings-themes__card-title">
                                    <?php echo $theme["theme_name"]; ?>                                    
                                    <a href="<?php echo base_url('select-theme/'.$theme["theme_dirname"]) ?>" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                                    <a href="<?php echo base_url('admin/appearance/themes/'.$theme["theme_dirname"]) ?>" class="btn btn-default btn-xs pull-right">Düzenle</a>
                                </div>
                                
                            </div>
                        </div>
         <?php endforeach; ?>
 </div></div>     
<?php elseif( route(4) && route(4) != "active"): ?>
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading edit-theme-title"><strong><?php echo $theme["theme_name"] ?></strong> Düzenleniyor</div>

        <div class="row">
          <div class="col-md-3 padding-md-right-null">

            <div class="panel-body edit-theme-body">
              <div class="twig-editor-block">
                <?php
                  $layouts  = [
                    "HTML"=>["header.twig","footer.twig","account.twig","addfunds.twig","api.twig",
                    "login.twig","signup.twig","neworder.twig","orders.twig","dripfeeds.twig","subscriptions.twig",
                    "services.twig","child-panels.twig","tickets.twig","viewticket.twig","blog.twig","blogpost.twig","verify.twig","affiliates.twig", 
                    "resetpassword.twig",
                    "terms.twig","faq.twig","404.twig"],
                    "CSS"=>["bootstrap.css","style.css"],
                    "JS"=>["bootstrap.js","script.js"]
                  ];
                foreach ($layouts as $style => $layout):
                  echo '<div class="twig-editor-list-title" data-toggle="collapse" href="#folder_'.$style.'"><span class="fa fa-folder-open"></span>'.$style.'</div><ul class="twig-editor-list collapse in" id="folder_'.$style.'">';
                  foreach ($layouts[$style] as $layout) :
                    if( $lyt == $layout ):
                      $active = ' class="active file-modified" ';
                    else:
                      $active = '';
                    endif;
                    echo '
                      <li '. $active .'><a href="'.base_url('admin/appearance/themes/'.$theme["theme_dirname"]).'?file='.$layout.'">'.$layout.'</a></li>';
                  endforeach;
                  echo '</ul>';
                endforeach;
              ?>
              </div>

            </div>
          </div>
          <div class="col-md-9 padding-md-left-null edit-theme__block-editor">
            <?php if( !$lyt ): ?>
              <div class="panel-body">
                <div class="row">
                   <div class="col-md-12">
                    <div class="theme-edit-block">
                      <div class="alert alert-info" role="alert">
                     Döküman yakında eklenecektir...<br>
                     Sol taraftan düzenlemek istediğiniz sayfanın tema kodlarına ulaşabilirsiniz.
                      </div>
                    </div>
                  </div>
                  </div>
              </div>
            <?php else: ?>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/codemirror.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/mode/xml/xml.min.js"></script>
                  
                  <div id="fullscreen">

               <div class="panel-body">

                <?php
                $file = fopen($fn, "r");
                $size = filesize($fn);
                $text = fread($file, $size);
                $text = str_replace("<","&lt;",$text);
                $text = str_replace(">","&gt;",$text);
                $text = str_replace('"',"&quot;",$text);
                fclose($file);
                ?>

                <div class="row">
                    <div class="col-md-8">
                      <strong class="edit-theme-filename"><?=$dir."/".$lyt?></strong>
                        </div>
                        <div class="col-md-4 text-right">
                                    <a class="btn btn-xs btn-default fullScreenButton">
                                        <span class="glyphicon glyphicon-fullscreen"></span>
                                        Tam Ekran Düzenle </a>
                                </div>
                  </div>
           

                <base_url action="<?php echo base_url("admin/appearance/themes/".$theme["theme_dirname"]."?file=".$lyt) ?>" method="post" class="twig-editor__base_url">
                    
                  <textarea id="code" name="code" class="codemirror-textarea"><?=$text;?></textarea>
                  <div class="edit-theme-body-buttons text-right">
                    <button class="btn btn-primary click">Güncelle</button>
                  </div>
                </base_url>

              </div>
            <?php endif; ?>
          </div>
        </div>

    </div>
  </div>


<?php endif; ?>

