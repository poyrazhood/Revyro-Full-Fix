<?= view('admin/yeni_admin/static/header'); ?>
<style>
	.action-block{
		width: 100%;
    position: absolute;
    left: 0px;
    top: 50px;
    height: 44px;
    line-height: 40px;
    background: #0d2443;
    z-index: 1;
    padding-left: 35px;
	}
</style>
    <div class="container-fluid px-sm-5">
        <div class="row">
            <div class="col-md-12 collapse ms-auto mb-3" id="collapseExample">
                <form class="form-inline" action="<?= base_url("admin/logs") ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="<?= $search_word ?>"
                               placeholder="Log Ara">
                        <span class="input-group-btn search-select-wrap ms-2" style="display: inherit;">
						<select class="form-control search-select" name="search_type">
							<option value="username" <?php if ($search_where == "username"): echo 'selected'; endif; ?>>Kullanıcı Adı</option>
							<option value="action" <?php if ($search_where == "action"): echo 'selected'; endif; ?>>IP Adresi</option>
						</select>
						<button type="submit" class="btn btn-dark ms-2"><span class="fa fa-search"
                                                                              aria-hidden="true"></span></button>
					</span>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-0">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="card-header-title">Popuplar</div>
								<a href="javascript::void(0)" data-bs-toggle="modal" data-bs-target="#popupadd" class="btn-glycon btn-glycon-success btn-card-header w-100 w-sm-auto">
                                    <i class="fas fa-search"></i> Popup Ekle
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="card-body mobile-overflow p-0">
                        <table class="table border table-striped m-0">
                            <thead class="card-glycon text-white">
                            <tr>
                                <th scope="col">Popup Adı</th>
                                <th scope="col">Popup Türü</th>
                                <th class="text-center" scope="col">Popup Zaman</th>
                                <th class="text-center" scope="col">İşlemler</th>
                            </tr>
                            </thead>

                                <tbody class="icon-animation">
                                <?php if (!$popups): ?>
                                    <tr>
                                        <td colspan="4">
                                            <center>Herhangi bir popup bulunamadı</center>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($popups as $popup): ?>
                          <tr>

                             <td><?php echo $popup["name"] ?></td>
                             <td><?php if($popup["tur"] == 1){echo "Giriş Yapanlara Gösteriliyor";}elseif($popup["tur"] == 2){echo "Giriş Yapmayanlara Gösteriliyor";}elseif($popup["tur"] == 3){echo "Herkese Gösteriliyor";} ?></td>
                             <td><?php if($popup["zaman"] == 1){echo "Saatte bir gösteriliyor";}elseif($popup["zaman"] == 2){echo "3 Saatte bir gösteriliyor";}elseif($popup["zaman"] == 3){echo "6 Saatte bir gösteriliyor";}elseif($popup["zaman"] == 4){echo "12 Saatte bir gösteriliyor";}elseif($popup["zaman"] == 5){echo "Günde bir gösteriliyor";} ?></td>
                              <td class="text-center">	
                              <a href="javascript::void(0)" data-bs-toggle="modal" data-bs-target="#popupdetail<?=$popup["id"]?>"><i class="fas fa-search-plus text-success"></i></a>
                              <a style="margin-left:.3rem;" href="<?= base_url()?>/admin/popup?delete=<?= $popup['id']?>"><i class="fas fa-trash text-danger"></i></a>
                                    </td>
                          </tr>
                          
                          <div class="modal fade" id="popupdetail<?=$popup["id"]?>" tabindex="-1" aria-labelledby="popupdetail<?=$popup["id"]?>" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Popup Detayı</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                            
                                      <div class="mb-3">
                                        <label for="recipient-name" class="col-form-label">Başlık:</label>
                                        <input type="text" class="form-control" value="<?=$popup["name"]?>" readonly>
                                      </div>
                                      <div class="mb-3">
                                        <label for="message-text" class="col-form-label">İçerik:</label>
                                        <br><hr>
                                        <?=$popup["icerik"]?>
                                      </div>
                                        <div class="mb-3"></div>
                            
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    
                                  </div>
                                </div>
                              </div>
                            </div>
                          
                        <?php endforeach; ?>
                                <?php endif; ?>

                                </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="popupadd" tabindex="-1" aria-labelledby="popupadd" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Popup Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="" method="POST">
      <div class="modal-body">

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Başlık:</label>
            <input type="text" class="form-control" name="baslik" required>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">İçerik:</label>
            <textarea class="form-control" id="tinymce" name="icerik"></textarea>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Tür:</label>
            <select class="form-select" name="tur" required>
                <option value="1">Giriş Yapanlara Göster</option>
                <option value="2">Giriş Yapmayanlara Göster</option>
                <option value="3">Herkese Göster</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Zaman:</label>
            <select class="form-select" name="zaman" required>
                <option value="1">Saatte bir</option>
                <option value="2">3 Saatte bir</option>
                <option value="3">6 Saatte bir</option>
                <option value="4">12 Saatte bir</option>
                <option value="5">Günde bir</option>
            </select>
          </div>
            <div class="mb-3"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Popup Ekle</button>
          </form>
      </div>
    </div>
  </div>
</div>
<?= view('admin/yeni_admin/static/footer'); ?>
<script>
    document.addEventListener('focusin', (e) => {
         if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
         e.stopImmediatePropagation();
   }
</script>