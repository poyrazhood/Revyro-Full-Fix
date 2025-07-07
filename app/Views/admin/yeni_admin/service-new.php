<?= view('admin/yeni_admin/static/header'); ?>


    <div class="container-fluid px-5">
    <div class="row">
        <div class="bgimg-glycon bg-gradient rounded p-4">
            <div class="col-12 text-center client-detail">
                <strong class="fs-2 text-white" id="servis_ismi">
                    <?php
                    foreach ($languages as $language) {
                        if ($language["default_language"]) {
                            echo $language["language_code"];
                        }
                    }
                    ?>


                </strong>
            </div>
        </div>
        <div class="shadow mt-3 py-2">
            <div class="col-12">
                <!-- Tabs navs -->
                <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab"
                           aria-controls="ex1-tabs-1" aria-selected="true"> Türkçe
                        </a>
                    </li>
                </ul>
                <!-- Tabs navs -->

                <!-- Tabs content -->
                <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                        <div class="col-md-12">
                            <form class="needs-validation" action="<?= base_url("admin/services/new-service") ?>"
                                  method="post" data-xhr="true">
                                <div class="row g-3">
                                    <?php

                                    foreach ($languages as $language) {
                                        if ($language["default_language"]) {
                                            ?>

                                            <div class="col-sm-4">
                                                <label for="firstName" class="form-label">Servis İsmi
                                                    <small><?= $language["language_name"] ?></small></label>
                                                <input type="text" class="form-control" id="sevis_ismi_degis"
                                                       onkeyup="servisismi()"
                                                       name="name[<?= $language["language_code"] ?>]" placeholder=""
                                                       value=""
                                                       required="">
                                            </div>
                                        <?php } else { ?>

                                            <div class="col-sm-4">
                                                <label for="firstName" class="form-label">Servis İsmi
                                                    <small><?= $language["language_name"] ?></small></label>
                                                <input type="text" class="form-control" id="sevis_ismi_degis"
                                                       onkeyup="servisismi()"
                                                       name="name[<?= $language["language_code"] ?>]" placeholder=""
                                                       value=""
                                                       required="">
                                            </div>

                                        <?php }
                                    } ?>
                                    <div class="col-sm-4">
                                        <label for="lastName" class="form-label">Kategori</label>
                                        <select class="form-select" name="category" required="">
                                            <?php
                                            foreach ($categories as $category) {
                                                echo "<option value=\"" . $category["category_id"] . "\"";

                                                echo ">" . $category["category_name"] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="lastName" class="form-label">Servis Tipi</label>
                                        <select class="form-select" name="package" required="">
                                            <?php
                                            echo "<option value=\"1\">Servis</option>\r\n <option value=\"2\"";
                                            echo ">Paket</option>\r\n <option value=\"3\"";
                                            echo ">Özel Yorum</option>\r\n <option value=\"4\"";
                                            echo ">Paket Yorum</option><option value=\"5\"";
                                            echo ">Yorum Beğeni</option><option value=\"6\"";
                                            echo ">Oylama</option>";

                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="aciklama" class="form-label">Açıklama</label>
                                        <textarea class="form-control" id="tinymce" name="aciklama"
                                                  id="aciklama"></textarea>
                                    </div>
                                    <hr class="mb-0">
                                    <div class="col-sm-">
                                        <label for="lastName" class="form-label">Mod</label>
                                        <select class="form-select" name="mode" id="serviceMode" required="">
                                            <option value="1"<?php
                                            echo ">Manuel</option><option value=\"2\"";

                                            echo ">Otomatik (API)</option>" ?>
                                        </select>
                                    </div>

                                    <div id="autoMode" style="display: none">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="service-mode__block">
                                                    <div class="form-group">
                                                        <label for="provider" class="">Servis Sağlayıcı</label>
                                                        <select class="form-select" id="provider" name="provider"
                                                        >
                                                            <?php
                                                            foreach ($providers as $provider) {
                                                                echo "<option value=\"" . $provider["id"] . "\"";

                                                                echo ">" . $provider["api_name"] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">

                                                <div id="provider_service">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="ikiliislem" id="ikiliislem"
                                                           onchange="ikilicheckbox(this);">
                                                    <label class="form-check-label" for="ikiliislem">
                                                        Servis Birleştirme
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" id="ikili_servis_select" style='display:none;'>
                                                <div class="form-group">
                                                    <label for="ikili_servis_select" class="">Servis
                                                        Sağlayıcı
                                                        2</label>
                                                    <select class="form-select" id="provider_iki"
                                                            name="provider_2"
                                                    >
                                                        <?php
                                                        foreach ($providers as $provider) {
                                                            echo "<option value=\"" . $provider["id"] . "\"";

                                                            echo ">" . $provider["api_name"] . "</option>";
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div id="provider_service_iki" style='display:none;'>
                                                </div>

                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-check" style="display:none" id="ikiislem">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="siraliislem"
                                                           id="siraliislem">
                                                    <label class="form-check-label" for="siraliislem">
                                                        Aynı Tür <small>(2 Takipçi Servisi, 2 Beğeni Servisi)</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Dripfeed</label>
                                            <select class="form-control" name="dripfeed">
                                                <option value="1">Pasif</option>
                                                <option value="2">Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr class="mb-0">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-group__service-name" id="datasnamesmanuel">1000 Adet Ücreti
                                                </label>
                                            <label class="form-group__service-name" id="datasnames" style="display:none;">1000 Adet Ücreti
                                                </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="priceInput" name="price"
                                                   value="<?= $serviceInfo["service_price"] ?>" <?= $serviceInfo["sync_price"] == 1 ? 'style="display: none;"' : "" ?>>
                                        </div>
                                        <div id="priceThreeInput" <?= $serviceInfo["sync_price"] == 0 ? 'style="display: none;"' : "" ?>>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" style="border-radius:5px;"
                                                           id="priceInput" name="sync_rate_sabit"
                                                           placeholder="Kar Sabit Fiyat"

                                                           onkeyup="FiyatDegis(this)" >
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" style="border-radius:5px;"
                                                           id="priceInput" name="sync_rate" placeholder="Kar Oranı"

                                                           onkeyup="FiyatDegis(this)" >
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><?= $settings["site_currency"] ?></div>
                                                        </div>
                                                        <input type="text" value="<?= $serviceInfo["service_price"] ?>"
                                                               readonly="" id="fiyat_site_degis"
                                                               data-price="<?= $serviceInfo["service_price"] ?>"
                                                               class="form-control" placeholder="">

                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text ajaxdataerkan"
                                                                 data-fiyat="<?= $service_fiyat ?>" id="fiyat_api">

                                                                <?php
                                                                echo $service_fiyat;

                                                                ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <input type="hidden" name="price_api" id="ratePriceInput"
                                                       value="<?= $s_x; ?>">

                                            </div>

                                        </div>
                                        <div class="input-group-addon">
                                            <label class="switch"><input id="priceCheckbox" type="checkbox"
                                                                         name="auto_price" style="display:none;" <?= $serviceInfo["sync_price"] == 1 ? 'checked' : "" ?>>
                                                <span class="slider round"></span>
                                            </label>

                                        </div>

                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text ajaxdataerkan" id="minText"></div>
                                            </div>
                                            <input type="text" value="10" id="minPriceInput" name="min"
                                                   class=" form-control" placeholder="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><label class="switch"><input
                                                                id="minPriceCheckbox" type="checkbox" name="auto_min"
                                                                ><span class="slider round"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text ajaxdataerkan" id="maxText"></div>
                                            </div>
                                            <input type="text" value="20" id="maxPriceInput" name="max"
                                                   class=" form-control" placeholder="" >
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><label class="switch"><input
                                                                id="maxPriceCheckbox" type="checkbox" name="auto_max"
                                                                ><span class="slider round"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-0">
                                    <div class="form-group col-sm-6">
                                        <label>İptal butonu</label>
                                        <select class="form-control" name="cancel_type">
                                            <option value="2">
                                                Aktif
                                            </option>
                                            <option value="1" selected>
                                                Pasif
                                            </option>
                                        </select>
                                    </div>


                                    <div class="form-group col-sm-6">
                                        <label>Refill Butonu</label>
                                        <select id="refill" class="form-control" name="refill_type">
                                            <option value="2">
                                                Aktif
                                            </option>
                                            <option value="1" selected>
                                                Pasif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <div id="refill_day" class="form-group" style="display: none;">
                                            <label>Refill Maksimum Gün <small>(Lifetime ise 0 yazınız)</small></label>
                                            <input type="number" class="form-control" name="refill_time"
                                                   value="">
                                            <label>Refill Çıkma Süresi <small>(Default: 24 Saat)</small></label>
                                            <input type="number" class="form-control" name="refill_min"
                                                   value="<?= $serviceInfo["refill_min"] ?>">
                                        </div>
                                    </div>
                                    <hr class="mb-0">
                                    <div class="col-sm-4">
                                        <label for="rep_link" class="form-label">Aynı Link</label>
                                        <select class="form-control" name="rep_link">
                                            <option value="1">
                                                Sipariş Al
                                            </option>
                                            <option value="2">
                                                Sipariş Alma
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" style="display:none">
                                        <select class="form-control" name="speed">
                                            <option value="4">
                                                Hızlı
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="lastName" class="form-label">Bağlantı Türü</label>
                                        <select class="form-control" name="want_username">
                                            <option value="1">
                                                Link
                                            </option>
                                            <option value="2">
                                                Kullanıcı adı
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="lastName" class="form-label">Kişiye Özel Servis</label>
                                        <select class="form-control" name="secret">
                                            <option value="2">
                                                Hayır
                                            </option>
                                            <option value="1">
                                                Evet
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <button class="my-2 mt-4 btn btn-glycon" type="submit">Güncelle</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div style="min-height: 50px;"></div>

    <?= view('admin/yeni_admin/static/footer'); ?>
    <script>
        var type = $("#refill").val();

        if (type == 1) {

            $("#refill_day").hide();

        } else {

            $("#refill_day").show();

        }

        $("#refill").change(function () {

            var type = $(this).val();

            if (type == 1) {

                $("#refill_day").hide();

            } else {

                $("#refill_day").show();

            }

        });

        /* minprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */
        $("#minPriceCheckbox").click(function () {
            var minPriceInput = $("#minPriceInput");
            var minText = $("#minText");
            if (!this.checked) {
                minPriceInput.removeAttr("readonly", "readonly");
            } else {
                minPriceInput.attr("readonly", "readonly");
                minPriceInput.val(minText.text());
            }
        });


        /* maxprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */
        $("#maxPriceCheckbox").click(function () {
            var maxPriceInput = $("#maxPriceInput");
            var maxText = $("#maxText");
            if (!this.checked) {
                maxPriceInput.removeAttr("readonly", "readonly");
            } else {
                maxPriceInput.attr("readonly", "readonly");
                maxPriceInput.val(maxText.text());
            }
        });


        /* maxprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */
        $("#priceCheckbox").click(function () {
            var priceInput = $("#priceInput");
            var priceThree = $("#priceThreeInput");
            if (this.checked) {
                priceInput.css("display", "none");
                priceThree.css("display", "block");
            } else {
                priceThree.css("display", "none");
                priceInput.css("display", "block");
            }
        });

        $(".other_services").click(function () {
            var control = $("#translationsList");
            if (control.attr("class") == "hidden") {
                control.removeClass("hidden");
            } else {
                control.addClass("hidden");
            }
        });
        var base_url = $("head base").attr("href");


        function getProviderServices(provider, base_url) {
            if (provider == 0) {
                $("#provider_service").hide();
            } else {
                $.post(base_url + "/admin/ajax_data", {
                    action: "providers_list",
                    provider: provider
                }).done(function (data) {
                    $("#provider_service").show();
                    $("#provider_service").html(data);
                }).fail(function () {
                    alert("Hata oluştu!");
                });
            }
        }

        function getProvider() {
            var mode = $("#serviceMode").val();
            if (mode == 1) {
                $("#autoMode").hide();
            } else {
                $("#autoMode").show();
            }
        }

        function getSalePrice() {
            var type = $("#saleprice_cal").val();
            if (type == "normal") {
                $("#saleprice").hide();
                $("#servicePrice").show();
            } else {
                $("#saleprice").show();
                $("#servicePrice").hide();
            }
        }

        function getSubscription() {
            var type = $("#subscription_package").val();
            if (type == "11" || type == "12") {
                $("#unlimited").show();
                $("#limited").hide();
            } else {
                $("#unlimited").hide();
                $("#limited").show();
            }
        }
    </script>
    <script>
        function servisismi() {
            $('#servis_ismi').html($('#sevis_ismi_degis').val());
        }
    </script>
    <script>

        function FiyatDegis(thiss) {
            Fiyatdisable(thiss);
            let api_fiyat = parseFloat($('#fiyat_api').html());
            //console.log($('#fiyat_api').data("fiyat"));
            if ($(thiss).attr('name') == "sync_rate") {
                var oran = parseFloat($(thiss).val())
                let islem = (api_fiyat * oran / 100) + api_fiyat;

                if (isNaN(islem)) {
                    var sabit_fiyat = parseFloat($('[name="sync_rate_sabit"]').val());
                    if (sabit_fiyat == "" || isNaN(sabit_fiyat) || isNaN(api_fiyat + sabit_fiyat)) {
                        $('#fiyat_site_degis').val(api_fiyat);
                    } else {
                        $('#fiyat_site_degis').val(api_fiyat + sabit_fiyat);

                    }
                } else {
                    $('#fiyat_site_degis').val(islem);
                }
            }
            if ($(thiss).attr('name') == "sync_rate_sabit") {
                var oran = parseFloat($(thiss).val())
                let islem = oran + api_fiyat;

                var firstValue = $(this).attr("data-status");
                $(this).attr("data-status", "true");

                if (isNaN(islem)) {
                    var sabit_fiyat = parseFloat($('[name="sync_rate"]').val());
                    if (sabit_fiyat == "" || isNaN(sabit_fiyat) || isNaN(api_fiyat + sabit_fiyat)) {
                        $('#fiyat_site_degis').val(api_fiyat);
                    } else {
                        $('#fiyat_site_degis').val((api_fiyat * sabit_fiyat / 100) + api_fiyat);

                    }
                } else {
                    $('#fiyat_site_degis').val(islem);
                }
            }
        };

        $(document).on('change', '[name="service"]', () => {
            if ($('[name="sync_rate_sabit"]').prop("disabled") && !$('[name="sync_rate"]').prop("sync_rate")) {
                //yüzdelik
                FiyatDegis($('[name="sync_rate"]'));
            } else if (!$('[name="sync_rate_sabit"]').prop("disabled") && $('[name="sync_rate"]').prop("sync_rate")) {
                FiyatDegis($('[name="sync_rate_sabit"]'));
            }
        })
        $(document).on('change', '[name="service2"]', () => {
            if ($('[name="sync_rate_sabit"]').prop("disabled") && !$('[name="sync_rate"]').prop("sync_rate")) {
                //yüzdelik
                FiyatDegis($('[name="sync_rate"]'));
            } else if (!$('[name="sync_rate_sabit"]').prop("disabled") && $('[name="sync_rate"]').prop("sync_rate")) {
                FiyatDegis($('[name="sync_rate_sabit"]'));
            }
        })

        function Fiyatdisable(thiss) {
            var oran = parseFloat($(thiss).val())
            if ($(thiss).attr('name') == "sync_rate") {
                if (isNaN(oran)) {
                    $('[name="sync_rate_sabit"]').prop("disabled", false);
                    $('[name="sync_rate_sabit"]').val("");
                } else {

                    $('[name="sync_rate_sabit"]').prop("disabled", true);
                    $('[name="sync_rate_sabit"]').val("Yüzdelik Aktif");
                }
            } else {
                if (isNaN(oran)) {
                    $('[name="sync_rate"]').prop("disabled", false);
                    $('[name="sync_rate"]').val("");
                } else {

                    $('[name="sync_rate"]').prop("disabled", true);
                    $('[name="sync_rate"]').val("Sabit Kar Aktif");
                }
            }
        }
        $(document).ready(function{
            serviceMode();
        })
    </script>
<?= "</script>\r\n          <script>\r\n\r\n          var type = \$(\"#refill\").val();\r\n\r\n          if( type == 1 ){\r\n\r\n            \$(\"#refill_day\").hide();\r\n\r\n          } else{\r\n\r\n            \$(\"#refill_day\").show();\r\n\r\n          }\r\n\r\n          \$(\"#refill\").change(function(){\r\n\r\n            var type = \$(this).val();\r\n\r\n              if( type == 1 ){\r\n\r\n                \$(\"#refill_day\").hide();\r\n\r\n              } else{\r\n\r\n                \$(\"#refill_day\").show();\r\n\r\n              }\r\n\r\n          });\r\n\r\n          \$(\".other_services\").click(function(){\r\n            var control = \$(\"#translationsList\");\r\n            if( control.attr(\"class\") == \"hidden\" ){\r\n              control.removeClass(\"hidden\");\r\n            } else{\r\n              control.addClass(\"hidden\");\r\n            }\r\n          });\r\n          </script>" ?>