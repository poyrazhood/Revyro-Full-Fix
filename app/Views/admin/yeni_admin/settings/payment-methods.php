<div class="col-md-10">
    <div class="card">


                    <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-header-title">Ödeme Ayarları</div>
                    <a href="<?= base_url() ?>/admin/settings/bank-accounts" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i> Banka
                Hesapları</a>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <?php foreach ($methodList as $method): $extra = json_decode($method["method_extras"], true); ?>
                    <div class="col-md-4">
                        <div class="glycon-module-card">
                            <div class="glycon-module-image">
                                <p><?php echo $method["method_name"]; ?></p>
                                <button href="#"
                                        class="btn-glycon btn-glycon-primary btn-glycon-module edit-payment-method"
                                        data-bs-toggle="modal" data-bs-target="#modalDiv"
                                        data-action="edit_paymentmethod" data-id="<?php echo $method["method_get"]; ?>">
                                    <i class="fa fa-cog" aria-hidden="true"></i> <?php echo $method["method_name"]; ?>
                                    Ayarları
                                </button>
                                <?php if ($method["method_type"] == 2) { ?>
                                    <a href="#" class="btn-glycon btn-glycon-danger btn-glycon-module payment-methods"
                                       data-status="pasif"
                                       data-url="<?= base_url("admin/settings/payment-methods/type") ?>"
                                       data-id="<?php echo $method["id"]; ?>"><i class="fas fa-times"
                                                                                 aria-hidden="true"></i> Pasifleştir</a>
                                <?php } else { ?>
                                    <a href="#" class="btn-glycon btn-glycon-success btn-glycon-module payment-methods"
                                       data-status="aktif"
                                       data-url="<?= base_url("admin/settings/payment-methods/type") ?>"
                                       data-id="<?php echo $method["id"]; ?>"><i class="fas fa-check"
                                                                                 aria-hidden="true"></i> Aktifleştir</a>

                                <?php } ?>
                            </div>
                            <div class="glycon-module-info">
                                <h5 class="glycon-module-name">Kredi/Banka Kartı | 3D Güvenli Ödeme</h5>
                                <p class="glycon-module-desc">Min: <?php echo $method["method_min"]; ?> -
                                    Max: <?php if ($method["method_max"] == 0) {
                                        echo("∞");
                                    } else {
                                        echo $method["method_max"];
                                    } ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
