<?= view('admin/yeni_admin/static/header'); ?>

<div class="container-fluid px-sm-5">
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Sipariş Detayları</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow">
					<form action="" method="POST">
						<input type="hidden" name="id" value="<?= $order['order_id']?>">
						<div class="row">
							<?php
							$api_de = json_decode($order['api_json'],true);
							?>
							<div class="col-md-3 fw-bold">
								ID
							</div>
							<div class="col-md-9">
								#<?= $order['order_id']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Müşteri
							</div>
							<div class="col-md-9">
								<?= $order['username']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Bağlantı
							</div>
							<div class="col-md-9">
								<?= $order['order_url']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Servis
							</div>
							<div class="col-md-9">
								<?= $order['service_name']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Başlangıç Sayısı
							</div>
							<div class="col-md-9">
								0
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Miktar
							</div>
							<div class="col-md-9">
								<?= $order['order_quantity']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Kalan
							</div>
							<div class="col-md-9">
								<?= $order['order_remains']?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Tutar
							</div>
							<div class="col-md-9">
								<?= $order['order_charge']?><?= site_symbol($settings['site_currency'])?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Durum
							</div>
							<div class="col-md-9">
							    <?php if($order['order_status'] == 'pending'){ ?>
								<div class="glycon-badge badge-primary">Sipariş Alındı</div>
								<?php }elseif($order['order_status'] == 'processing'){ ?>
								<div class="glycon-badge badge-secondary">Gönderim Sırasında</div>
								<?php }elseif($order['order_status'] == 'inprogress'){ ?>
								<div class="glycon-badge badge-info">Yükleniyor</div>
								<?php }elseif($order['order_status'] == 'partial'){ ?>
								<div class="glycon-badge badge-warning">Kısmi Tamamlandı</div>
								<?php }elseif($order['order_status'] == 'completed'){ ?>
								<div class="glycon-badge badge-success">Tamamlandı</div>
								<?php }elseif($order['order_status'] == 'canceled'){ ?>
								<div class="glycon-badge badge-danger">İptal Edildi</div>
								<?php } ?>
							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-md-3 fw-bold">
								Seçenekler
							</div>
							<div class="col-md-9">
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" <?= $order['order_status'] == 'pending'?'checked':''?> type="radio" name="order_status" value="pending" id="flexRadioDefault1">
									<label class="form-check-label me-2" for="flexRadioDefault1">
										Sipariş Alındı
									</label>
								</div>
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" type="radio" <?= $order['order_status'] == 'processing'?'checked':''?> name="order_status" value="processing" id="flexRadioDefault2">
									<label class="form-check-label me-2" for="flexRadioDefault2">
										Gönderim Sırasında
									</label>
								</div>
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" type="radio" <?= $order['order_status'] == 'inprogress'?'checked':''?> name="order_status" value="inprogress" id="flexRadioDefault3">
									<label class="form-check-label me-2" for="flexRadioDefault3">
										Yükleniyor
									</label>
								</div>
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" type="radio" <?= $order['order_status'] == 'partial'?'checked':''?> name="order_status" value="partial" id="flexRadioDefault4">
									<label class="form-check-label me-2" for="flexRadioDefault4">
										Kısmi Tamamlandı
									</label>
								</div>
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" type="radio" <?= $order['order_status'] == 'completed'?'checked':''?> name="order_status" value="completed" id="flexRadioDefault5">
									<label class="form-check-label me-2" for="flexRadioDefault5">
										Tamamlandı
									</label>
								</div>
								<div class="inputs  d-block d-sm-inline">
									<input class="form-check-input" type="radio" <?= $order['order_status'] == 'canceled'?'checked':''?> name="order_status" value="canceled" id="flexRadioDefault6">
								<label class="form-check-label me-2" for="flexRadioDefault6">
									İptal Edildi
								</label>
								</div>


							</div>
							<div class="col-12 text-muted">
								<hr>
							</div>
							<div class="col-12">
								<div class="d-grid gap-2">
									<button type="submit" class="btn-glycon btn-glycon-success text-center"><i class="fas fa-cogs"></i> Güncelle</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">API Detayları</div>
						</div>
					</div>
				</div>
				<div class="card-body ">
					<div class="api-detail row">
						<div class="col-md-6 fw-bold">
							Sağlayıcı
						</div>
						<div class="col-md-6">
							<?= $order['api_name']?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>

						<div class="col-md-6 fw-bold">
							Sipariş ID
						</div>
						<div class="col-md-6">
							#<?= $order['api_orderid']?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-md-6 fw-bold">
							Sipariş Tutarı
						</div>
						<div class="col-md-6">
							<?= $order['api_charge']?><?= site_symbol($settings['site_currency'])?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-md-6 fw-bold">
							Sağlayıcı Para Birimi
						</div>
						<div class="col-md-6">
							<?= isset($api_de['currency'])?$api_de['currency']:''?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-md-6 fw-bold">
							Sağlayıcı Bakiyesi
						</div>
						<div class="col-md-6">
							<?= isset($api_de['balance'])?$api_de['balance']:''?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-md-6 fw-bold">
							Son Kontrol
						</div>
						<div class="col-md-6">
							<?= $order['last_check']?>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-md-6 fw-bold">
							Sağlayıcı Adresi
						</div>
						<div class="col-md-6">
							<a href="<?= $order['api_url']?>" class="text-glycon">Ziyaret Et</a>
						</div>
						<div class="col-12 text-muted">
							<hr>
						</div>
						<div class="col-12">
							<div class="bg-glycon text-white p-3 shadow">

								<?= $order['order_detail']?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= view('admin/yeni_admin/static/footer'); ?>

