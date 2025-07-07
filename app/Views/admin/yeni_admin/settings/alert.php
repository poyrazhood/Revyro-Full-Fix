<div class="col-md-10">

    <div class="card">
        <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    ss
                     <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
                    <div class="card-header-title">Bildirim Ayarları</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>API Düşük Bakiye</p>
                            <?php if ($settings["alert_apibalance"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_apibalance"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_apibalance"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Bakiyeniz belirlediğiniz tutarın altına düşerse bir bildirim
                                alırsınız.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Yeni Manuel Sipariş Bildirimi</p>
                            <?php if ($settings["alert_newmanuelservice"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_newmanuelservice"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_newmanuelservice"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?></div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Yeni manuel siparişler alınırsa bildirim gönderilir.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Yeni Ödeme Bildirimi</p>
                            <?php if ($settings["alert_newpayment"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_newpayment"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_newpayment"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Yeni ödeme alınırsa bildirim gönderilir.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Yeni Banka Ödeme Talebi Bildirimi</p>
                            <?php if ($settings["alert_newbankpayment"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_newbankpayment"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_newbankpayment"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Yeni ödeme talebi alınırsa bildirim gönderilir.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Yeni Başarısız Sipariş Bildirimi</p>
                            <?php if ($settings["alert_failorder"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_failorder"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_failorder"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Yeni başarısız (Fail) sipariş alınırsa bildirim
                                gönderilir.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Yeni Destek Bildirimi</p>
                            <?php if ($settings["alert_newticket"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_newticket"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_newticket"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Yeni destek talebi oluşturulduğunda bildirim alırsınız.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p>Değişen Sağlayıcı Bilgileri</p>
                            <?php if ($settings["alert_serviceapialert"] == 1) { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/on/alert_serviceapialert"
                                   class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-check"
                                                                                              aria-hidden="true"></i>
                                    Aktifleştir</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/admin/settings/alert/off/alert_serviceapialert"
                                   class="btn-glycon btn-glycon-danger btn-glycon-module"><i class="fas fa-times"
                                                                                             aria-hidden="true"></i>
                                    Pasifleştir</a>

                            <?php } ?>
                        </div>
                        <div class="glycon-module-info">
                            <p class="glycon-module-desc">Servis Sağlayıcısında Servisler İle İlgili Tüm Güncellemerden
                                bildirim alırsınız.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Bildirim Alacak Mail Adresi</label>
                            <input type="text" class="form-control" name="admin_mail"
                                   value="<?= $settings["admin_mail"] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Bildirim Alacak Telefon Numarası</label>
                            <input type="text" class="form-control" name="admin_telephone"
                                   value="<?= $settings["admin_telephone"] ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mb-2">

                            <label class="form-label fw-bold">Bildirim Şekli</label>
                            <select class="form-control" name="alert_type">
                                <option value="3" <?= $settings["alert_type"] == 3 ? "selected" : null; ?> >Mail ve
                                    SMS
                                </option>
                                <option value="2" <?= $settings["alert_type"] == 2 ? "selected" : null; ?>>Mail</option>
                                <option value="1" <?= $settings["alert_type"] == 1 ? "selected" : null; ?>>SMS</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label fw-bold">SMS İle Şifre Yenileme</label>
                            <select class="form-control" name="resetsms">
                                <option value="2" <?= $settings["resetpass_sms"] == 2 ? "selected" : null; ?> >Aktif
                                </option>
                                <option value="1" <?= $settings["resetpass_sms"] == 1 ? "selected" : null; ?>>Pasif
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label fw-bold">Mail İle Şifre Yenileme</label>
                            <select class="form-control" name="resetmail">
                                <option value="2" <?= $settings["resetpass_email"] == 2 ? "selected" : null; ?> >Aktif
                                </option>
                                <option value="1" <?= $settings["resetpass_email"] == 1 ? "selected" : null; ?>>Pasif
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 help-block">
                            <small>En sağlıklı bildirim şekli SMS'tir.</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="form-label fw-bold">SMS Sağlayıcı</label>
                            <select class="form-control" name="sms_provider">
                                <option value="bizimsms" <?= $settings["sms_provider"] == "bizimsms" ? "selected" : null; ?> >
                                    Bizimsms
                                </option>
                                <option value="netgsm" <?= $settings["sms_provider"] == "netgsm" ? "selected" : null; ?> >
                                    NetGSM
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label fw-bold">SMS Başlığı</label>
                            <input type="text" class="form-control" name="sms_title"
                                   value="<?= $settings["sms_title"] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label fw-bold">Kullanıcı adı</label>
                            <input type="text" class="form-control" name="sms_user"
                                   value="<?= $settings["sms_user"] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label fw-bold">Kullanıcı parola</label>
                            <input type="text" class="form-control" name="sms_pass"
                                   value="<?= $settings["sms_pass"] ?>">
                        </div>
                        <div class="col-md-12 help-block">
                            <small><i class="fa fa-warning"></i> <code>Bizim SMS</code> için parola kısmına API
                                Key'inizi yazınız.</small><br>
                            <small>SMS Başlığı yazarken türkçe karakterlere dikkat ediniz.</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label class="form-label fw-bold">E-Posta</label>
                            <input type="text" class="form-control" name="smtp_user"
                                   value="<?= $settings["smtp_user"] ?>">
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label class="form-label fw-bold">E-Posta Şifre</label>
                            <input type="text" class="form-control" name="smtp_pass"
                                   value="<?= $settings["smtp_pass"] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label fw-bold">SMTP Server</label>
                            <input type="text" class="form-control" name="smtp_server"
                                   value="<?= $settings["smtp_server"] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label fw-bold">SMTP Port</label>
                            <input type="text" class="form-control" name="smtp_port"
                                   value="<?= $settings["smtp_port"] ?>">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label fw-bold">SMTP Protokol</label>
                            <select class="form-control" name="smtp_protocol">
                                <option value="0" <?= $settings["smtp_protocol"] == 0 ? "selected" : null; ?> >Yok
                                </option>
                                <option value="tls" <?= $settings["smtp_protocol"] == "tls" ? "selected" : null; ?>>
                                    TLS
                                </option>
                                <option value="ssl" <?= $settings["smtp_protocol"] == "ssl" ? "selected" : null; ?>>
                                    SSL
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="form-label fw-bold">Mail Type</label>
                            <select class="form-control" name="smtp_type">
                                <option value="smtp" <?= $settings["smtp_type"] == "smtp" ? "selected" : null; ?> >SMTP
                                </option>
                                <option value="send_mail" <?= $settings["smtp_type"] == "send_mail" ? "selected" : null; ?>>
                                    SEND MAİL
                                </option>
                                <option value="mail" <?= $settings["smtp_type"] == "mail" ? "selected" : null; ?>>
                                    MAİL
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-glycon btn-glycon-success text-center"><i
                                        class="fas fa-cogs"></i> Güncelle
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

