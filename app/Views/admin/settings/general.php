<div class="col-md-8"> 
        <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>

  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <div class="row">
            <div class="col-md-10">
              <label for="preferenceLogo" class="control-label">Logo</label>
              <input type="file" name="logo" id="preferenceLogo">
                        <p class="help-block">200 x 80px önerilen boyutlardır</p>
            </div>
            <div class="col-md-2">
              <?php if( $settings["site_logo"] ):  ?>
                <div class="setting-block__image">
                      <img class="img-thumbnail" src="<?=$settings["site_logo"]?>">
                    <div class="setting-block__image-remove">
                      <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-logo")?>"><span class="fa fa-remove"></span></a>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-11">
              <label for="preferenceFavicon" class="control-label">Favicon</label>
              <input type="file" name="favicon" id="preferenceFavicon">
                        <p class="help-block">16 x 16px .png önerilen boyutlardır</p>
            </div>
            <div class="col-md-1">
              <?php if( $settings["favicon"] ):  ?>
                <div class="setting-block__image">
                    <img class="img-thumbnail" src="<?=$settings["favicon"]?>">
                    <div class="setting-block__image-remove">
                      <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-favicon")?>"><span class="fa fa-remove"></span></a>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
   <hr>  
   <div class="form-group">
          <label class="control-label">Panel Adı</label>
          <input type="text" class="form-control" name="name" value="<?=$settings["site_name"]?>">
        </div>
        
        <div class="form-group">
                            <label class="control-label" for="createorderform-currency">Para Birimi</label>
                            <select class="form-control" name="site_currency">
                                                                    <option value="USD" <?php if($settings["site_currency"] == "USD"): echo"selected"; endif; ?>>
                                        United States Dollars (USD)
                                    </option>
                                                                    <option value="TRY" <?php if($settings["site_currency"] == "TRY"): echo"selected"; endif; ?>>
                                        Türk Lirası (TRY)
                                    </option>
                                                                    <option value="EUR" <?php if($settings["site_currency"] == "EUR"): echo"selected"; endif; ?>>
                                        Euro (EUR)
                                    </option>
                                                                 
                                                            </select>
                        </div>
                        
                      
                        
     <div class="form-group">
            <label class="control-label">Zaman Dilimi</label>
            <select class="form-control" name="timezone">
                        <?php
                                foreach($timezones as $timezoneKey => $timezoneVal){
                                    if($settings["site_timezone"] == $timezoneVal["timezone"]){
                                        echo '<option selected value="'.$timezoneVal["timezone"].'">'.$timezoneVal["label"].'</option>';
                                    }else{
                                        echo '<option value="'.$timezoneVal["timezone"].'">'.$timezoneVal["label"].'</option>';
                                    }
                                }
                        
                        ?>
              </select>
          </div>
        <div class="form-group">
          <label class="control-label">Bakım Modu</label>
          <select class="form-control" name="site_maintenance"> 
            <option value="1" <?= $settings["site_maintenance"] == 1 ? "selected" : null; ?>>Açık</option>
            <option value="2" <?= $settings["site_maintenance"] == 2 ? "selected" : null; ?> >Kapalı</option>
          </select>
        </div>  
        <hr>
        <div class="form-group">
          <label class="control-label">Destek Sistemi</label>
          <select class="form-control" name="ticket_system">
            <option value="2" <?= $settings["ticket_system"] == 2 ? "selected" : null; ?> >Açık</option>
            <option value="1" <?= $settings["ticket_system"] == 1 ? "selected" : null; ?>>Kapalı</option>
          </select>
        </div>
                <?php if( $settings["ticket_system"] == 2): ?>
        <div class="form-group">
          <label class="control-label">Kullanıcı başına bekleyen maksimum ticket</label>
          <select class="form-control" name="max_ticket">
            <option value="1" <?= $settings["max_ticket"] == 1 ? "selected" : null; ?>>1</option>
