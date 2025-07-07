<style>
	.select2-w-100 span{
	width:100%!important;}
</style>
<?php if (!route(4)): ?>
<div class="col-md-10">
    <div class="card apperance-hr">
        <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-header-title">Menüler</div>
                    <a href="javascript:;" onclick="showMe('gizlebeni');"
                       class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i>
                        Yeni Menü Oluştur</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <strong>Herkese Açık</strong>
                </div>

                <div class="col-md-10">
                    <ul class="list-group list-group-flush border rounded">
                        <?php foreach ($public as $module) { ?>
                            <li class="list-group-item"><?= $module["name"] ?> <span class="float-end">
									<div class="form-check form-switch">
										<input class="form-check-input"
                                               type="checkbox" <?= $module["status"] == 2 ? 'checked' : '' ?> role="switch"
                                               onchange="window.location.href='<?= $module["status"] == 2 ? base_url('/admin/appearance/menu/public_false/' . $module["id"]) : base_url('/admin/appearance/menu/public_true/' . $module["id"]) ?>'"
                                               id="flexSwitchCheckDefault">
                                        <a href="<?php echo base_url('admin/appearance/menu/edit/'.$module["id"]) ?>"><i class="fas fa-edit"></i></a>       
                                        <?php if(($module["id"] != 2) && ($module["id"] != 5) && ($module["id"] != 3) && ($module["id"] != 4) && ($module["id"] != 6)){ ?><a style="margin-left: 0.3rem;" href="<?php echo base_url('admin/appearance/menu/delete/'.$module["id"]) ?>"><i class="fas fa-trash"></i></a><?php } ?>
									</div>
								</span></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-2">
                    <strong>Üyelere Özel</strong>
                </div>
                <div class="col-md-10">
                    <ul class="list-group list-group-flush border rounded">
                        <?php foreach ($public as $module) { ?>
                            <li class="list-group-item"><?= $module["name"] ?> <span class="float-end">
									<div class="form-check form-switch">
										<input class="form-check-input"
                                               type="checkbox" <?= $module["public"] == 2 ? 'checked' : '' ?> role="switch"
                                               onchange="window.location.href='<?= $module["public"] == 2 ? base_url('/admin/appearance/menu/nopublic_false/' . $module["id"]) : base_url('/admin/appearance/menu/nopublic_true/' . $module["id"]) ?>'"
                                               id="flexSwitchCheckDefault">
                                        <a href="<?php echo base_url('admin/appearance/menu/edit/'.$module["id"]) ?>"><i class="fas fa-edit"></i></a>
                                        <?php if(($module["id"] != 2) && ($module["id"] != 5) && ($module["id"] != 3) && ($module["id"] != 4) && ($module["id"] != 6)){ ?><a style="margin-left: 0.3rem;" href="<?php echo base_url('admin/appearance/menu/delete/'.$module["id"]) ?>"><i class="fas fa-trash"></i></a><?php } ?>
									</div>
								</span></li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2"></div>
<div class="col-md-10">
    <div class="panel panel-default" id="gizlebeni" style="display: none;">
        <div class="card">
            <div class="card-body">

                <form action="<?php echo base_url('admin/appearance/menu/add') ?>" method="post"
                      enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="menu_name" class="control-label">Menü İsmi</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name">
                    </div>

                    <div class="form-group select2-w-100">
                        <label for="menu_link" class="control-label">Menü Linki</label>
                        <select name="menu_link" id="menu_link" class="form-select select2 w-100">
                            <option value="<?= base_url("api/v2") ?>">API</option>
                            <option value="<?= base_url("terms") ?>">Kullanıcı Sözleşmesi</option>
                            <option value="<?= base_url("contact") ?>">İletişim</option>
                            <option value="-1" id="secilmemis">Özel Gir</option>
                        </select>
                    </div>

                    <div class="form-group" id="ozel_link" style="display:none;">
                        <label for="menu_link" class="control-label">Link</label>
                        <input type="text" class="form-control" name="menu_link_ozel" id="menu_link_ozel">
                    </div>

                    <div class="form-group">
                        <label for="menu_link" class="control-label">İkon</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_link">
                    </div>

                    <div class="form-group">
                        <label for="menu_tip" class="control-label">Tip</label>
                        <select name="menu_tip" id="" class="form-select">
                            <option value="2">Herkese Açık</option>
                            <option value="1">Üyeye Özel</option>
                        </select>
                    </div>

                    <div class="form-group">

                        <button type="submit" class="btn btn-primary mt-2">Ekle</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?php elseif( route(4) == "edit" ): ?>
<div class="col-md-10">
    <div class="panel panel-default" id="gizlebeni">
    <div class="card">
  <div class="card-body">

   <form action="<?php echo base_url('admin/appearance/menu/edit/'.route(5)) ?>" method="post" enctype="multipart/form-data">

    <div class="form-group">
                        <label for="menu_name" class="control-label">Menü İsmi</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name" value="<?=$post["name"]?>">
                    </div>
                    
                    <?php if(($post["id"] != 2) && ($post["id"] != 5) && ($post["id"] != 3) && ($post["id"] != 4) && ($post["id"] != 6)){ ?>
                    <div class="form-group select2-w-100">
                        <label for="menu_link" class="control-label">Menü Linki</label>
                        <select name="menu_link" id="menu_link" class="form-select select2 w-100">
                            
                            
                            <?php
                            if($post["link"] == base_url("api/v2")){
                                $links = 1;
                            }elseif($post["link"] == base_url("terms")){
                                $links = 2;
                            }elseif($post["link"] == base_url("contact")){
                                $links = 3;
                            }else{
                                $links = 4;
                            }
                            
                            ?>
                            
                            <option value="<?= base_url("api/v2") ?>" <?php if($links == 1){echo "selected";} ?>>API</option>
                            <option value="<?= base_url("terms") ?>" <?php if($links == 2){echo "selected";} ?>>Kullanıcı Sözleşmesi</option>
                            <option value="<?= base_url("contat") ?>" <?php if($links == 3){echo "selected";} ?>>İletişim</option>
                            <option value="-1" id="secilmemis" <?php if($links == 4){echo "selected";} ?>>Özel Gir</option>
                        </select>
                    </div>

                    <div class="form-group" id="ozel_link" style="<?php if($links == 4){echo "display:block;";}else{echo "display:none;";} ?>">
                        <label for="menu_link" class="control-label">Link</label>
                        <input type="text" class="form-control" name="menu_link_ozel" id="menu_link_ozel" value="<?=$post["link"]?>">
                    </div>
                    
                    <?php }else{?>
                    <input type="hidden" name="tag" value="<?=$post["tag"]?>">
                    <?php } ?>

                    <div class="form-group">
                        <label for="menu_link" class="control-label">İkon</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_link" value="<?=$post["icon"]?>">
                    </div>
                    

                    <div class="form-group">

                        <button type="submit" class="btn btn-primary mt-2">Güncelle</button>
                    </div>
   <a href="<?= base_url()?>/admin/appearance/menu" class="btn btn-default">Geri</a>
 </form>

</div>
</div>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
    $('#menu_link').change(function () {
        if ($(this).val() == -1) {
            $('#ozel_link').show();
        } else {
            $('#ozel_link').hide();
        }
    });

    // göster/gizle
    function showMe(blockId) {
        if (document.getElementById(blockId).style.display == 'none') {
            document.getElementById(blockId).style.display = '';
        } else if (document.getElementById(blockId).style.display == '') {
            document.getElementById(blockId).style.display = 'none';
        }
    }
</script>