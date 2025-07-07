<div class="col-md-10">
                <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
    <div class="card">


                    <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-header-title">Ödeme Bonusu</div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_paymentbonus" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i> Yeni Ödeme Bonusu Ekle</a>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
               <?php foreach($bonusList as $bonus): ?>
                    <div class="col-md-4">
                        <div class="glycon-module-card">
                            <div class="glycon-module-image">
                                <p>#<?php echo $bonus["id"]; ?> <?php echo $bonus["method_name"]; ?></p>


                                    <a href="#" class="btn-glycon btn-glycon-success btn-glycon-module" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_paymentbonus" data-id="<?php echo $bonus["bonus_id"]; ?>><i class="fas fa-check"
                                                                                 aria-hidden="true"></i> Düzenle</a>


                            </div>
                            <div class="glycon-module-info">
                                <h5 class="glycon-module-name"></h5>
                                <p class="glycon-module-desc">Bonus: %<?php echo $bonus["bonus_amount"]; ?> -
                                    Min:  <?php echo $bonus["bonus_from"]; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