<option value="2" <?= $settings["max_ticket"] == 2 ? "selected" : null; ?>>2 (Önerilen)</option>
<option value="3" <?= $settings["max_ticket"] == 3 ? "selected" : null; ?>>3</option>
<option value="4" <?= $settings["max_ticket"] == 4 ? "selected" : null; ?>>4</option>
<option value="5" <?= $settings["max_ticket"] == 5 ? "selected" : null; ?>>5</option>
<option value="6" <?= $settings["max_ticket"] == 6 ? "selected" : null; ?>>6</option>
<option value="7" <?= $settings["max_ticket"] == 7 ? "selected" : null; ?>>7</option>
<option value="8" <?= $settings["max_ticket"] == 8 ? "selected" : null; ?>>8</option>
<option value="9" <?= $settings["max_ticket"] == 9 ? "selected" : null; ?>>9</option>
<option value="99" <?= $settings["max_ticket"] == 99 ? "selected" : null; ?>>Sınırsız</option>
          </select>
        </div> <hr />
    <?php endif; ?>
        
        
              <div class="form-group">
          <label class="control-label">Yeni Üyelik <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <select class="form-control" name="registration_page">
            <option value="2" <?= $settings["register_page"] == 2 ? "selected" : null; ?>>Açık</option>
            <option value="1" <?= $settings["register_page"] == 1 ? "selected" : null; ?>>Kapalı</option>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label">Numara Alanı <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <select class="form-control" name="skype_area">
            <option value="2" <?= $settings["skype_area"] == 2 ? "selected" : null; ?>>Aktif</option>
            <option value="1" <?= $settings["skype_area"] == 1 ? "selected" : null; ?>>Pasif</option>
          </select>
        </div>

        <div class="form-group">
          <label class="control-label">İsim Alanı <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <select class="form-control" name="name_secret">
            <option value="2" <?= $settings["name_secret"] == 2 ? "selected" : null; ?>>Aktif</option>
            <option value="1" <?= $settings["name_secret"] == 1 ? "selected" : null; ?>>Pasif</option>
          </select>
        </div>
               
        <div class="form-group">
          <label class="control-label">Kayıt sırasında sözleşme <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <select class="form-control" name="terms_checkbox">
            <option value="2" <?= $settings["terms_checkbox"] == 2 ? "selected" : null; ?>>Aktif</option>
            <option value="1" <?= $settings["terms_checkbox"] == 1 ? "selected" : null; ?>>Pasif</option>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label">Sipariş sırasında onaylama <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <select class="form-control" name="neworder_terms">
            <option value="2" <?= $settings["neworder_terms"] == 2 ? "selected" : null; ?>>Aktif</option>
            <option value="1" <?= $settings["neworder_terms"] == 1 ? "selected" : null; ?>>Pasif</option>
          </select>
        </div>
       
        <div class="form-group">
            <label class="control-label">Şifremi Unuttum <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="resetpass">
              <option value="2" <?= $settings["resetpass_page"] == 2 ? "selected" : null; ?> >Aktif</option>
              <option value="1" <?= $settings["resetpass_page"] == 1 ? "selected" : null; ?>>Pasif</option>
            </select>
        </div> 
        <hr>
      <div class="row">
        <div class="form-group col-md-6">
            <label class="control-label">Servis listesi <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="service_list">
              <option value="2" <?php if($settings["service_list"] == 2){ echo "selected"; } ?>>Herkese açık</option>
              <option value="1" <?php if($settings["service_list"] == 1){ echo "selected"; } ?>>Sadece üyeler</option>
            </select>
        </div> 
     
         <div class="form-group col-md-6">
            <label class="control-label">Otomatik Refill <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="auto_refill">
              <option value="2" <?php if($settings["auto_refill"] == 2){ echo "selected"; } ?>>Aktif</option>
              <option value="1" <?php if($settings["auto_refill"] == 1){ echo "selected"; } ?>>Pasif</option>
            </select> </div> 
        <div class="form-group col-md-6">
            <label class="control-label">Ortalama tamamlanma süreleri <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="avarage">
              <option value="2" <?php if($settings["avarage"] == 2){ echo "selected"; } ?>>Aktif</option>
              <option value="1" <?php if($settings["avarage"] == 1){ echo "selected"; } ?>>Pasif</option>
            </select>
        </div> 
               
            <div class="form-group col-md-6">
            <label class="control-label">Sağlayıcıda Servis Kapanırsa <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="ser_sync">
              <option value="2" <?= $settings["ser_sync"] == 2 ? "selected" : null; ?> >Sadece Uyar</option>
              <option value="1" <?= $settings["ser_sync"] == 1 ? "selected" : null; ?>>Uyar & Servisi Pasifleştir</option>
            </select>
        </div> 
        </div>
<hr>
<div class="row">
<div class="form-group col-md-6">
            <label class="control-label">SMS Doğrulama <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="sms_verify">
              <option value="2" <?= $settings["sms_verify"] == 2 ? "selected" : null; ?> >Aktif</option>
              <option value="1" <?= $settings["sms_verify"] == 1 ? "selected" : null; ?>>Pasif</option>
            </select>
        </div> 
        <div class="form-group col-md-6">
            <label class="control-label">Mail Doğrulama <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
            <select class="form-control" name="mail_verify">
              <option value="2" <?php if($settings["mail_verify"] == 2){ echo "selected"; } ?>>Aktif</option>
              <option value="1" <?php if($settings["mail_verify"] == 1){ echo "selected"; } ?>>Pasif</option>
            </select>
        </div> 
    </div>    
        <hr />
        
        <div class="form-group">
          <label class="control-label">Header Kod Alanı (Tüm Sayfalarda Gözükür) <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <textarea class="form-control" rows="7" name="custom_header" placeholder='<style type="text/css">...</style>'><?=$settings["custom_header"]?></textarea>
        </div>
        <div class="form-group">
          <label>Footer Kod Alanı (Tüm Sayfalarda Gözükür) <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <textarea class="form-control" rows="7" name="custom_footer" placeholder='<script>...</script>'><?=$settings["custom_footer"]?></textarea>
        </div>
    <hr>
        <div class="form-group">
          <label class="control-label">Google Ads Veri Gönderme (Her yer) <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <textarea class="form-control" rows="7" name="custom_header" placeholder='<script>...</script>'><?=$settings["google_ads_all"]?></textarea>
        </div>
        <div class="form-group">
          <label>Google Ads Veri Gönderme (Sadece Ödemeler) <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"></span></label>
          <textarea class="form-control" rows="7" name="custom_footer" placeholder='<script>...</script>'><?=$settings["google_ads_odeme"]?></textarea>
        </div>
    <hr>
    <div class="form-group">
          <label>Güncelleme Sistemi (Son kaç güncelleme gösterilsin?)</label>
          <input class="form-control" name="up_limiti" value="<?=$settings["up_limiti"]?>">
    </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
      </form>
    </div>
  </div>
</div>

<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
 <div class="modal-dialog modal-dialog-center" role="document">
   <div class="modal-content">
     <div class="modal-body text-center">
       <h4>İşlemi onaylıyor musun?</h4>
       <div align="center">
         <a class="btn btn-primary" href="" id="confirmYes">Evet</a>
         <button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
       </div>
     </div>
   </div>
 </div>
</div>
