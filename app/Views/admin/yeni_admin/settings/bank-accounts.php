<div class="col-md-10">
    <div class="card">
        <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-header-title">Banka Ayarları</div>
                    <button data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_bankaccount" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i> Yeni
                        Banka Hesabı Ekle</button>
                </div>
            </div>
        </div>
        <div class="card-body">

            <hr>
            <div class="row">
                <?php foreach ($bankList as $bank): ?>
                    <div class="col-md-4">
                        <div class="glycon-module-card">
                            <div class="glycon-module-image">
                                <p> <?php echo $bank["bank_name"]; ?></p>
                                <button href="#"
                                        class="btn-glycon btn-glycon-primary btn-glycon-module edit-payment-method"
                                        data-bs-toggle="modal" data-bs-target="#modalDiv"
                                        data-action="edit_bankaccount" data-id="<?php echo $bank["id"]; ?>">
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                    Düzenle
                                </button>

                            </div>
                            <div class="glycon-module-info">
                                <h5 class="glycon-module-name"><?php echo $bank["bank_alici"]; ?></h5>
                                <p class="glycon-module-desc"><?php echo $bank["bank_iban"]; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
