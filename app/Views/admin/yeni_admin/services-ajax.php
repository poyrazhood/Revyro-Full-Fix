<script>
    window.redservice = 0;
    window.redservicelist = [];
</script>
<div class="card-body p-0 mobile-overflow">
    <table class="table border table-striped mb-0" style="min-width:1221px;">
        <thead class="card-body card-glycon text-white">

        <tr>
            <th class="checkAll-th service-block__checker null w-10">
                <div class="checkAll-holder">
                    <input class="form-check-input ms-3 me-2 selectOrder"
                           style="float:left;position:relative;z-index:9;" type="checkbox"
                           id="checkAll">
                    <input type="hidden" id="checkAllText" value="order">
                    ID
                </div>
                <div class="action-block countblok" style="display: none;">
                    <button type="button" class="btn btn-primary dropdown-toggle ms-5 py-1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="countOrders"></span> servis seçili
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item bulkorder" data-type="active">Tümünü Aktif Et</a>
                        </li>
                        <li><a class="dropdown-item bulkorder" data-type="deactive">Tümünü
                                Pasifleştir</a></li>
                        <li><a class="dropdown-item bulkorder" data-type="del_price">Tüm İndirimleri
                                Sıfırla</a></li>
                        <li><a class="dropdown-item bulkorder" data-type="ayni_link_al">Aynı Link Sipariş Al</a></li>
                        <li><a class="dropdown-item bulkorder" data-type="ayni_link_alma">Aynı Link Sipariş Alma</a>
                        </li>
                        <li><a class="dropdown-item bulkorder" data-type="del_service">Tümünü Sil</a>
                        </li>
                    </ul>
                </div>
            </th>
            <th scope="col" class="w-30">Servis Adı</th>
            <th class="w-10" scope="col">Servis Türü</th>
            <th class="w-10 text-center" scope="col">Sağlayıcı</th>
            <th class="text-center w-10" scope="col">Fiyat <span id="fiyat-red"></span></th>
            <th class="text-center w-5" scope="col">Min</th>
            <th class="text-center w-5" scope="col">Max</th>
            <th class="text-center w-10" scope="col">Statü</th>
            <th class="text-center w-10"><span id="allServices" class="service-block__hide-all fa fa-compress"></span>
            </th>
        </tr>
        </thead>

    </table>


    <form action="<?php echo base_url("admin/services/multi-action") ?>" method="post"
          id="changebulkForm">
        <input type="hidden" name="bulkStatus" id="bulkStatus" value="-1">

        <div class="category-sortable" style="min-width:1221px;">
            <?php $c = 0;
            $e = 0;
            foreach ($serviceList as $category => $service):

                $e++;
                if (!countRow(['table' => 'services', 'where' => ['category_id' => $service[0]["category_id"]]])):
                    $service[0] = getRow(["table" => "categories", "where" => ["category_name" => $category]]);
                endif;
                ?>
                <div class="categories" data-id="<?= $service[0]["category_id"] ?>">
                    <div class="services-cat py-2">
                        <th colspan="11" class="text-start fw-3" id="serviceshowcategory-<?= $c ?>"
                            data-category="category-<?= $c ?>"
                            data-id="service-<?php echo $service[0]["service_id"] ?>"
                            data-service="<?php echo $service[0]["service_name"] ?>">
                            <div class="service-block__drag handle mx-2">
                                <i class="fas fa-grip-vertical d-none d-sm-block"></i>
                            </div>
                            <input class="form-check-input ms-3 me-2"
                                   style="float:left;position:relative;z-index:9;" type="checkbox"
                                   id="serviceAll" data-id="<?= $e ?>">
                            <?php if (isset($service[0]["category_secret"]) && $service[0]["category_secret"] == 1): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="gizli kategori"><i class="fa fa-lock"></i></small> '; endif;
                            echo $category; ?>

                            <?php
                            if ($service[0]["category_type"] == 1) {
                                echo '<span class="ms-2 px-2 bg-danger rounded">Pasif</span>';
                            }
                            ?>

                            <?php if (!empty($service[0]["service_id"])):

                                ?>

                                <div class="btn-group float-end">
                                    <div id="collapedAdd-<?= $e ?>"
                                         data-cid="<?= isset($service[0]['category_id']) ? $service[0]['category_id'] : "-1" ?>"
                                         class="service-block__collapse-button"
                                         data-category="<?= $e ?>"><span class="ms-2 px-2 bg-danger rounded"
                                                                         id="gizle-<?= $e ?>">Gizle</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="btn-group float-end">
                                <button type="button" class="btn btn-primary dropdown-toggle py-0"
                                        style="margin-right: 41px!important;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    İşlemler
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#modalDiv"
                                           data-id="<?= $service[0]['category_id'] ?>"
                                           data-action="edit_category">Kategoriyi Düzenle</a></li>
                                    <?php if ($service[0]["category_type"] == 1): $type = "category-active"; else: $type = "category-deactive"; endif; ?>
                                    <li><a class="dropdown-item"
                                           href="<?php echo base_url("admin/services/" . $type . "/" . $service[0]["category_id"]) ?>"><?php if ($service[0]["category_type"] == 1): echo "Kategoriyi Aktifleştir"; else: echo "Kategoriyi Pasifleştir"; endif; ?></a>
                                    </li>
                                    <?php if (!countRow(['table' => 'services', 'where' => ['category_id' => $service[0]["category_id"]]])): ?>
                                        <li><a class="dropdown-item"
                                               href="<?php echo base_url("admin/services/del_cate/" . $service[0]["category_id"]) ?>">Kategoriyi
                                                Sil</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item"
                                           data-bs-toggle="modal"
                                           data-bs-target="#modalDiv"
                                           data-id="<?= $service[0]['category_id'] ?>"
                                           data-action="edit_category_price">Fiyat Düzenle</a></li>

                                    <li><a class="dropdown-item"
                                           data-bs-toggle="modal"
                                           data-bs-target="#modalDiv"
                                           data-id="<?= $service[0]['category_id'] ?>"
                                           data-action="edit_category_aciklama">Açıklama
                                            Düzenle</a></li>
                                    <?php if (!$service[0]['price_line']): ?>
                                        <li><a class="dropdown-item"
                                               href="<?php echo base_url("admin/services/fiyat_sirala/" . $service[0]["category_id"]) ?>">Fiyata
                                                Göre Sıralat </a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item"
                                               href="<?php echo base_url("admin/services/fiyat_sirala_iptal/" . $service[0]["category_id"]) ?>">Fiyata
                                                Göre Sıralayı iptal et </a></li>
                                        <?php if (!$service[0]['fiyat_siralama']): ?>
                                            <li><a class="dropdown-item"
                                                   href="<?php echo base_url("admin/services/fiyat_sirala_az/" . $service[0]["category_id"]) ?>">Fiyata
                                                    Azdan Çoğa Göre Sırala </a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item"
                                                   href="<?php echo base_url("admin/services/fiyat_sirala_cok/" . $service[0]["category_id"]) ?>">Fiyata
                                                    Çoktan Aza Göre Sırala </a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <!--
                                                    <li><a class="dropdown-item"
                                                           href="<?php echo base_url("admin/services/" . $type . "/" . $service[0]["category_id"]) ?>"><?php if ($service[0]["category_type"] == 1): echo "Fiyat Belirle"; else: echo "Fiyat Belirle"; endif; ?></a>
                                                    </li>
													<li><a class="dropdown-item"
                                                           href="<?php echo base_url("admin/services/" . $type . "/" . $service[0]["category_id"]) ?>"><?php if ($service[0]["category_type"] == 1): echo "Açıklama Belirle"; else: echo "Açıklama Belirle"; endif; ?></a>
                                                    </li> -->
                                </ul>
                            </div>
                        </th>

                    </div>
                    <div id="servis-gizle-<?= $e ?>" data-dd="<?= $category ?>">
                        <div class="loading" style="display:none">
                            <img src="<?= base_url("assets/yeni_admin/loading.jpg") ?>" alt="">
                        </div>
                        <table id="servicesTableList"
                               data-id="<?= isset($service[0]['category_id']) ? $service[0]['category_id'] : "-1" ?>"
                               class="table border table-striped Servicecategory-<?= $e ?> mb-0">

                        </table>
                    </div>
                </div>


                <script>window.icerik_sayi.push("<?= $service[0]['category_id'] ?>|<?= $e ?>")</script>
            <?php endforeach; ?>

        </div>

    </form>
