<?php if( !route(4) ): ?>
		<div class="col-md-10">
			<div class="card mt-3 mt-sm-0 apperance-hr">
				<div class="card-header">
					Sayfalar
				</div>
				<div class="card-body">
                         <?php foreach($pageList as $page): ?>
					<div class="row">
						<div class="col-6">
							<div class="apperance-left text-start">
								<?php echo $page["page_name"]; ?>
							</div>
						</div>
						<div class="col-6">
							<div class="apperance-right text-end">
								<a href="<?php echo base_url('admin/appearance/pages/edit/'.$page["page_get"]) ?>" class="btn btn-primary px-3 py-0">Düzenle</a>
							</div>
						</div>
					</div>
					<hr>
                    <?php endforeach; ?>

				</div>
			</div>
		</div>
<?php elseif( route(4) == "edit" ): ?>
<div class="col-md-8">
   <div class="panel panel-default">
      <div class="panel-body">
         <form action="<?php echo base_url('admin/appearance/pages/edit/'.route(5)) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
               <label class="control-label">Sayfa Adı</label>
               <input type="text" class="form-control" readonly value="<?=$page["page_name"];?>">
            </div>
            <div class="form-group">
               <label class="control-label">İçerik</label>
               <textarea class="form-control" id="tinymce" rows="5" name="content" placeholder=""><?php echo $page["page_content"]; ?></textarea>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Güncelle</button>
              <a href="/admin/appearance/pages" class="btn btn-default">Geri</a>
         </form>
      </div>
   </div>
</div>
<?php endif; ?>