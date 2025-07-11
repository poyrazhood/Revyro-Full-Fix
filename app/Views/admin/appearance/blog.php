<?php if( !route(4) ): ?>
<div class="col-md-8">
              <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
       <ul class="nav nav-tabs">
   <a href="javascript:;" onclick="showMe('gizlebeni');" ><li class="p-b"><button class="btn btn-default">Yeni Blog Yazısı Oluştur</button></li></a></ul>
        <table class="table">
            <thead>
            <tr>
                <th>Blog Adı</th>
                <th>Yayın Tarihi</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
         <?php foreach($postList as $post): ?>

                            <tr>
                <td>
                   <?php echo $post["blog_title"]; ?> <a href="/blog/<?php echo $post["url"]; ?> " class="order-link" target="_blank">
                                <span class="fa fa-external-link"></span>
                            </a> 

                </td>
                   <td>
                   <?php echo $post["blog_created"]; ?>

                </td>
                
                <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">İşlemler <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                      
                         <li><a href="<?php echo base_url('admin/appearance/blog/edit/'.$post["id"]) ?>">Düzenle</a></li>
                     
                         <li><a href="<?php echo base_url('admin/appearance/blog/delete/'.$post["id"]) ?>">Sil</a></li>

               
                     </ul>
                   </div>
                 </td>

            </tr> 

         <?php endforeach; ?>           
          
                        </tbody>
        </table>
      </tbody>
   </table>
<br>
          <div class="panel panel-default" id="gizlebeni" style="display: none;">
    <div class="panel-body">

         <form action="<?php echo base_url('admin/appearance/blog') ?>" method="post" enctype="multipart/form-data">
             
                     <div class="form-group relative">
          <div class="row">
            <div class="col-md-10">
              <label for="preferenceLogo" class="control-label">Blog Resmi</label>
              <input type="file" name="logo" id="preferenceLogo">
                        <p class="help-block">800 x 450px önerilen boyutlardır</p>
            </div>
           
          </div>
        </div>
             
        <div class="form-group">
          <label for="" class="control-label">Blog Adı</label>
          <input type="text" class="form-control" name="name">
        </div>

            <div class="form-group">
               <label class="control-label">Blog İçeriği</label>
               <textarea class="form-control" id="summernote" rows="5" name="content" placeholder=""></textarea>
            </div>	  

            <button type="submit" class="btn btn-primary">Blog'u Yayınla</button>
                <a href="<?= base_url()?>/admin/appearance/blog" class="btn btn-default">Geri</a>
         </form>

</div> </div>



</div>

<script type="text/javascript">
// göster/gizle
function showMe(blockId) {
     if ( document.getElementById(blockId).style.display == 'none' ) {
          document.getElementById(blockId).style.display = ''; }
else if ( document.getElementById(blockId).style.display == '' ) {
          document.getElementById(blockId).style.display = 'none'; }
}
</script>

<?php elseif( route(4) == "edit" ): ?>
<div class="col-md-8">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
          <div class="panel panel-default">
    <div class="panel-body">

         <form action="<?php echo base_url('admin/appearance/blog/edit/'.route(5)) ?>" method="post" enctype="multipart/form-data">
             
                     <div class="form-group relative">
          <div class="row">
            <div class="col-md-10">
              <label for="preferenceLogo" class="control-label">Blog Resmi</label>
              <input type="file" name="logo" id="preferenceLogo">
                        <p class="help-block">800 x 450px önerilen boyutlardır</p>
            </div>
            <div class="col-md-2">
              <?php if( $post["blog_image"] ):  ?>
                <div class="setting-block__image">
                      <img class="img-thumbnail" src="<?=base_url('assets/uploads/blog/'.$post["blog_image"])?>">
                    <div class="setting-block__image-remove">
                      <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=base_url("admin/appearance/blog/delete-image")?>"><span class="fa fa-remove"></span></a>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
             
        <div class="form-group ">
          <label for="" class="control-label">Blog Adı</label>
          <input type="text" class="form-control" name="name" value="<?=$post["blog_title"]?>">
        </div>

            <div class="form-group">
               <label class="control-label">Blog İçeriği</label>
               <textarea class="form-control" id="summernote" rows="5" name="content" placeholder=""><?php echo $post["blog_content"]; ?></textarea>
            </div>	  

            <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="/admin/appearance/blog" class="btn btn-default">Geri</a>
         </form>

</div> </div> </div> 


<?php endif; ?>
 