</div>
<script>
    window.katsayi = window.icerik_sayi.length;
    window.degissayi = 0;

    window.bol = window.icerik_sayi[window.degissayi].split("|");
    window.url = "<?= base_url('admin/gizle-ajax')?>?category=" + window.bol[0];
    icerik_cek();


    $(".category-sortable").sortable({
        handle: '.handle',
        update: function (event, ui) {
            var array = [];
            $(this).find('.categories').each(function (i) {
                $(this).attr('data-line', i + 1);
                var params = {};
                params['id'] = $(this).attr('data-id');
                params['line'] = $(this).attr('data-line');
                array.push(params);
            });
            $.post(base_url + '/admin/ajax_data', {action: 'category-sortable', categories: array});
        }
    });

    /*
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
    */
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    $(".methods-sortable").sortable({
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
            $.post(base_url + 'admin/ajax_data', {action: 'paymentmethod-sortable', methods: array});
        }
    });
    $(document).ready(function () {
        if (!getCookie("servis-gizle")) {
            setCookie("servis-gizle", JSON.stringify([]), 3);

        }
        var servisler = JSON.parse(getCookie("servis-gizle"));
        servisler.forEach(id => {
            $("#servis-gizle-" + id).hide();
            $('#gizle-' + id).html("Göster");
            $('#gizle-' + id).attr("class", "ms-2 px-2 bg-success rounded");
            $("#collapedAdd-" + id).addClass(" collapsed");
        });
        if (getCookie("servis-gizle-all") == 1) {
            $('#allServices').removeClass("fa fa-compress");
            $('#allServices').addClass("fa fa-expand");
        } else {
            window.e = 0;
            $('[id^="collapedAdd-"]').each(function () {
                window.e++;

            });
            if(Object.keys(servisler).length == window.e){

            $('#allServices').removeClass("fa fa-compress");
            $('#allServices').addClass("fa fa-expand");
            }

        }


    })
    $('[id^="collapedAdd-"]').on('click', function () {
        var id = $(this).attr("data-category");
        if ($(this).attr("class") == "service-block__collapse-button") {
            $("#servis-gizle-" + id).hide();

            $(this).addClass(" collapsed");
            $('#gizle-' + id).html("Göster");
            $('#gizle-' + id).attr("class", "ms-2 px-2 bg-success rounded");
            if (getCookie("servis-gizle")) {

                var cookie = JSON.parse(getCookie("servis-gizle"));

                if (cookie.indexOf(parseInt(id)) == -1) {
                    cookie.push(parseInt(id));
                    setCookie("servis-gizle", JSON.stringify(cookie), 3);

                }
            }
        } else {


            $("#servis-gizle-" + id).show();
            $('#gizle-' + id).html("Gizle");
            $('#gizle-' + id).attr("class", "ms-2 px-2 bg-danger rounded");
            $(this).removeClass(" collapsed");
            if (getCookie("servis-gizle")) {
                var cookie = JSON.parse(getCookie("servis-gizle"));
                cookie.find(element => {
                    if (element == parseInt(id)) {
                        var cookie = JSON.parse(getCookie("servis-gizle"));
                        cookie.splice(cookie.indexOf(parseInt(id)), 1);
                        console.log(cookie);
                        setCookie("servis-gizle", JSON.stringify(cookie), 3);

                    }
                });
            }
        }
    });
    if (window.redservice != 0) {
        $('#fiyat-red').html(window.redservice);
        $('#fiyat-red').css("color", "red");

    }
    $('#fiyat-red').on('click', function () {
        swal({
            title: 'Api fiyatı Servis fiyatından Yüksek Olan Servisler',
            text: window.redservicelist.toString()

        })
    });
    $('#allServices').click(function () {
        if ($(this).attr("class") == "service-block__hide-all fa fa-compress") {
            $('#allServices').removeClass("fa fa-compress");
            $('#allServices').addClass("fa fa-expand");
            $('[class^="Servicecategory-"]').each(function () {
                $(this).hide();
            });
            $('[id^="collapedAdd-"]').each(function () {
                var id = $(this).attr("data-category");
                $("#servis-gizle-" + id).hide();
                $(this).addClass(" collapsed");
                $('#gizle-' + id).html("Göster");
                $('#gizle-' + id).attr("class", "ms-2 px-2 bg-success rounded");
                if (getCookie("servis-gizle")) {

                    var cookie = JSON.parse(getCookie("servis-gizle"));

                    if (cookie.indexOf(parseInt(id)) == -1) {
                        cookie.push(parseInt(id));
                        setCookie("servis-gizle", JSON.stringify(cookie), 3);
                        setCookie("servis-gizle-all", 1, 3);
                    }
                }
            });
        } else {
            $('#allServices').removeClass("fa fa-expand");
            $('#allServices').addClass("fa fa-compress");
            $('[class^="Servicecategory-"]').each(function () {
                $(this).show();
            });
            $('[id^="collapedAdd-"]').each(function () {
                var id = $(this).attr("data-category");
                $("#servis-gizle-" + id).show();
                $('#gizle-' + id).html("Gizle");
                $('#gizle-' + id).attr("class", "ms-2 px-2 bg-danger rounded");
                $(this).removeClass(" collapsed");
                if (getCookie("servis-gizle")) {
                    var cookie = JSON.parse(getCookie("servis-gizle"));
                    cookie.find(element => {
                        if (element == parseInt(id)) {
                            var cookie = JSON.parse(getCookie("servis-gizle"));
                            cookie.splice(cookie.indexOf(parseInt(id)), 1);
                            setCookie("servis-gizle", JSON.stringify(cookie), 3);

                            setCookie("servis-gizle-all", 0, 3);
                        }
                    });
                }
            });
        }
    });
</script>