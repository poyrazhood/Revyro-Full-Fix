<?= view('admin/yeni_admin/static/header'); ?>

<div class="container-fluid px-sm-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-glycon mb-3">
                <div class="card-header text-center fw-bold">Bugün Toplam Ciro</div>
                <div class="card-body card-glycon">
                    <h5 class="card-title text-center fw-bold fs-1"><?= $ciro ?><?= site_symbol($settings['site_currency'])?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-glycon mb-3">
                <div class="card-header text-center fw-bold">Bugün Toplam Kâr</div>
                <div class="card-body card-glycon">
                    <h5 class="card-title text-center fw-bold fs-1"><?= $kar?><?= site_symbol($settings['site_currency'])?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-glycon mb-3">
                <div class="card-header text-center fw-bold">Aktif Destek Talebi</div>
                <div class="card-body card-glycon">
                    <h5 class="card-title text-center fw-bold fs-1"><?= $active_ticket ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-glycon mb-3">
                <div class="card-header text-center fw-bold">Hatalı Sipariş</div>
                <div class="card-body card-glycon">
                    <h5 class="card-title text-center fw-bold fs-1"><?php if(isset($fail)): echo $fail; else: echo "0"; endif; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12">
            <hr align="center">
        </div>
        <div class="col-md-6">
			<div class="card-body mobile-overflow p-0">
            <table class="table border table-striped">
                <thead class="card-body card-glycon text-white">
                <tr>
                    <div class="align-items-center">
                        <span class="fs-3">Son 5 Sipariş</span>
                    </div>
                </tr>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Bağlantı</th>
                    <th scope="col">Servis</th>
                    <th scope="col">Ücret</th>
                    <th scope="col" class="text-center">Sipariş Durumu</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
						<th scope="row"><a href="<?=base_url("admin")?>/order-detail/<?= $order['order_id'] ?>">#<?= $order['order_id'] ?></a></th>
                        <td>
							
							<div class="btn-group">
							  <button type="button" class="btn btn-primary dropdown-toggle py-1" data-bs-toggle="dropdown" aria-expanded="false">
								Bağlantı
							  </button>
							  <ul class="dropdown-menu px-2">
								  <?= $order['order_url'] ?>
							  </ul>
							</div>
						</td>
                        <td><?= $order['service_name'] ?></td>
                        <td><?= $order['order_charge'] ?><?= site_symbol($settings['site_currency'])?></td>
                        <td class="p-1 align-baseline">
                            <?php if ($order["order_error"] != "-" && $order["service_api"] != 0) { ?>
                                <div class="bg-glycon2 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Fail
                                </div>
                            <?php }  elseif ($order['order_status'] == 'completed') { ?>
                                <div class="bg-glycon bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Tamamlandı
                                </div>
                            <?php } elseif ($order['order_status'] == 'pending') { ?>
                                <div class="bg-glycon3 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Sipariş Alındı
                                </div>
                            <?php } elseif ($order['order_status'] == 'inprogress') { ?>
                                <div class="bg-glycon3 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    İşlemde
                                </div>
                            <?php } elseif ($order['order_status'] == 'partial') { ?>
                                <div class="bg-glycon3 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Kısmi Tamamlandı
                                </div>
                            <?php } elseif ($order['order_status'] == 'processing') { ?>
                                <div class="bg-glycon3 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Yükleniyor
                                </div>
                            <?php } elseif ($order['order_status'] == 'canceled') { ?>

                                <div class="bg-glycon2 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    İptal Edildi
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
			</div>
        </div>
        <div class="col-md-6">
			<div class="card-body mobile-overflow p-0">
            <table class="table border table-striped">
                <thead class="card-body card-glycon text-white">
                <tr>
                    <div class="align-items-center">
                        <span class="fs-3">Son 5 Destek Talebi</span>
                    </div>
                </tr>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Konu</th>
                    <th scope="col">Kullanıcı</th>
                    <th scope="col" class="text-center">Durum</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tickets as $ticket) { ?>
                    <tr>
                        <th scope="row">#<?= $ticket['ticket_id'] ?></th>
                        <td><?= $ticket['subject'] ?></td>
                        <td><?= $ticket['email'] ?></td>
                        <td class="p-1 align-baseline">
                            <?php
                            if ($ticket['status'] == 'answered') {
                                ?>
                                <div class="bg-glycon4 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Cevaplandı
                                </div>
                            <?php } elseif ($ticket['status'] == 'pending') { ?>
                                <div class="bg-glycon2 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Cevap Bekliyor
                                </div>
                            <?php } elseif ($ticket['status'] == 'closed') { ?>

                                <div class="bg-glycon3 bg-gradient fw-bold rounded p-2 text-white text-center m-0">
                                    Çözümlendi
                                </div>
                            <?php } ?>
                        </td>
                    </tr>

                <?php } ?>
                </tbody>
            </table>
			</div>
        </div>
        <div class="col-12">
            <hr align="center">
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-light mb-3">
                <div class="card-header text-center text-white fw-bold">API Sağlayıcı Uyarıları</div>
                <div class="card-body">
                    <div class="row text-dark">
                        <?php
                        foreach($api as $ap){
                            $bakiye = json_decode($ap['api_json'],true);
                        ?>
                        <div class="col-md-4">
                            <span><?= $ap['api_name']?></span>
                        </div>
                        <div class="col-md-4 text-center">
                            <span><?= isset($bakiye['currency'])?$bakiye['currency']:'Apiye Bağlanılamadı'?></span>
                        </div>
                        <div class="col-md-4 text-end">
                            <span>Bakiyeniz: <strong><?= isset($bakiye['currency'])?$bakiye['balance']:'Apiye Bağlanılamadı'?></strong></span>
                        </div>
                        <hr class="mt-3">
                        <?php } ?>
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <div class="form-floating">
                        <textarea class="form-control" style="height: 270px;" onkeypress="notkaydet();"
                                  placeholder="Leave a comment here"
                                  id="floatingTextarea"><?= $settings['notlar']?></textarea>
                    <label for="floatingTextarea">Notlarım</label>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('admin/yeni_admin/static/footer'); ?>

<script>
    function notkaydet() {
        var not = $('#floatingTextarea').val();
        $.post('<?= base_url("/admin/notkaydet")?>', {not:not});
    }
</script>
