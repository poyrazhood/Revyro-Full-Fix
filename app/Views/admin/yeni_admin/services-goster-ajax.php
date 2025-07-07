<tbody class="service-sortable">
<?php
$c = 0;
foreach ($service as $services):
    $c++;
    if (isset($services['service_id'])) {
        ?>
        <?php $api_detail = json_decode($services["api_detail"], true);
        $api_balance = json_decode($services['api_json'], true);
        $a_balance = isset($api_balance['balance']) ? $api_balance['balance'] : "Apiye bağlanılamadı";

        ?>
        <div class="serviceSortable" id="Servicecategory-<?= $c ?>"
             data-id="category-<?= $c ?>">
            <tr data-category="category-<?= $c ?>"
                data-id="service-<?php echo $services["service_id"] ?>"
                data-service="<?php echo $services["service_name"] ?>"
                id="serviceshowcategory-<?= $c ?>">
                <td class="text-start w-10 services-provider align-middle">
                    <div class="service-block__drag handle">
                        <i class="fas fa-grip-vertical d-none d-sm-block"></i>
                    </div>
                    <input
                            class="form-check-input me-2 selectOrder"
                            type="checkbox"
                            name="service[<?php echo $services["service_id"] ?>]"
                            value="1" id="service-api-id-<?= $services["service_id"] ?>">#<?= $services["service_id"] ?>
                    <?php
                    if ($services["service_api"] != 0) {
                        $apiservice = $services['api_service'];
                        echo "<small class='ms-0 ms-sm-5'>#$apiservice</small>";
                    }
                    ?>

                </td>
                <td class="w-30 align-middle"
                    id="service-name-id-<?= $services["service_id"] ?>"><?= $services["service_name"]; ?> <?= $services['refill_type'] == 2 ? " | <span style='color:red;'>Refill Açık</span>" : "" ?></td>
                <td class="w-10 align-middle"><?php echo servicePackageType($services["service_package"]);
                    if ($services["service_dripfeed"] == 2): echo ' <i class="fa fa-tint"></i>'; endif; ?></td>
                <td class="w-10 text-center services-provider align-middle"><?php if ($services["service_api"] != 0): echo $services["api_name"]; else: echo "Manuel"; endif; ?>
                    <?= $services['birlestirme'] ? "<small style='color:#c0392b'>Birleştirilmiş Servis</small>" : "" ?>
                    <?php if ($services["service_api"] != 0): echo '<small class="service-block__provider-value">' . $a_balance . '₺</small>'; endif; ?></td>
                <td class="w-10 text-center services-provider align-middle">
                    <?php

                    $api_price = isset($api_detail["rate"]) ? $api_detail["rate"] : "";

                    ?>
                    <div id="service-bakiye-id-<?= $services["service_id"] ?>" <?= ($services["service_api"] != 0 && $services["service_price"] < $api_price) ? 'style="color:red"' : "" ?>><?php echo $services["service_price"] ?>
                        <i class="fa fa-<?= strtolower($settings["site_currency"]) ?>"> </i>
                    </div>
                    <script>
                        <?php if($services["service_api"] != 0 && $services["service_price"] < $api_price): ?>
                        window.redservice = window.redservice + 1
                        window.redservicelist.push("<?= $services["service_id"]?>");
                        <?php endif; ?>
                    </script>
                    <?php if ($services["service_api"] != 0 && $api_detail["rate"]): echo '<small>' . priceFormat($api_detail["rate"]) . ' <i class="fa fa-' . strtolower($api_detail["currency"]) . '"></i> </small>'; endif; ?>

                </td>
                <td class="w-5 text-center services-provider align-middle">
                    <div id="service-min-id-<?= $services["service_id"] ?>">
                        <?php if ($services["sync_min"] == 1): echo ''; endif;
                        echo $services["service_min"] ?>
                        <?php
                        if ($services["sync_min"] == 0):
                            if ($services["service_api"] != 0): echo '<small>' . $api_detail["min"] . '</small>'; endif;
                        endif;
                        ?>
                    </div>
                </td>
                <td class="w-5 text-center services-provider align-middle">
                    <div id="service-max-id-<?= $services["service_id"] ?>">
                    <?php if ($services["sync_max"] == 1): echo ''; endif;
                    echo $services["service_max"] ?>
                    <?php
                    if ($services["sync_max"] == 0):
                        if ($services["service_api"] != 0): echo '<small>' . $api_detail["max"] . '</small>'; endif;
                    endif; ?>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <?php if ($services["service_type"] == 1) { ?>
                        <div class="badge bg-danger w-auto fs-6">
                            <i class="fas fa-times-circle"></i> Pasif
                        </div>
                        <?php if ($services["api_servicetype"] == 1): ?>
                            <div class="badge bg-danger w-auto fs-6">
                                <i class="fas fa-times-circle"></i> Api
                            </div>
                        <?php endif; ?>
                    <?php } else { ?>
                        <div class="badge bg-success w-auto fs-6">
                            <i class="fas fa-check-circle"></i> Aktif
                        </div>
                    <?php } ?>
                </td>

                <td class="text-center w-10 align-middle">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle py-1"
                                type="button"
                                id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            İşlemler
                        </button>
                        <ul class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton1">

                            <li><a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#modalDivDuzenle"
                                   data-id="<?= $services['service_id'] ?>"
                                   data-action="edit_service_baslik">Hızlı Düzenle</a>
                            </li>
                            <li><a class="dropdown-item"
                                   href="<?= base_url('admin/') ?>/services-detail/<?= $services["service_id"] ?>">Düzenle</a>
                            </li>

                            <li><a class="dropdown-item"
                                   href="<?= base_url('admin/') ?>/services/service-copy/<?= $services["service_id"] ?>">Servisi
                                    Kopyala</a>
                            </li>
                            <li><a class="dropdown-item"
                                   href="<?php echo base_url("admin/services/del_price/" . $services["service_id"]) ?>">İndirimleri
                                    Sıfırla</a>
                            </li>

                            <li><a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#modalDiv"
                                   data-id="<?= $services['service_id'] ?>"
                                   data-action="edit_service_baslik">Başlığı Düzenle</a>
                            </li>
                            <li><a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#modalDiv"
                                   data-id="<?= $services['service_id'] ?>"
                                   data-action="edit_service_aciklama">Açıklamayı Düzenle</a>
                            </li>
                            <?php if ($services["service_type"] == 1) { ?>
                                <li><a class="dropdown-item"
                                       href="<?= base_url('admin/') ?>/services/service-active/<?= $services["service_id"] ?>">Servisi
                                        Aktifleştir</a></li>
                            <?php } else { ?>
                                <li><a class="dropdown-item"
                                       href="<?= base_url('admin/') ?>/services/service-deactive/<?= $services["service_id"] ?>">Servisi
                                        Pasifleştir</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item"
                                   href="javascript::void(0)"
                                   data-bs-toggle="modal"
                                   data-bs-target="#confirmChange"
                                   data-href="<?php echo base_url("admin/services/delete/" . $services["service_id"]) ?>">Servisi
                                    Sil</a></li>
                            <li><a class="dropdown-item"
                                   href="<?= base_url('admin/') ?>/orders?services=<?= $services["service_id"] ?>">Siparişleri
                                    Gör</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </div>
    <?php }endforeach;
?>

</tbody>

<script>
    $(".service-sortable").sortable({
        handle: '.handle',
        update: function (event, ui) {
            var array = [];
            $(this).find('tr').each(function (i) {
                $(this).attr('data-line', i + 1);
                var params = {};
                params['id'] = $(this).attr('data-id');
                params['line'] = $(this).attr('data-line');
                array.push(params);
            });
            $.post(base_url + '/admin/ajax_data', {action: 'service-sortable', services: array});
        }
    });

</script>
