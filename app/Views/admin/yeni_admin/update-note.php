<?= view('admin/yeni_admin/static/header'); ?>
    <style>



        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }

        .btn-a-brc-tp:not(.disabled):not(:disabled).active, .btn-brc-tp, .btn-brc-tp:focus:not(:hover):not(:active):not(.active):not(.dropdown-toggle), .btn-h-brc-tp:hover, .btn.btn-f-brc-tp:focus, .btn.btn-h-brc-tp:hover {
            border-color: transparent;
        }

        .btn-outline-blue {
            color: #0d6ce1;
            border-color: #5a9beb;
            background-color: transparent;
        }

        .btn {
            cursor: pointer;
            position: relative;
            z-index: auto;
            border-radius: .175rem;
            transition: color .15s, background-color .15s, border-color .15s, box-shadow .15s, opacity .15s;
        }

        .border-2 {
            border-width: 2px !important;
            border-style: solid !important;
            border-color: transparent;
        }

        .bgc-white {
            background-color: #fff !important;
        }


        .text-green-d1 {
            color: #277b5d !important;
        }

        .letter-spacing {
            letter-spacing: .5px !important;
        }

        .font-bolder, .text-600 {
            font-weight: 600 !important;
        }

        .text-170 {
            font-size: 1.7em !important;
        }

        .text-purple-d1 {
            color: #6d62b5 !important;
        }

        .text-primary-d1 {
            color: #276ab4 !important;
        }

        .text-secondary-d1 {
            color: #5f718b !important;
        }

        .text-180 {
            font-size: 1.8em !important;
        }

        .text-150 {
            font-size: 1.5em !important;
        }

        .text-danger-m3 {
            color: #e05858 !important;
        }

        .rotate-45 {
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .position-l {
            left: 0;
        }

        .position-b, .position-bc, .position-bl, .position-br, .position-center, .position-l, .position-lc, .position-r, .position-rc, .position-t, .position-tc, .position-tl, .position-tr {
            position: absolute !important;
            display: block;
        }

        .mt-n475, .my-n475 {
            margin-top: -2.5rem !important;
        }

        .ml-35, .mx-35 {
            margin-left: 1.25rem !important;
        }

        .text-dark-l1 {
            color: #56585e !important;
        }

        .text-90 {
            font-size: .9em !important;
        }

        .text-left {
            text-align: left !important;
        }

        .mt-25, .my-25 {
            margin-top: .75rem !important;
        }

        .text-110 {
            font-size: 1.1em !important;
        }

        .deleted-text {
            text-decoration: line-through;;
        }

    </style>
    <div class="col-md-12">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>

        <div class="container">
            <div class="mt-5">
                <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm">
                    <!-- Basic Plan -->
                    <div class="row align-items-center">
                        <div class="col-12 col-md-12">
                            <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing">
                                X Olan Maddeler Gün İçerisinde Yapılıp Güncelleme Atılacaktır.
                            </h4>

                        </div>




                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-4">
                            <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing">
                                Versiyon 1.7.1
                            </h4>

                            <div class="text-secondary-d1 text-120">
                                <span class="text-180">Güncelleme Notu</span>
                            </div>
                        </div>

                        <ul class="list-unstyled mb-0 col-4 col-md-4 text-dark-l1 text-90 text-left my-4 my-md-0">
                            <li>
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span>
                <span class="text-110">Mail Şablonu Eklendi.</span>
              <small>Admin Header Menüsünden Görebilirsiniz</small>
              </span>
                            </li>

                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span class="text-110">
                Popup Eklendi
                  <small>Admin Header Menüsünden Görebilirsiniz. Temalara eklenmesi için glycon ile konuşulacak şimdiden popupunuzu ekleyebilirsiniz.</small>
            </span>
                            </li>

                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span>
                <span class="text-110">Database Yedekleme İçin Alan Oluşturuldu.</span>
              <small>İstediğiniz yedek için xx.com/mysql_backup?table=tablo_ismi adresine gidin</small>
              </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span>
                <span class="text-110">Database Değişiklikleri İçin Migrations Aktif Edildi.</span>
              <small>Güncellemeyle gelen database değişiklikleri otomatik olarak aktarılması ve geri alınabilmesi için</small>
              </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span>
                <span class="text-110">Giriş Yaparken Session Bekleme Süresi 2 Saniyeden 0.5 Saniyeye İndirildi.</span>
              </span>
                            </li>

                        </ul>
                        <ul class="list-unstyled mb-0 col-4 col-md-4 text-dark-l1 text-90 text-right my-4 my-md-0">


                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span class="text-110">
                Referans Üyeler İçin Ayarlara Alan Eklendi
            </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span class="text-110">
                Referans Sistemi Eklendi
                                    <small>Sipariş Eğer Complete Olursa Otomatik Referansı Olduğu Kişiye Referans Bakiyesi Gidecek</small>
            </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span class="text-110">
                Bazı Panellerde Güncelleme Atılamama Problemi Düzeltildi
            </span>
                            </li>

                            <li class="mt-25">
                                <i class="fa fa-check text-success-m2 text-110 mr-2 mt-1"></i>
                                <span class="text-110">
                Güncelleme Notlarına Panelden Erişim Eklendi
            </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-times text-success-m2 text-110 mr-2 mt-1" style="color:#e74c3c"></i>
                                <span class="text-110">
                Fast Sistemi Aktif Edildi
            </span>
                            </li>
                            <li class="mt-25">
                                <i class="fa fa-times text-success-m2 text-110 mr-2 mt-1" style="color:#e74c3c"></i>
                                <span class="text-110">
                Servislerin Toplu İşlemine Toplu Fiyat Düzenle - Açıklama Düzenle Eklendi
            </span>
                            </li>

                            <li class="mt-25">
                                <i class="fa fa-times text-success-m2 text-110 mr-2 mt-1" style="color:#e74c3c"></i>
                                <span class="text-110">
                G-Analiz Verilerinde Toplam Veriler Düzeltildi
            </span>
                            </li>
                        </ul>


                    </div>

                </div>

            </div>
        </div>
    </div>
<?= view('admin/yeni_admin/static/footer'); ?>