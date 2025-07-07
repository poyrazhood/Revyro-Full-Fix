<div class="col-md-10">
    <div class="card integration-p">
        <div class="card-header">
            Entegrasyon Ayarları
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <?php foreach ($active as $int) { ?>
                    <div class="col-md-4">
                        <div class="glycon-module-card" style="min-height:300px;">
                            <?php if ($int["id"] == 9) { ?><a target="_blank" href="https://docs.glyc.one/help-center/articles/10/tawkto"><i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true"  style="top: 0%;margin-left: 87%;margin-bottom: %;font-size: 20px;"></i></a><?php }elseif($int["id"] == 13){ ?><a target="_blank" href="https://docs.glyc.one/help-center/articles/5/7/11/recaptcha-v2"><i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true"  style="top: 0%;margin-left: 87%;margin-bottom: %;font-size: 20px;"></i></a> <?php } ?>
                            <div class="glycon-module-image">
                                <img height="75" src="<?= base_url('assets/' . $int['icon_url']) ?>"
                                     alt="<?= $int['name'] ?>">
                                <p><?= $int['name'] ?></p>

                                <?php if ($int["id"] == 13) { ?>
                                    <button type="button" class="btn-glycon btn-glycon-primary btn-glycon-module"
                                            data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_google">
                                        Düzenle
                                    </button>
                                <?php } elseif ($int["id"] == 14) { ?>
                                    <button type="button" class="btn-glycon btn-glycon-primary btn-glycon-module"
                                            data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_seo">
                                        Düzenle
                                    </button>
                                <?php } else { ?>

                                    <button type="button" class="btn-glycon btn-glycon-primary btn-glycon-module"
                                            data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_code"
                                            data-id="<?= $int['id'] ?>">Düzenle
                                    </button>
                                <?php } ?>
                                <a href="<?= base_url() ?>/admin/settings/integrations/disabled/<?= $int["id"] ?>"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>
                            </div>
                            <div class="glycon-module-info">
                                <p class="glycon-module-desc"><?= $int['description'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <?php foreach ($other as $int) { ?>
                    <div class="col-md-4">
                        <div class="glycon-module-card" style="min-height:300px;">
                            <div class="glycon-module-image">
                                <img height="75" src="<?= base_url('assets/' . $int['icon_url']) ?>"
                                     alt="<?= $int['name'] ?>">
                                <p><?= $int['name'] ?></p>


                                <a href="<?= base_url() ?>/admin/settings/integrations/enabled/<?= $int["id"] ?>"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                 aria-hidden="true"></i> Aktifleştir</a>
                            </div>
                            <div class="glycon-module-info">
                                <p class="glycon-module-desc"><?= $int['description'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>

    </div>
</div>
