<?= view('admin/yeni_admin/static/header'); ?>
<script>

        tinymce.init({
            selector: 'textarea',
        plugins: [
          'a11ychecker','advlist','quickbars','blocks','advcode','advtable','autolink','checklist','export',
          'lists','link autolink','charmap','preview','anchor','searchreplace','visualblocks',
          'powerpaste','fullscreen','formatpainter','insertdatetime','media','image','code','table','wordcount'
        ],
        
        toolbar: 'undo redo | link image formatpainter casechange | bold italic forecolor  backcolor color | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code'
      });
    </script>
<div class="container-fluid px-sm-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-0">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="card-header-title text-center text-sm-start"> <?= $ticketMessage[0]["subject"] ?><?php echo ' <span class="service-block__provider-value">';
               if($ticketMessage[0]["support_new"] == 2){
                                           echo'  <i class="fa fa-eye-slash"></i> Henüz Görülmedi';
                                           }elseif ($ticketMessage[0]["support_new"] == 1){
                                           echo'  <i class="fa fa-eye"></i> Görüldü';
                                           } ?></span></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url("admin/tickets/read/" . $ticketMessage[0]["ticket_id"]) ?>"
                          data-id="<?= $ticketMessage[0]["ticket_id"] ?>"
                          method="post">
                        <input type="hidden" id="t_id" name="t_id" value="<?= $ticketMessage[0]["ticket_id"] ?>">
                        <input type="hidden" id="t_baslik" name="t_baslik" value="<?= $ticketMessage[0]["subject"] ?>">
                        <div class="row align-items-center">
                            <div class="col-md-2 order-3 order-sm-1">
                                <div class="card mt-3 mt-sm-0 text-center">
                                    <div class="card-header">
                                        Müşteri Hesabı
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $ticketMessage[0]['username'] ?></h5>
                                        <img src="https://picsum.photos/150/150" class="rounded mb-4">
                                        <p class="card-text">
                                            <strong>Bakiye: </strong><span><?= $ticketMessage[0]['balance'] ?>₺</span><br>
                                            <strong>Harcama: </strong><span><?= $ticketMessage[0]['spent'] ?>₺</span><br>
                                            <strong>Sipariş Sayısı: </strong><span><?= $total_order ?></span>
                                        </p>
                                        <a href="<?= base_url("admin/client-detail/".$ticketMessage[0]['client_id'])?>" target="_blank" class="btn-glycon btn-glycon-primary btn-block">Üye Profili</a>
                                    </div>
                                    <div class="card-footer text-muted">
                                        <?php
                                        $ticketSender = $ticketMessage[0]['username'];
                                        ?>
                                        <a href="<?php echo base_url("admin/tickets/?search=$ticketSender&search_type=client"); ?>"
                                           class="text-muted fw-bold">Destek Talepleri</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 order-1 order-sm-2">
                                <div class="card">
                                    <div class="card-header">
                                        <select class="select2 w-100" id="hazir_cevap">
                                            <option value="-1">Hazır Cevap Seçin</option>
                                            <?php foreach ($ready as $r): ?>

                                                <option value="<?= $r['id'] ?>"><?= $r['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDelete"
                                           class="btn-glycon btn-glycon-danger mt-2 py-0">
                                            Seçili Hazır Cevabı Sil
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#confirmReady"
                                           class="btn-glycon btn-glycon-success mt-2 py-0">
                                            Hazır Cevap Kaydet
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <textarea name="message" id="tinymce"></textarea>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn-glycon btn-glycon-success mt-2">
                                                Gönder
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 order-2 order-sm-3">
                                <div class="card mt-3 mt-sm-0 text-center">
                                    <div class="card-header text-center">
                                        Genel
                                    </div>
                                    <div class="card-body  text-start py-3">
                                        <label class="mt-1 text-start">Talep Durumu</label>
                                        <select class="select2 w-100" id="talep_degis">
                                            <option value="pending" <?= $ticketMessage[0]['status'] == "pending" ? 'selected' : "" ?> >
                                                Yanıt Bekliyor
                                            </option>
                                            <option value="answered" <?= $ticketMessage[0]['status'] == "answered" ? 'selected' : "" ?>>
                                                Yanıtlandı
                                            </option>
                                            <option value="closed" <?= $ticketMessage[0]['status'] == "closed" ? 'selected' : "" ?>>
                                                Çözümlendi
                                            </option>
                                        </select>
                                        <hr>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                   id="defaultCheck1">
                                            <label class="form-check-label text-start" for="defaultCheck1">
                                                Talebi Kilitle
                                            </label>
                                        </div>
                                        <hr>
                                        <label class="fs-7">Oluşturulma</label>
                                        <span class="olusturulma-tarihi"><?= $ticketMessage[0]['time'] ?></span>
                                        <hr>
                                        <label class="fs-7 mb-1">Güncellenme</label>
                                        <span class="olusturulma-tarihi"><?= $ticketMessage[0]['lastupdate_time'] ?></span>
                                        <hr>
                                        <label class="mt-1 text-start">Talep Oluştur</label>
                                        <select class="form-select w-100" id="ajax_talep_site">
                                            <?php
                                            foreach ($services_api as $api) {
                                                $decode = json_decode($api['api_json'], true);
                                                if (isset($decode['software'])) {
                                                    ?>
                                                    <option data-key="<?= $api['api_key'] ?>"
                                                            value="<?= $api['api_url'] ?>" <?= isset($ticket_url[$api['api_name']]) ? 'disabled' : '' ?>><?= $api['api_name'] ?></option>
                                                    <?php
                                                }
                                            } ?>
                                            <?php
                                            $ticket_url = json_decode($ticketMessage[0]['panel_id'], true);
                                            ?>
                                        </select>
                                        <a href="javascript::void(0)" class="btn btn-primary mt-1 py-1 w-100"
                                           id="ticket_talep">Oluştur</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <?php foreach ($ticketMessage as $message): if ($message["support"] == 2): ?>
                                <div class="col-md-12">
                                    <div class="tickets">
                                        <div class="card my-3">
                                            <div class="card-header card-header-admin">
                                                Destek Ekibi
                                                <div class="glycon-badge badge-primary mx-3">Yönetici</div>
                                                <a class="pointer text-white text-decoration-none"
                                                   style="font-weight:400;" data-bs-toggle="modal"
                                                   data-bs-target="#modalDiv" data-action="edit_ticket"
                                                   data-id="<?= $message['id'] ?>">Düzenle</a>
                                                <div class="glycon-badge badge-info float-end"><?= $message["time"] ?>

                                                </div>
                                            </div>
                                            <div class="card-body"><?= $message["message"] ?> </div>

                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <div class="tickets">
                                        <div class="card my-3">
                                            <div class="card-header card-header-admin">
                                                <?= $message["username"] ?>
                                                <div class="glycon-badge badge-success mx-3">Müşteri</div>
                                                <a class="pointer text-white text-decoration-none"
                                                   style="font-weight:400;" data-bs-toggle="modal"
                                                   data-bs-target="#modalDiv" data-action="ticketSend_provider"
                                                   data-id="<?= $message['id'] ?>">Sağlayıcıya Gönder</a>
                                                <div class="glycon-badge badge-info float-end"><?= $message["time"] ?>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <?= str_replace("</script>", "</ script >", str_replace("<script>", "< script >", $message["message"])) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-center fade" id="confirmReady" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>Hazır Cevap Olarak Kaydedilsin Mi?</h4>
                <div class="form-group">
                    <label for="">Hazır Cevap Kısa Ad</label>
                    <input type="text" id="kisa_ad" value="">
                </div>
                <div align="center">
                    <a class="btn btn-primary" href="#" id="confirmYes">Evet</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hayır</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-center fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>Seçili Hazır Cevap Silinsin Mi?</h4>
                <div class="form-group">
                </div>
                <div align="center">
                    <a class="btn btn-primary" href="#" id="hazir_cevap_sil">Evet</a>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Hayır</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view('admin/yeni_admin/static/footer'); ?>
<script>
    $('#hazir_cevap').change(function () {
        var value = $(this).val();
        $.post(window.base_url + "/admin/tickets/getReady", {id: value}, function (data) {
            tinymce.get("tinymce").setContent(data.content);

        }, 'json');
    });
    $('#confirmYes').click(function () {
        var titles = $('#kisa_ad').val();
        var contents = tinymce.get("tinymce").getContent();
        $.post(window.base_url + "/admin/tickets/ready", {content: contents, title: titles}, function (data) {
            if (data.m == "okey") {
                $.toast({
                    heading: "Hazır Cevap",
                    text: "Başarıyla Kaydedildi",
                    icon: "success",
                    loader: true,
                    loaderBg: "#3C763D"
                });
            }

        }, 'json');
    });

    $('#hazir_cevap_sil').click(function () {
        var value = $('#hazir_cevap').val();
        if (value != "-1") {
            $.post(window.base_url + "/admin/tickets/deleteReady", {id: value}, function (data) {
                $.toast({
                    heading: "Hazır Cevap",
                    text: "Başarıyla Silindi",
                    icon: "success",
                    loader: true,
                    loaderBg: "#3C763D"
                })
                location.reload();

            }, 'json');
        }
    });
</script>
<script>
    function button_saglayici() {
        var desc = $('#description_saglayici').html();
        var select = $('#select_saglayici').val();
        var saglayici = $('#select_saglayici').find(':selected').html();
        var key = $('#select_saglayici').find(':selected').data('key');
        $.post(saglayici, {
            key: key,
            action: 'replyTicket',
            id: select,
            message: desc
        }, function (data) {
            if (data.status == 202) {
                $.toast({
                            heading: "Talebe Cevap Gönderildi",
                            text: "Başarıyla " + $('#ajax_talep_site').val() + " Adresinde cevap oluşturuldu",
                            icon: "success",
                            loader: true,
                            loaderBg: "#3C763D"
                        });
            }
            console.log(data.ticket_id);
        }, 'json');

    }
</script>