
<?php
$sabonelik = array(14, 15);
$abonelik = array(11, 12, 13, 14, 15);

?>
<div class="container">
    <div class="row">
        <div class="bgimg-glycon bg-gradient rounded p-4">
            <?php $multiName = json_decode($serviceInfo["name_lang"], true); ?>
            <div class="col-12 text-center client-detail">
                <strong class="fs-2 text-white" id="servis_ismi">
                    <?php
                    foreach ($languages as $language) {
                        if ($language["default_language"]) {
                            echo $multiName[$language["language_code"]];
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
                        <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">
                            <?php
                            foreach ($languages as $language) {

                                if ($language["default_language"]) {
                                    echo $language["language_name"];
                                }
                            }
                            ?>
                        </a>
                    </li>
                </ul>
                <!-- Tabs navs -->

                <!-- Tabs content -->
                <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                        <div class="col-md-12">
                            <form class="needs-validation form" action="<?php if (in_array($serviceInfo["service_package"], $abonelik)) {
                                echo base_url("admin/services/edit-subscription/" . $serviceInfo["service_id"]);
                            } else {
                                echo base_url("admin/services/edit-service/" . $serviceInfo["service_id"]."?modalsave=1");
                            } ?>" method="post" data-xhr="true">
                                <div class="row g-3">
                                    <!-- **************SERVIS ISMI BASLANGICI**************** -->
                                    <?php foreach ($languages as $language) {
                                        if ($language["default_language"]) { ?>
                                            <div class="col-sm-4">
                                                <label for="firstName" class="form-label">Servis İsmi
                                                    <small><?= $language["language_name"] ?></small></label>
                                                <input type="text" class="form-control" id="sevis_ismi_degis" onkeyup="servisismi()" name="name[<?= $language["language_code"] ?>]" placeholder="" value="<?= $multiName[$language["language_code"]] ?>" required="">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-sm-4">
                                                <label for="firstName" class="form-label">Servis İsmi
                                                    <small><?= $language["language_name"] ?></small></label>
                                                <input type="text" class="form-control" id="sevis_ismi_degis" onkeyup="servisismi()" name="name[<?= $language["language_code"] ?>]" placeholder="" value="<?= $multiName[$language["language_code"]] ?>">
                                            </div>
                                        <?php }
                                    } ?>
                                    <!-- **************SERVIS ISMI SONU**************** -->
                                    <!-- **************KATEGORI BASLANGIC**************** -->
                                    <div class="col-sm-4">
                                        <label for="lastName" class="form-label">Kategori</label>
                                        <select class="form-select" name="category" required="">
                                            <?php
                                            foreach ($categories as $category) {
                                                echo "<option value=\"" . $category["category_id"] . "\"";
                                                if ($serviceInfo["category_id"] == $category["category_id"]) {
                                                    echo "selected";
                                                }
                                                echo ">" . $category["category_name"] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- **************KATEGORI SONU**************** -->
                                    <!-- **************SERVIS TIPI BASLANGIC**************** -->
                                    <div class="col-sm-12">
                                        <label for="lastName" class="form-label">Servis
                                            Tipi <?= $serviceInfo["service_package"] ?></label>
                                        <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                            <select class="form-select" disabled="" id="subscription_package">
                                                <?php
                                                echo "<option value=\"11\"";
                                                if ($serviceInfo["service_package"] == "11") {
                                                    echo "selected";
                                                }
                                                echo ">Instagram Otomatik Beğeni - Sınırsız</option><option value=\"12\"";
                                                if ($serviceInfo["service_package"] == "12") {
                                                    echo "selected";
                                                }
                                                echo ">Instagram Otomatik İzlenme - Sınırsız</option><option value=\"14\"";
                                                if ($serviceInfo["service_package"] == "14") {
                                                    echo "selected";
                                                }
                                                echo ">Instagram Otomatik Beğeni - Süreli</option><option value=\"15\"";
                                                if ($serviceInfo["service_package"] == "15") {
                                                    echo "selected";
                                                }
                                                echo ">Instagram Otomatik İzlenme - Süreli</option>"; ?>
                                            </select>
                                        <?php } else { ?>
                                            <select class="form-select" name="package" id="package" required="">
                                                <?php
                                                if ($serviceInfo["service_package"] == 1) {
                                                    echo "selected";
                                                }
                                                echo "<option value=\"1\">Servis</option>\r\n                          <option value=\"2\"";
                                                if ($serviceInfo["service_package"] == "2") {
                                                    echo "selected";
                                                }
                                                echo ">Paket</option>\r\n                          <option value=\"3\"";
                                                if ($serviceInfo["service_package"] == "3") {
                                                    echo "selected";
                                                }
                                                echo ">Özel Yorum</option>\r\n                          <option value=\"4\"";
                                                if ($serviceInfo["service_package"] == "4") {
                                                    echo "selected";
                                                }
                                                echo ">Paket Yorum</option><option value=\"5\"";
                                                if ($serviceInfo["service_package"] == "5") {
                                                    echo "selected";
                                                }
                                                echo ">Yorum Beğeni</option><option value=\"6\"";
                                                if ($serviceInfo["service_package"] == "6") {
                                                    echo "selected";
                                                }
                                                echo ">Oylama</option>";
                                                ?>



                                            </select>
                                        <?php } ?>

                                    </div>
                                    <!-- **************SERVIS TIPI SONU**************** -->
                                    <!-- **************ACIKLAMA BASLANGIC**************** -->
                                    <div class="col-sm-12">
                                        <label for="aciklama" class="form-label">Açıklama</label>
                                        <textarea class="form-control" id="tinymce<?= $serviceInfo["service_id"]?>" name="aciklama"><?= $serviceInfo['service_description'] ?></textarea>
                                    </div>
                                    <!-- **************ACIKLAMA SONU**************** -->
                                    <hr class="mb-0">
                                    <!-- **************MOD BASLANGIC**************** -->
                                    <div class="col-sm-">
                                        <label for="lastName" class="form-label">Mod</label>
                                        <select class="form-select" name="mode" id="serviceMode" required="">
                                            <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                <option value="1"
                                                <?php if ($serviceInfo["service_api"] == 0) {
                                                    echo "selected";
                                                }
                                                echo ">Manuel</option>";
                                            }
                                            echo '<option value="2"';
                                            if ($serviceInfo["service_api"] != 0) {
                                                echo "selected";
                                            }
                                            echo ">Otomatik (API)</option>" ?> </select>
                                    </div>
                                    <!-- **************MOD SONU**************** -->

                                    <div id="autoMode" style="display: none">
                                        <div class="row">
                                            <!-- **************SERVIS SAGLAYICI BASLANGIC**************** -->
                                            <div class="col-sm-6">
                                                <div class="service-mode__block">
                                                    <div class="form-group">
                                                        <label for="provider" class="">Servis Sağlayıcı</label>
                                                        <select class="form-select" id="provider" name="provider">
                                                            <?php
                                                            foreach ($providers as $provider) {
                                                                echo "<option value=\"" . $provider["id"] . "\"";
                                                                if ($serviceInfo["service_api"] == $provider["id"]) {
                                                                    echo "selected";
                                                                }
                                                                echo ">" . $provider["api_name"] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- **************SERVIS SAGLAYICI SONU**************** -->

                                            <!-- **************SERVIS SECICI BASLANGICI**************** -->
                                            <div class="col-sm-6">
                                                <div id="provider_service">
                                                    <?php if ($api_service) : ?>
                                                    <div class="service-mode__block">

                                                        <div class="form-group">

                                                            <label>Servis</label>
                                                            <select class="form-control select2" name="service">
                                                                <?php foreach ($api_service as $a_s) : ?>
                                                                    <?php if (isset($a_s->name)) : ?>
                                                                        <option data-max="<?= $a_s->max ?>" data-min="<?= $a_s->min ?>" data-price="<?= $a_s->rate ?>" value="<?= $a_s->service ?>" <?= $serviceInfo["api_service"] == $a_s->service ? "selected" : "" ?>><?= $a_s->service . " - " . $a_s->name . " - " . $a_s->rate. " - Min " . $a_ss->min . " - Max " . $a_ss->max ?></option>
                                                                    <?php endif;
                                                                endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- **************SERVIS SECICI SONU**************** -->
                                            <!-- **************SERVIS BIRLESTIRME BASLANGICI**************** -->
                                            <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>

                                            <?php } else { ?>
                                                <div class="col-sm-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" name="ikiliislem" id="ikiliislem" <?= $serviceInfo['birlestirme'] ? "checked" : "" ?> onchange="ikilicheckbox(this);">
                                                        <label class="form-check-label" for="ikiliislem">
                                                            Servis Birleştirme
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6" id="ikili_servis_select" style='<?= $serviceInfo['birlestirme'] ? "" : "display:none;" ?>'>
                                                        <div class="form-group">
                                                            <label for="ikili_servis_select" class="">Servis Sağlayıcı 2</label>
                                                            <select class="form-select" id="provider_iki" name="provider_2">
                                                                <?php
                                                                foreach ($providers as $provider) {
                                                                    echo "<option value=\"" . $provider["id"] . "\"";
                                                                    if ($serviceInfo["service_api2"] == $provider["id"]) {
                                                                        echo "selected";
                                                                    }
                                                                    echo ">" . $provider["api_name"] . "</option>";
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div id="provider_service_iki" style='<?= $serviceInfo['birlestirme'] ? "" : "display:none;" ?>'>
                                                            <?php if ($api_service2) : ?>
                                                                <div class="service-mode__block">

                                                                    <div class="form-group">

                                                                        <label>Servis</label>
                                                                        <select class="form-control select2" name="service2">
                                                                            <?php foreach ($api_service2 as $a_ss) : ?>
                                                                                <?php if (isset($a_ss->name)) : ?>
                                                                                    <option data-max="<?= $a_ss->max ?>" data-min="<?= $a_ss->min ?>" data-price="<?= $a_ss->rate ?>" value="<?= $a_ss->service ?>" <?= $serviceInfo["api_service2"] == $a_ss->service ? "selected" : "" ?>><?= $a_ss->service . " - " . $a_ss->name . " - " . $a_ss->rate . " - Min " . $a_ss->min . " - Max " . $a_ss->max ?></option>
                                                                                <?php endif;
                                                                            endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">

                                                        <div class="form-check" style="<?= $serviceInfo['birlestirme'] ? "" : "display:none;" ?>" id="ikiislem">
                                                            <input class="form-check-input" type="checkbox" value="1" name="siraliislem" id="siraliislem" <?= $serviceInfo['sirali_islem'] ? "checked" : "" ?>>
                                                            <label class="form-check-label" for="siraliislem">
                                                                Aynı Tür (2 Takipçi Servisi, 2 Beğeni Servisi)
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- **************SERVIS BIRLESTIRME SONU**************** -->
                                        </div>
                                    </div>
                                    <!-- **************DRIPFEED BASLANGICI**************** -->
                                    <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>

                                    <?php } else { ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Dripfeed</label>
                                                <select class="form-control" name="dripfeed">
                                                    <option value="1" <?= $serviceInfo["service_dripfeed"] == 2 ? "" : "selected" ?>>
                                                        Pasif
                                                    </option>
                                                    <option value="2" <?= $serviceInfo["service_dripfeed"] == 2 ? "selected" : "" ?>>
                                                        Aktif
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- **************DRIPFEED SONU**************** -->
                                    <hr class="mb-0">
                                    <?php if ($serviceInfo["service_api"] == 0) : ?>
                                    <!-- **************MANUAL SERVIS BASLANGICI**************** -->

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-group__service-name" id="datasnames">1000 Adet Ücreti <small style="color:#636363;">(Zarar etmemeniz için olması gereken en düşük fiyat = <b><?=$s_x?></b>)</small></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="priceInput" name="price" value="<?= $serviceInfo["service_price"] ?>" <?= $serviceInfo["sync_price"] == 1 ? 'style="display: none;"' : "" ?>>
                                        </div>
                                        <div id="priceThreeInput" <?= $serviceInfo["sync_price"] == 0 ? 'style="display: none;"' : "" ?>>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" style="border-radius:5px;" id="priceInput" name="sync_rate_sabit" placeholder="Kar Sabit Fiyat" value="<?= !$serviceInfo["sync_kar_oran"] ? $serviceInfo["sync_rate"] : "Yüzdelik Aktif" ?>" onkeyup="FiyatDegis(this)" <?= $serviceInfo["sync_kar_oran"] ? "disabled" : "" ?>>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" style="border-radius:5px;" id="priceInput" name="sync_rate" placeholder="Kar Oranı" value="<?= $serviceInfo["sync_kar_oran"] ? $serviceInfo["sync_rate"] : "Sabit Kar Aktif" ?>" onkeyup="FiyatDegis(this)" <?= !$serviceInfo["sync_kar_oran"] ? "disabled" : "" ?>>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><?= $settings["site_currency"] ?></div>
                                                        </div>
                                                        <input type="text" value="<?= $serviceInfo["service_price"] ?>" readonly="" id="fiyat_site_degis" data-price="<?= $serviceInfo["service_price"] ?>" class="form-control" placeholder="">

                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text ajaxdataerkan" data-fiyat="<?= $service_fiyat ?>" id="fiyat_api">

                                                                <?php
                                                                echo $service_fiyat;

                                                                ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <input type="hidden" name="price_api" id="ratePriceInput" value="<?= $s_x; ?>">

                                            </div>

                                        </div>
                                        <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                            <div class="input-group-addon">
                                                <label class=""><input id="priceCheckbox" type="checkbox" name="auto_price" <?= $serviceInfo["sync_price"] == 1 ? 'checked' : "" ?>>
                                                    <span class="slider round"></span>
                                                </label>

                                            </div>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="form-group__service-name" id="datasnames">Minimum</label>


                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text ajaxdataerkan" id="minText"><?= $s_x_min ?></div>
                                                    </div>
                                                    <input type="text" value="<?= $serviceInfo["service_min"] ?>" id="minPriceInput" name="min" class=" form-control" placeholder="" <?= $serviceInfo["sync_min"] == 1 ? "readonly" : "" ?>>
                                                    <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">

                                                                <label class=""><input id="minPriceCheckbox" type="checkbox" name="auto_min" <?= $serviceInfo["sync_min"] == 1 ? "checked" : "" ?>>
                                                                    <span class="slider round"></span>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </div>

                                            </div><div class="col-md-6 form-group">
                                                <label class="form-group__service-name" id="datasnames">Maksimum</label>


                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text ajaxdataerkan" id="maxText"><?= $s_x_max ?></div>
                                                    </div>
                                                    <input type="text" value="<?= $serviceInfo["service_max"] ?>" id="maxPriceInput" name="max" class=" form-control" placeholder="" <?= $serviceInfo["sync_max"] == 1 ? "readonly" : "" ?>>
                                                    <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">

                                                                <label class=""><input id="maxPriceCheckbox" type="checkbox" name="auto_max" <?= $serviceInfo["sync_max"] == 1 ? "checked" : "" ?>>
                                                                    <span class="slider round"></span>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- **************MANUAL SERVIS SONU**************** -->
                                        <?php else : ?>
                                            <!-- **************NORMAL FIYAT BASLANGIC**************** -->
                                            <?php if (in_array($serviceInfo["service_package"], $sabonelik)) { ?>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-group__service-name">Servis fiyatı</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="priceInput" name="limited_price" value="<?= $serviceInfo["service_price"] ?>">
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label class="form-group__service-name">Gönderi miktarı</label>
                                                        <input type="text" class="form-control" name="autopost" value="<?= $serviceInfo["service_autopost"] ?>">
                                                    </div>

                                                    <div class="col-md-6 form-group">
                                                        <label class="form-group__service-name">Sipariş miktarı</label>
                                                        <input type="text" class="form-control" name="limited_min" value="<?= $serviceInfo["service_min"] ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-group__service-name">Paket Süresi <small>(gün)</small></label>
                                                    <input type="text" class="form-control" name="autotime" value="<?= $serviceInfo["service_autotime"] ?>">
                                                </div>
                                            <?php } else { ?>
                                                <?php
                                                $service_fiyat = 0;
                                                foreach ($api_service as $service) {
                                                    if ($serviceInfo["api_service"] == $service->service) {
                                                        $s_x = $service->rate;
                                                        $s_x_min = $service->min;
                                                        $s_x_max = $service->max;
                                                        $service_fiyat = $service->rate;
                                                    }
                                                }
                                                if ($api_service2) :

                                                    foreach ($api_service2 as $a_ss) {
                                                        if ($serviceInfo["api_service2"] == $a_ss->service) {
                                                            $s_x_two = $a_ss->rate;
                                                            $s_x_min_two = $a_ss->min;
                                                            $s_x_max_two = $a_ss->max;
                                                            $service_fiyat2 = $a_ss->rate;
                                                        }}

                                                    if($s_x <= $s_x_two){
                                                        $s_x = $s_x_two;
                                                    }
                                                    if($service_fiyat <= $service_fiyat2){
                                                        $service_fiyat2 = $service_fiyat2;
                                                    }
                                                    if($s_x_min <= $s_x_min_two){
                                                        $s_x_min = $s_x_min_two*2;
                                                    }else{
                                                        $s_x_min = $s_x_min*2;
                                                    }
                                                    if($s_x_max >= $s_x_max_two){
                                                        $s_x_max = $s_x_max_two*2;
                                                    }else{
                                                        $s_x_max = $s_x_max*2;
                                                    }
                                                endif; ?>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-group__service-name" id="datasnames">1000 Adet Ücreti <small style="color:#636363;">(Zarar etmemeniz için olması gereken en düşük fiyat = <b><?=$s_x?></b>)</small></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="priceInput" name="price" value="<?= $serviceInfo["service_price"] ?>" <?= $serviceInfo["sync_price"] == 1 ? 'style="display: none;"' : "" ?>>
                                                    </div>
                                                    <div id="priceThreeInput" <?= $serviceInfo["sync_price"] == 0 ? 'style="display: none;"' : "" ?>>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" style="border-radius:5px;" id="priceInput" name="sync_rate_sabit" placeholder="Kar Sabit Fiyat" value="<?= !$serviceInfo["sync_kar_oran"] ? $serviceInfo["sync_rate"] : "Yüzdelik Aktif" ?>" onkeyup="FiyatDegis(this)" <?= $serviceInfo["sync_kar_oran"] ? "disabled" : "" ?>>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" style="border-radius:5px;" id="priceInput" name="sync_rate" placeholder="Kar Oranı" value="<?= $serviceInfo["sync_kar_oran"] ? $serviceInfo["sync_rate"] : "Sabit Kar Aktif" ?>" onkeyup="FiyatDegis(this)" <?= !$serviceInfo["sync_kar_oran"] ? "disabled" : "" ?>>
                                                            </div>
                                                            <div class="col-md-6">

                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text"><?= $settings["site_currency"] ?></div>
                                                                    </div>
                                                                    <input type="text" value="<?= $serviceInfo["service_price"] ?>" readonly="" id="fiyat_site_degis" data-price="<?= $serviceInfo["service_price"] ?>" class="form-control" placeholder="">

                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text ajaxdataerkan" data-fiyat="<?= $service_fiyat ?>" id="fiyat_api">

                                                                            <?php
                                                                            echo $service_fiyat;

                                                                            ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="price_api" id="ratePriceInput" value="<?= $s_x; ?>">

                                                        </div>

                                                    </div>
                                                    <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                        <div class="input-group-addon">
                                                            <label class=""><input id="priceCheckbox" type="checkbox" name="auto_price" <?= $serviceInfo["sync_price"] == 1 ? 'checked' : "" ?>>
                                                                <span class="slider round"></span>
                                                            </label>

                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <!-- **************NORMAL FIYAT SONU**************** -->
                                            <!-- **************MIN - MAX BASLANGIC**************** -->
                                            <?php if (in_array($serviceInfo["service_package"], $sabonelik)) { ?>

                                            <?php } else { ?>
                                                <div class="col-sm-12 form-group">
                                                    <div class="row">
                                                        <!-- **************MINIMUM BASLANGIC**************** -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="form-group__service-name" id="datasnames">Minimum</label>


                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text ajaxdataerkan" id="minText"><?= $s_x_min ?></div>
                                                                </div>
                                                                <input type="text" value="<?= $serviceInfo["service_min"] ?>" id="minPriceInput" name="min" class=" form-control" placeholder="" <?= $serviceInfo["sync_min"] == 1 ? "readonly" : "" ?>>
                                                                <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">

                                                                            <label class=""><input id="minPriceCheckbox" type="checkbox" name="auto_min" <?= $serviceInfo["sync_min"] == 1 ? "checked" : "" ?>>
                                                                                <span class="slider round"></span>
                                                                            </label>

                                                                        </div>
                                                                    </div>
                                                                <?php } ?>

                                                            </div>

                                                        </div>
                                                        <!-- **************MINIMUM SONU**************** -->
                                                        <!-- **************MAXIMUM BASLANGIC**************** -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="form-group__service-name" id="datasnames">Maksimum</label>


                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text ajaxdataerkan" id="maxText"><?= $s_x_max ?></div>
                                                                </div>
                                                                <input type="text" value="<?= $serviceInfo["service_max"] ?>" id="maxPriceInput" name="max" class=" form-control" placeholder="" <?= $serviceInfo["sync_max"] == 1 ? "readonly" : "" ?>>
                                                                <?php if (!in_array($serviceInfo["service_package"], $abonelik)) { ?>
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">

                                                                            <label class=""><input id="maxPriceCheckbox" type="checkbox" name="auto_max" <?= $serviceInfo["sync_max"] == 1 ? "checked" : "" ?>>
                                                                                <span class="slider round"></span>
                                                                            </label>

                                                                        </div>
                                                                    </div>
                                                                <?php } ?>

                                                            </div>

                                                        </div>
                                                        <!-- **************MAXIMUM SONU**************** -->
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- **************MIN - MAX SONU**************** -->
                                        <?php endif; ?>
                                        <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>

                                        <?php } else { ?>
                                            <hr class="mb-0">
                                            <!-- **************IPTAL BASLANGIC**************** -->
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>İptal butonu</label>
                                                    <select class="form-control" name="cancel_type">
                                                        <option value="2" <?= $serviceInfo["cancel_type"] == 2 ? "selected" : "" ?>>
                                                            Aktif
                                                        </option>
                                                        <option value="1" <?= $serviceInfo["cancel_type"] == 1 ? "selected" : "" ?>>
                                                            Pasif
                                                        </option>
                                                    </select>
                                                </div>
                                                <!-- **************IPTAL SONU**************** -->

                                                <!-- **************REFILL BASLANGIC**************** -->
                                                <div class="form-group col-sm-6">
                                                    <label>Refill Butonu</label>
                                                    <select id="refill" class="form-control" name="refill_type">
                                                        <option value="2" <?= $serviceInfo["refill_type"] == 2 ? "selected" : "" ?>>
                                                            Aktif
                                                        </option>
                                                        <option value="1" <?= $serviceInfo["refill_type"] == 1 ? "selected" : "" ?>>
                                                            Pasif
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div id="refill_day" class="form-group" style="display: none;">
                                                        <label>Refill Maksimum Gün <small>(Lifetime ise 0
                                                                yazınız)</small></label>
                                                        <input type="number" class="form-control" name="refill_time" value="<?= $serviceInfo["refill_time"] ?>">
                                                        <label>Refill Çıkma Süresi <small>(Default: 24 Saat)</small></label>
                                                        <input type="number" class="form-control" name="refill_min" value="<?= $serviceInfo["refill_min"] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- **************REFILL SONU**************** -->
                                        <?php } ?>
                                        <!-- **************SERVIS HIZI BASLANGIC**************** -->
                                        <hr class="mb-0">
                                        <div class="row">
                                            <div class="col-sm-4" style="display:none;">
                                                <label for="lastName" class="form-label">Servis Hızı</label>
                                                <select class="form-control" name="speed">
                                                    <option value="1" <?= $serviceInfo["service_speed"] == 1 ? "selected" : "" ?>>
                                                        Yavaş
                                                    </option>
                                                    <option value="2" <?= $serviceInfo["service_speed"] == 2 ? "selected" : "" ?>>
                                                        Bazen Yavaş
                                                    </option>
                                                    <option value="3" <?= $serviceInfo["service_speed"] == 3 ? "selected" : "" ?>>
                                                        Normal
                                                    </option>
                                                    <option value="4" <?= $serviceInfo["service_speed"] == 4 ? "selected" : "" ?>>
                                                        Hızlı
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- **************SERVIS HIZI SONU**************** -->
                                            <!-- **************AYNI LINK BASLANGICI**************** -->
                                            <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>

                                            <?php } else { ?>
                                                <div class="col-sm-4">
                                                    <label for="rep_link" class="form-label">Aynı Link</label>
                                                    <select class="form-control" name="rep_link">
                                                        <option value="1" <?= $serviceInfo["rep_link"] == 1 ? "selected" : "" ?>>
                                                            Sipariş Al
                                                        </option>
                                                        <option value="2" <?= $serviceInfo["rep_link"] == 2 ? "selected" : "" ?>>
                                                            Sipariş Alma
                                                        </option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <!-- **************AYNI LINK SONU**************** -->
                                            <!-- **************BAGLANTI TURU BASLANGIC**************** -->
                                            <?php if (in_array($serviceInfo["service_package"], $abonelik)) { ?>

                                            <?php } else { ?>
                                                <div class="col-sm-4">
                                                    <label for="lastName" class="form-label">Bağlantı Türü</label>
                                                    <select class="form-control" name="want_username">
                                                        <option value="1" <?= $serviceInfo["want_username"] == 1 ? "selected" : "" ?>>
                                                            Link
                                                        </option>
                                                        <option value="2" <?= $serviceInfo["want_username"] == 2 ? "selected" : "" ?>>
                                                            Kullanıcı adı
                                                        </option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <!-- **************BAGLANTI TURU SONU**************** -->
                                            <!-- **************OZEL SERVIS BASLANGIC**************** -->
                                            <div class="col-sm-4">
                                                <label for="lastName" class="form-label">Kişiye Özel Servis</label>
                                                <select class="form-control" name="secret">
                                                    <option value="2" <?= $serviceInfo["service_secret"] == 2 ? "selected" : "" ?>>
                                                        Hayır
                                                    </option>
                                                    <option value="1" <?= $serviceInfo["service_secret"] == 1 ? "selected" : "" ?>>
                                                        Evet
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- **************OZEL SERVIS BASLANGIC**************** -->
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
      <script>
        window.base_url = "<?= base_url(); ?>/";
        <?php if ($serviceInfo['api_service'] != 0) : ?>
            window.api_service_s = 1;
        <?php endif; ?>
    </script>
    <script>
        var type = $("#refill").val();

        if (type == 1) {

            $("#refill_day").hide();

        } else {

            $("#refill_day").show();

        }

        $("#refill").change(function() {

            var type = $(this).val();

            if (type == 1) {

                $("#refill_day").hide();

            } else {

                $("#refill_day").show();

            }

        });

        /* minprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */
        $(document).on("click","#minPriceCheckbox",function() {

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
        $("#maxPriceCheckbox").click(function() {
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
        $("#priceCheckbox").click(function() {
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

        $(".other_services").click(function() {
            var control = $("#translationsList");
            if (control.attr("class") == "hidden") {
                control.removeClass("hidden");
            } else {
                control.addClass("hidden");
            }
        });


        function getProviderServices(provider, base_url) {
            if (provider == 0) {
                $("#provider_service").hide();
            } else {
                $.post(base_url + "/admin/ajax_data", {
                    action: "providers_list",
                    provider: provider
                }).done(function(data) {
                    $("#provider_service").show();
                    $("#provider_service").html(data);
                }).fail(function() {
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
        serviceMode()
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

                var firstValue=$(this).attr("data-status");
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

        $(document).on('change','[name="service"]',()=>{
            if($('[name="sync_rate_sabit"]').prop("disabled") && !$('[name="sync_rate"]').prop("sync_rate")){
                //yüzdelik
                FiyatDegis($('[name="sync_rate"]'));
            }else if(!$('[name="sync_rate_sabit"]').prop("disabled") && $('[name="sync_rate"]').prop("sync_rate")){
                FiyatDegis($('[name="sync_rate_sabit"]'));
            }
        })
        $(document).on('change','[name="service2"]',()=>{
            if($('[name="sync_rate_sabit"]').prop("disabled") && !$('[name="sync_rate"]').prop("sync_rate")){
                //yüzdelik
                FiyatDegis($('[name="sync_rate"]'));
            }else if(!$('[name="sync_rate_sabit"]').prop("disabled") && $('[name="sync_rate"]').prop("sync_rate")){
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
    </script>
    <?= "</script>\r\n          <script>\r\n\r\n          var type = \$(\"#refill\").val();\r\n\r\n          if( type == 1 ){\r\n\r\n            \$(\"#refill_day\").hide();\r\n\r\n          } else{\r\n\r\n            \$(\"#refill_day\").show();\r\n\r\n          }\r\n\r\n          \$(\"#refill\").change(function(){\r\n\r\n            var type = \$(this).val();\r\n\r\n              if( type == 1 ){\r\n\r\n                \$(\"#refill_day\").hide();\r\n\r\n              } else{\r\n\r\n                \$(\"#refill_day\").show();\r\n\r\n              }\r\n\r\n          });\r\n\r\n          \$(\".other_services\").click(function(){\r\n            var control = \$(\"#translationsList\");\r\n            if( control.attr(\"class\") == \"hidden\" ){\r\n              control.removeClass(\"hidden\");\r\n            } else{\r\n              control.addClass(\"hidden\");\r\n            }\r\n          });\r\n          </script>" ?>
    <script>selectService()</script>

    
<script>
     tinymce.init({
            selector: 'textarea#tinymce<?= $serviceInfo["service_id"]?>',
            plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export code',
          menubar: 'file edit view insert format tools table tc help',
          toolbar: 'undo redo | code | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed link codesample | a11ycheck ltr rtl | showcomments addcomment',

      });
</script>
