<div class="col-md-10">
			<div class="card">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Duyurular</div>
							<a href="#" class="btn-glycon btn-glycon-success btn-card-header" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="new_news" ><i class="fas fa-plus"></i> Yeni Duyuru Oluştur</a>
						</div>
					</div>
				</div>
				<div class="card-body p-0">
					<table class="table border table-striped m-0">
						<thead class="card-glycon text-white">
							<tr>
								<th scope="col">Duyuru İkonu</th>
								<th scope="col">Duyuru Başlığı</th>
								<th scope="col">Duyuru Tarihi</th>
								<th></th>
							</tr>
						</thead>
						<tbody class="icon-animation">
                        <?php foreach($newsList as $new): ?>
							<tr>
								<td><img src='<?= base_url()?>/assets/img/icons/<?=$new["news_icon"]?>.png' widht="32" height="32"></td>
								<td><?=$new["news_title"]?></td>
								<td><?=$new["news_date"]?></td>
								<td>
									<button class="btn-glycon btn-glycon-light btn-sm py-0" data-bs-toggle="modal" data-bs-target="#modalDiv" data-action="edit_news" data-id="<?=$new['id']?>">Düzenle</button>
								</td>
							</tr>

         <?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>