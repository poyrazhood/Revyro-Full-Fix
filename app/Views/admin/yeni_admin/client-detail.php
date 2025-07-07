<?= view('admin/yeni_admin/static/header'); ?>

<div class="container-fluid px-SM-5">
	<div class="row">
		<div class="col-md-12">
			<div class="bgimg-glycon bg-gradient rounded p-4">
				<div class="col-12 text-center client-detail">	
					<img src="https://wisecp.glycon.com.tr/resources/uploads/admin/profile/default.jpg" class="img-fluid rounded-circle avatar">  
					<h3 class="text-white"><?= $client['username']?></h3>
				</div>			
			</div>
		</div>
		<div class="col-md-12 mt-3">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
                            <?php if ($client["client_type"] == 1): $type = "active"; else: $type = "deactive"; endif; ?>
							<div class="card-header-title">Kullanıcı Bilgileri</div>
							<a href="#" class="btn-glycon btn-glycon-info btn-card-header" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_user" data-id="<?=$client["client_id"]?>"><i class="fas fa-plus"></i> Kullanıcıyı Düzenle</a>
							<a href="#" class="btn-glycon btn-glycon-success btn-card-header" data-bs-toggle="modal" data-bs-target="#modalDiv" data-id="<?php echo $client["client_id"] ?>" data-action="price_user"><i class="fas fa-plus"></i> Özel Fiyatlandırma</a>
							<?php if($type == "active"): ?>
                            <a href="<?php echo base_url("admin/clients/" . $type . "/" . $client["client_id"]) ?>" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i> Üyenin Yasağını Kaldır</a>
	                        <?php else: ?>
                            <a href="<?php echo base_url("admin/clients/" . $type . "/" . $client["client_id"]) ?>" class="btn-glycon btn-glycon-danger btn-card-header"><i class="fas fa-plus"></i> Üyeyi Yasakla</a>

                            <?php endif; ?>
    </div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0">
						<thead class="card-glycon text-white">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Ad Soyad</th>
								<th scope="col">Kullanıcı Adı</th>
								<th scope="col">E-Mail</th>
								<th class="text-center" scope="col">Parola</th>
								<th class="text-center" scope="col">Mevcut Bakiye</th>
								<th class="text-center" scope="col">Harcanan Bakiye</th>
								<th scope="col" class="text-center">Durumu</th>
							</tr>
						</thead>
						<tbody class="icon-animation">
							<tr>
								<th scope="row">#<?= $client["client_id"] ?></th>
								<td><?= $client['first_name']." ".$client['last_name']?></td>
								<td><?= $client['username']?></td>
								<td><?= $client['email']?></td>
								<th scope="col">
									<div class="text-center">
										<a href="#" class="btn-glycon btn-glycon-danger" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="pass_user" data-id="<?=$client["client_id"]?>" ><i class="fas fa-unlock-alt"></i> Parolayı Yenile</a>
									</div>
								</th>
								<td class="text-center"><?= $client["balance"] ?></td>
								<td class="text-center"><?= $client["spent"]?></td>
								<td class="text-center">
									<?php if ($client["client_type"] == 2): ?>
                                            <div class="glycon-badge badge-success">Aktif</div>

                                        <?php else: ?>

                                            <div class="glycon-badge badge-danger">Pasif</div>
                                        <?php endif; ?>
								</td>
							</tr>		
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mt-3">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Son 5 Siparişi</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0">
						<thead class="card-body card-glycon text-white">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Bağlantı</th>
								<th scope="col">Servis</th>
								<th scope="col">Ücret</th>
								<th scope="col" class="text-center">Sipariş Durumu</th>
							</tr>
						</thead>
						<tbody>
                        <?php foreach($order as $or){ ?>
							<tr>
								<th scope="row">#<?= $or['order_id']?></th>
								<td><a href="<?= $or['order_url']?>"><?= $or['order_url']?></a></td>
								<td><?= $or['service_name']?></td>
								<td><?= $or['order_charge']?>₺</td>
								<td class="text-center">
									<div class="glycon-badge badge-success"><?= $or['order_status']?></div>
								</td>
							</tr>
                            <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mt-3">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Son 5 Destek Talebi</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0">
						<thead class="card-body card-glycon text-white">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Konu</th>
								<th scope="col" class="text-center">Durum</th>
							</tr>
						</thead>
						<tbody>
                        <?php foreach($ticket as $t){ ?>
							<tr>
								<th scope="row">#<?= $t['ticket_id']?></th>
								<td><a href=""><?= $t['subject']?></a></td>
								<td class="text-center">
                                                                <?php
                            if ($t['status'] == 'answered') {
                                ?>
                                <div class="glycon-badge badge-info">
                                    Cevaplandı
                                </div>
                            <?php } elseif ($t['status'] == 'pending') { ?>
                                <div class="glycon-badge badge-danger">
                                    Cevap Bekliyor
                                </div>
                            <?php } elseif ($t['status'] == 'closed') { ?>

                                <div class="glycon-badge badge-success">
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
		</div>
		<div class="col-md-12 mt-3">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Yapılan Ödemeler</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0">
						<thead class="card-body card-glycon text-white">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Ödeme Yöntemi</th>
								<th scope="col">Yüklenen Tutar</th>
								<th scope="col">Eski Bakiye</th>
								<th scope="col">Yeni Bakiye</th>
								<th scope="col" class="text-center">Durum</th>
							</tr>
						</thead>
						<tbody class="icon-animation">
                        <?php foreach($payments as $pay){ ?>
							<tr>
								<th scope="row">#<?= $pay['payment_id']?></th>
								<td><?= $pay['method_name']?></td>
								<td><?= $pay["payment_amount"]?></td>
								<td><?= $pay["client_balance"] ?></td>
								<td><?= $pay["client_balance"]+$pay["payment_amount"] ?></td>
								<td class="text-center">
								    <?php if($pay["payment_status"] == 1){  ?>
									<div class="glycon-badge badge-warning">Beklemede</div>
									<?php } ?>
									<?php if($pay["payment_status"] == 2){  ?>
									<div class="glycon-badge badge-danger">İptal Edildi</div>
									<?php } ?>
									<?php if($pay["payment_status"] == 3){  ?>
									<div class="glycon-badge badge-success">Ödendi</div>
									<?php } ?>
								</td>
							</tr>
                        <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?= view('admin/yeni_admin/static/footer'); ?>