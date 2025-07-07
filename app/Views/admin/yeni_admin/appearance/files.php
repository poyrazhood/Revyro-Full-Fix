<div class="col-md-10">
			<div class="card mt-3 mt-sm-0">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12 navbar-expand-lg">
							<div class="card-header-title">Dosyalar</div>
							<button class="navbar-toggler float-end" style="margin-top:10px;" type="button" data-bs-toggle="collapse" data-bs-target="#card-Nav" aria-controls="card-Nav" aria-expanded="false" aria-label="Toggle navigation">
									<i class="fas fa-bars text-white"></i>
    							</button>
							<div class="collapse navbar-collapse w-100 w-sm-auto float-end" id="card-Nav">
							<form action="" method="post" enctype="multipart/form-data">
								<button type="submit" href="#" class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto text-center" ><i class="fas fa-plus"></i> Yükle</button>
							<input class="form-control w-100 w-sm-auto text-center" style="width: auto;display: inline-block;float: right;height: 49px;line-height: 35px;background: #dde0e3;border-radius: 0;" id="formFile" type="file" name="logo" accept="image/*"> 							
							</form>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body mobile-overflow p-0">
					<table class="table border table-striped m-0">
						<thead class="card-glycon text-white">
							<tr>
								<th>Görsel</th>
								<th>Dosya Adı</th>
								<th></th>
							</tr>
						</thead>
						
						<tbody class="icon-animation">
							<?php foreach( $fileList as $file ){ ?>
							<tr>
								<td>
									<a href="<?=$file['link']?>" target="_blank"><img style="width:150px;height:50px;" src="<?=$file['link']?>"></a>
								</td>
								<td>
									<a href="<?=$file['link']?>" target="_blank"><?=$file['link']?></a>
								</td>
								<td>
									<a href="<?= base_url()?>/admin/appearance/files/delete/<?=$file['id']?>" class="btn-glycon btn-glycon-danger text-center"><i class="fas fa-times"></i> Sil</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    