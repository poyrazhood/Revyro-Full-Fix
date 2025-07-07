<div class="col-md-10">
    <?php if ($success) : ?>
        <div class="alert alert-success "><?php echo $successText; ?></div>
    <?php endif; ?>
    <?php if ($error) : ?>
        <div class="alert alert-danger "><?php echo $errorText; ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            Genel Ayarlar
        </div>

        <div class="card-body settings-form">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <?php if ($settings['site_logo'] != "") : ?>
                            <img style="width:200px;height:80px;" src="<?= base_url('assets/uploads/sites/' . $settings['site_logo']) ?>" alt="">
                            <br>
                            <div class="setting-block__image-remove">
                                <a href="" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?= base_url("admin/settings/general/delete-logo") ?>"><span class="fa fa-remove"></span></a>
                            </div>
                        <?php endif; ?>
                        <strong>Logo</strong><span> (200x80 önerilir)</span>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="logo" id="inputGroupFile02">
                            <label class="input-group-text" for="inputGroupFile02">Yükle</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <?php if ($settings['favicon'] != "") : ?>
                            <img style="width:100px;height:80px;" src="<?= base_url('assets/uploads/sites/' . $settings['favicon']) ?>" alt="">
                            <br>
                            <div class="setting-block__image-remove">
                                <a href="" data-bs-toggle="modal" data-bs-target="#confirmChange" data-href="<?= base_url("admin/settings/general/delete-favicon") ?>"><span class="fa fa-remove"></span></a>
                            </div>
                        <?php endif; ?>
                        <strong>Favicon</strong><span> (100x100 önerilir)</span>
                        <div class="input-group mb-3">
                            <input type="file" name="favicon" class="form-control" id="inputGroupFile02">
                            <label class="input-group-text" for="inputGroupFile02">Yükle</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Panel Adı</strong>
                        <input type="text" class="form-control" name="name" value="<?= $settings["site_name"] ?>">
                    </div>
                    <div class="col-6">
                        <strong>Para Birimi</strong>
                        <select class="form-select" name="site_currency">
                            <option value="USD" <?php if ($settings["site_currency"] == "USD") : echo "selected";
                                                endif; ?>>
                                United States Dollars (USD)
                            </option>
                            <option value="TRY" <?php if ($settings["site_currency"] == "TRY") : echo "selected";
                                                endif; ?>>
                                Türk Lirası (TRY)
                            </option>
                            <option value="EUR" <?php if ($settings["site_currency"] == "EUR") : echo "selected";
                                                endif; ?>>
                                Euro (EUR)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Zaman Dilimi</strong>
                        <select class="form-select" name="timezone">
                            <?php
                            foreach ($timezones as $timezoneKey => $timezoneVal) {
                                if ($settings["site_timezone"] == $timezoneVal["timezone"]) {
                                    echo '<option selected value="' . $timezoneVal["timezone"] . '">' . $timezoneVal["label"] . '</option>';
                                } else {
                                    echo '<option value="' . $timezoneVal["timezone"] . '">' . $timezoneVal["label"] . '</option>';
                                }
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <strong>Bakım Modu</strong>
                        <select class="form-select" name="site_maintenance">
                            <option value="1" <?= $settings["site_maintenance"] == 1 ? "selected" : null; ?>>Açık</option>
                            <option value="2" <?= $settings["site_maintenance"] == 2 ? "selected" : null; ?>>Kapalı
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Destek Sistemi</strong>
                        <select class="form-select" name="ticket_system">
                            <option value="2" <?= $settings["ticket_system"] == 2 ? "selected" : null; ?>>Açık</option>
                            <option value="1" <?= $settings["ticket_system"] == 1 ? "selected" : null; ?>>Kapalı</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <strong>Aktif Max Ticket</strong>
                        <select class="form-select" name="max_ticket">
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
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Yeni Üyelik</strong>
                        <select class="form-select" name="registration_page">
                            <option value="2" <?= $settings["register_page"] == 2 ? "selected" : null; ?>>Açık</option>
                            <option value="1" <?= $settings["register_page"] == 1 ? "selected" : null; ?>>Kapalı</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Numara Alanı</strong>
                        <select class="form-select" name="skype_area">
                            <option value="2" <?= $settings["skype_area"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["skype_area"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>İsim Alanı</strong>
                        <select class="form-select" name="name_secret">
                            <option value="2" <?= $settings["name_secret"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["name_secret"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Kayıt Sözleşmesi</strong>
                        <select class="form-select" name="terms_checkbox">
                            <option value="2" <?= $settings["terms_checkbox"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["terms_checkbox"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Sipariş Sözleşmesi</strong>
                        <select class="form-select" name="neworder_terms">
                            <option value="2" <?= $settings["neworder_terms"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["neworder_terms"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Şifremi Unuttum</strong>
                        <select class="form-select" name="resetpass">
                            <option value="2" <?= $settings["resetpass_page"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["resetpass_page"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Servis Listesi</strong>
                        <select class="form-select" name="service_list">
                            <option value="2" <?php if ($settings["service_list"] == 2) {
                                                    echo "selected";
                                                } ?>>Herkese açık</option>
                            <option value="1" <?php if ($settings["service_list"] == 1) {
                                                    echo "selected";
                                                } ?>>Sadece üyeler</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Otomatik Refill</strong>
                        <select class="form-select" name="auto_refill">
                            <option value="2" <?php if ($settings["auto_refill"] == 2) {
                                                    echo "selected";
                                                } ?>>Aktif</option>
                            <option value="1" <?php if ($settings["auto_refill"] == 1) {
                                                    echo "selected";
                                                } ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Ortalama tamamlanma süreleri</strong>
                        <select class="form-select" name="avarage">
                            <option value="2" <?php if ($settings["avarage"] == 2) {
                                                    echo "selected";
                                                } ?>>Aktif</option>
                            <option value="1" <?php if ($settings["avarage"] == 1) {
                                                    echo "selected";
                                                } ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Sağlayıcı Değişen Servis</strong>
                        <select class="form-select" name="ser_sync">
                            <option value="2" <?= $settings["ser_sync"] == 2 ? "selected" : null; ?>>Sadece Uyar</option>
                            <option value="1" <?= $settings["ser_sync"] == 1 ? "selected" : null; ?>>Uyar & Servisi Pasifleştir</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>SMS Doğrulama</strong>
                        <select class="form-select" name="sms_verify">
                            <option value="2" <?= $settings["sms_verify"] == 2 ? "selected" : null; ?>>Aktif</option>
                            <option value="1" <?= $settings["sms_verify"] == 1 ? "selected" : null; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Mail Doğrulama</strong>
                        <select class="form-select" name="mail_verify">
                            <option value="2" <?php if ($settings["mail_verify"] == 2) {
                                                    echo "selected";
                                                } ?>>Aktif</option>
                            <option value="1" <?php if ($settings["mail_verify"] == 1) {
                                                    echo "selected";
                                                } ?>>Pasif</option>
                        </select>
                    </div>
                </div>
                <!--<hr>
				<div class="row">
					<div class="col-md-4">
						<strong>Sipariş Başlangıç <i class="fas fa-question-circle"  data-bs-toggle="tooltip" data-bs-placement="top" title="Bu özellik sayesinde sipariş sayınızın başlangıcını arttırabilirsiniz."></i></strong>
						<input type="text" class="form-control" name="siparis_baslangic" value="54">
                	</div>
					<div class="col-md-4">
                    	<strong>Müşteri Başlangıç <i class="fas fa-question-circle"  data-bs-toggle="tooltip" data-bs-placement="top" title="Bu özellik sayesinde müşteri sayınızın başlangıcını arttırabilirsiniz."></i></strong>
						<input type="text" class="form-control" name="musteri_baslangic" value="1">
                	</div>
					<div class="col-md-4">
                    	<strong>Servis Başlangıç <i class="fas fa-question-circle"  data-bs-toggle="tooltip" data-bs-placement="top" title="Bu özellik sayesinde servis sayınızın başlangıcını arttırabilirsiniz."></i></strong>
						<input type="text" class="form-control" name="servis_baslangic" value="45">
                	</div>
				</div> -->
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Header Kod Alanı <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Bu alana gireceğiniz kodlar temanın üst kısmında yer alır."></i></strong>
                        <div>
                            <textarea class="form-control" name="custom_header" id="exampleFormControlTextarea1" rows="3"><?= $settings["custom_header"] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Footer Kod Alanı <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Bu alana gireceğiniz kodlar temanın alt kısmında yer alır."></i></strong>
                        <div>
                            <textarea class="form-control" name="custom_footer" id="exampleFormControlTextarea1" rows="3"><?= $settings["custom_footer"] ?></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Google Ads Veri Gönderme (Her yer) <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Bu alana gireceğiniz kodlar Google adsye siteye giren herkesin verisini yollar."></i></strong>
                        <div>
                            <textarea class="form-control" name="google_ads_all" id="exampleFormControlTextarea1" rows="3"><?= $settings["google_ads_all"] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Google Ads Veri Gönderme (Sadece Siparişler) <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Bu alana gireceğiniz kodlar Google adsye sitenizden sipariş veren herkesin verisini yollar ."></i></strong>
                        <div>
                            <textarea class="form-control" name="google_ads_odeme" id="exampleFormControlTextarea1" rows="3"><?= $settings["google_ads_odeme"] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Referans Komisyon Oranı:</strong>
                        <div>
                            <input class="form-control" value="<?= $settings["ref_bonus"] ?>" name="ref_bonus" id="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Referans Minimum Ödeme Eşiği:</strong>
                        <div>
                            <input class="form-control" name="ref_max" value="<?= $settings["ref_max"] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Güncelleme Sistemi (Son kaç güncelleme gösterilsin?):</strong>
                        <div>
                            <input class="form-control" name="up_limiti" value="<?= $settings["up_limiti"] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Proxy Sistemi (Satın almazsanız çalışmaz):</strong>
                        <select class="form-select" name="proxy_mode">
                            <option value="0" <?php if ($settings["proxy_mode"] == 0) {
                                                    echo "selected";
                                                } ?>>Pasif</option>
                            <option value="1" <?php if ($settings["proxy_mode"] == 1) {
                                                    echo "selected";
                                                } ?>>Aktif</option>
                        </select>

                    </div>
                </div>

                <button class="btn btn-primary">Güncelle</button>
            </form>
        </div>
    </div>
</div>