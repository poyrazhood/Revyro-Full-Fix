<style>
.providerss thead {
    background: #0b1f40;
    color: white;
}
</style>
<div class="col-md-10">
    <?php if ($success) : ?>
    <div class="alert alert-success "><?php echo $successText; ?></div>
    <?php endif; ?>
    <?php if ($error) : ?>
    <div class="alert alert-danger "><?php echo $errorText; ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            Sağlayıcılar
        </div>
        <div class="card-body providerss">
            <button class="btn btn-white text-dark border" data-bs-toggle="modal" data-bs-target="#modalDiv"
                data-action="new_provider">Yeni Sağlayıcı Ekle</button>
            <a href="/admin/settings/providers" class="btn btn-default m-b">Sağlayıcılar</a>
            <a href="/admin/settings/providers/suggestion" class="btn btn-default m-b">Önerdiğimiz Sağlayıcılar</a>
            <hr>


            <?php if (!route(4)) : ?>
            <div class="providers"></div>
            <?php elseif (route(4) == "suggestion") : ?>
            <div class="card-body p-0">
                <table class="table border table-striped m-0">
                    <thead class="card-glycon text-white">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="icon-animation">
                        <?php foreach ($datacik as $apidatas) {
                                $s_model = model("service_api");
                                $proquery = $s_model->where("api_url", $apidatas["apiurl"])->get()->getResultArray();
                                $count = count($proquery);
                                //$proquery = $conn->query("SELECT * FROM service_api WHERE api_url = '{$apidatas["apiurl"]}'", PDO::FETCH_ASSOC);
                            ?>
                        <tr>
                            <td style="">
                                <div style="font-size: 14px;" class="settings-emails__row-name"><?= $apidatas["name"] ?>
                                </div>
                                <div style="font-size: 12px;" class="settings-emails__row-description">
                                    <?= $apidatas["reklam"] ?></div>
                            </td>
                            <td style="padding: 10px 12px;text-align: right;"><button style="font-size: 12px;"
                                    data-bs-toggle="modal" data-bs-target="#managerModal<?= $apidatas["code"] ?>"
                                    class="btn <?php if ($count) {
                                                                                                                                                                                                                    echo "btn-danger";
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    echo "btn-success";
                                                                                                                                                                                                                } ?> btn-sm py-0">Detaylar</button>
                            </td>
                        </tr>
                        <div class="modal fade in" style="padding: 0px 0px;text-align: left;"
                            id="managerModal<?= $apidatas["code"] ?>" data-backdrop="static" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title" id="modalTitle"><?= $apidatas["name"] ?></h5>
                                        </div>
                                        <i data-bs-dismiss="modal" aria-label="Close" class="fas fa-times text-light"
                                            style="cursor:pointer;" aria-hidden="true"></i>
                                    </div>
                                    <form class="form" action="<?= base_url("admin/settings/providers/new") ?>"
                                        method="post" data-xhr="true">
                                        <input type="hidden" name="url" value="<?= $apidatas["apiurl"] ?>">
                                        <div class="modal-body" style="padding: 0px;">

                                            <div class="modal-body">
                                                <div class="form edit-integration-modal-body">
                                                    <?php if ($apidatas["gif"] != NULL) { ?>
                                                    <div class="form-group field-editintegrationform-code">
                                                        <a href="<?= $apidatas["url"] ?>" target="_blank">
                                                            <img style="width:100%;" src="<?= $apidatas["gif"] ?>">
                                                        </a>
                                                    </div>
                                                    <?php } ?>
                                                    <style>
                                                    .butonres {
                                                        margin-left: .5rem;
                                                    }

                                                    @media screen and (max-width: 995px) {
                                                        .butonres {
                                                            width: 100%;
                                                            margin-left: 0rem;
                                                        }
                                                    }
                                                    </style>
                                                    <?php if (!$count) { ?>
                                                    <div class="form-group field-editintegrationform-code"
                                                        style="margin-top:.5rem;">
                                                        <label class="control-label"
                                                            for="editintegrationform-code"></label>
                                                        <input class="form-control" name="key"
                                                            placeholder="Api Keyinizi Giriniz." value="">
                                                    </div>
                                                    <?php } ?>
                                                    <div class="form-group field-editintegrationform-code"
                                                        style="margin-top:.5rem;">
                                                        <?php if (!$count) { ?>
                                                        <button style="border-color: #ccc;margin-left:0rem!important;"
                                                            class="btn btn-default butonres" type="submit">Siteme Apiyi
                                                            Ekle</button>
                                                        <?php } ?>
                                                        <?php if ($apidatas["url"] != null) { ?><a
                                                            style="border-color: #ccc;"
                                                            <?php if($apidatas['apiurl'] == NULL){ ?>style="margin-left:0rem!important;"
                                                            <?php } ?> target="_blank" href="<?= $apidatas["url"] ?>"
                                                            class="btn btn-default butonres">Siteyi Ziyaret
                                                            Et</a><?php } ?>
                                                        <?php if ($apidatas["services"] != null) { ?><a
                                                            style="border-color: #ccc;"
                                                            <?php if($apidatas["url"] == null && $provider['api_url'] != $apidatas["apiurl"]){ ?>style="margin-left:0rem!important;"
                                                            <?php } ?> target="_blank"
                                                            href="<?= $apidatas["services"] ?>"
                                                            class="btn btn-default butonres">Servisleri
                                                            İncele</a><?php } ?>
                                                        <?php if ($apidatas["register"] != null) { ?><a
                                                            style="border-color: #ccc;"
                                                            <?php if($apidatas["url"] == null || $apidatas["services"] == null && $provider['api_url'] != $apidatas["apiurl"]){ ?>style="margin-left:0rem!important;"
                                                            <?php } ?> target="_blank"
                                                            href="<?= $apidatas["register"] ?>"
                                                            class="btn btn-default butonres">Kayıt Ol ve api key
                                                            Oluştur</a><?php } ?>
                                                    </div>
                                                    <?php if ($apidatas["ekstra"] != null) { ?>
                                                    <hr>
                                                    <div class="form-group field-editintegrationform-code"
                                                        style="overflow: auto;word-wrap: break-word; max-height:50rem;">
                                                        <?= $apidatas["ekstra"] ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!--<button type="submit" class="btn btn-primary">
                                                                Güncelle </button>-->
                                                <button type="button" class="btn btn-default" data-bs-dismiss="modal"
                                                    aria-label="Close" aria-hidden="true">
                                                    Kapat </button>
                                            </div>

                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>