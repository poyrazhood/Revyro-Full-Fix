<div class="col-md-10">
    <div class="card">
        <div class="card-header">
            Modül Ayarları
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach( $active_modules as $module ){ ?>
						<div class="col-md-4">
							<div class="glycon-module-card" style="min-height:275px;">
								<div class="glycon-module-image">
									<p><?=$module["name"]?></p>
                                     <?php if($module["id"] == 4): ?>

									<a href="<?= base_url()?>/admin/settings/subject" class="btn-glycon btn-glycon-primary btn-glycon-module" ><i class="fa fa-cog" aria-hidden="true"></i> Modül Ayarları</a>
                        <?php else: ?>
									<button href="#" class="btn-glycon btn-glycon-primary btn-glycon-module" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="<?=$module["ajax_name"]?>"><i class="fa fa-cog" aria-hidden="true"></i> Modül Ayarları</button>
						<?php endif; ?>
                        <a href="<?= base_url()?>/admin/settings/modules/disable/<?=$module["id"]?>" class="btn-glycon btn-glycon-danger btn-glycon-module payment-methods"><i class="fas fa-check" aria-hidden="true"></i>Pasifleştir</a>
								</div>
								<div class="glycon-module-info">
									<p class="glycon-module-desc"><?=$module["description"]?></p>
								</div>
							</div>
						</div>
                <?php } ?>
                 <?php foreach( $passive_modules as $module_1 ){ ?>
						<div class="col-md-4">
							<div class="glycon-module-card" style="min-height:275px;">
								<div class="glycon-module-image">
									<p><?=$module_1["name"]?></p>
									<button href="#" class="btn-glycon btn-glycon-primary btn-glycon-module" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="<?=$module_1["ajax_name"]?>"><i class="fa fa-cog" aria-hidden="true"></i> Modül Ayarları</button>
									<a href="<?= base_url()?>/admin/settings/modules/enable/<?=$module_1["id"]?>" class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check" aria-hidden="true"></i> Aktifleştir</a>
								</div>
								<div class="glycon-module-info">
									<p class="glycon-module-desc"><?=$module_1["description"]?></p>
								</div>
							</div>
						</div>
                <?php } ?>

					</div>
        </div>
    </div>
</div>
