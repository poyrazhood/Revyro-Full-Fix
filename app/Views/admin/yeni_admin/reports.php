<?= view('admin/yeni_admin/static/header'); ?>

    <div class="container-fluid px-sm-5">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-left">GAnalytics</h3>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Net Kâr</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-money-bill-wave-alt fa-3x text-glycon"></i>
                        </h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=profits">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Harcanan Bakiye</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-money-bill fa-3x text-glycon"></i></h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=spent">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Yüklenen Para</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-credit-card fa-3x text-glycon"></i></h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=addbalance">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Verilen Sipariş</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-box fa-3x text-glycon"></i></h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=orders">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Destek Bileti</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-ticket-alt fa-3x text-glycon"></i></h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=tickets">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-glycon mb-3">
                    <div class="card-header text-center fw-bold">Yeni Üye</div>
                    <div class="card-body card-glycon">
                        <h5 class="card-title text-center"><i class="fas fa-user fa-3x text-glycon"></i></h5>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn-glycon btn-glycon-info text-center" href="?page=users">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="conteiner-fluid px-sm-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-body mobile-overflow">
                        <table class="table table-striped mobile-overflow">
                            <thead>
                            <tr>
                                <th scope="col">Gün</th>
                                <th scope="col" class="text-center">Ocak</th>
                                <th scope="col" class="text-center">Şubat</th>
                                <th scope="col" class="text-center">Mart</th>
                                <th scope="col" class="text-center">Nisan</th>
                                <th scope="col" class="text-center">Mayıs</th>
                                <th scope="col" class="text-center">Haziran</th>
                                <th scope="col" class="text-center">Temmuz</th>
                                <th scope="col" class="text-center">Ağustos</th>
                                <th scope="col" class="text-center">Eylül</th>
                                <th scope="col" class="text-center">Ekim</th>
                                <th scope="col" class="text-center">Kasım</th>
                                <th scope="col" class="text-center">Aralık</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($day = 1; $day <= 31; $day++): ?>
                                <tr>

                                    <th scope="row"><?= $day ?></th>
                                    <?php for ($month = 1; $month <= 12; $month++): ?>
                                        <?php if (isset($_GET['page'])) { ?>
                                            <?php if ($_GET['page'] == "addbalance") { ?>

                                                <td class="text-center"><?= dayPayments($day, $month, $year) ?></td>
                                            <?php } elseif ($_GET['page'] == "spent") { ?>
                                                <td class="text-center"><?= dayCharge($day, $month, $year) ?></td>
                                            <?php } elseif ($_GET['page'] == "orders") { ?>
                                                <td class="text-center"><?= dayOrders($day, $month, $year) ?></td>
                                            <?php } elseif ($_GET['page'] == "tickets") { ?>
                                                <td class="text-center"><?= dayTickets($day, $month, $year) ?></td>
                                            <?php } elseif ($_GET['page'] == "users") { ?>
                                                <td class="text-center"><?= dayUsers($day, $month, $year) ?></td>
                                            <?php } elseif ($_GET['page'] == "profits") { ?>
                                                <td class="text-center"><?= dayChargeNet($day, $month, $year) ?></td>

                                            <?php } else { ?>
                                               <td class="text-center"><?= dayChargeNet($day, $month, $year) ?></td>
                                            <?php }
                                        } else { ?>
                                            <td class="text-center"><?= dayChargeNet($day, $month, $year) ?></td>
                                        <?php }
                                    endfor; ?>

                                </tr>
                            <?php endfor; ?>
                            <tr>
                                <td><b>Toplam: </b></td>
                                <?php for ($month = 1; $month <= 12; $month++): ?>
                                    <td align="center">
                                        <?php if (isset($_GET['page'])) { ?>
                                            <?php if ($_GET['page'] == "profits") { ?>
                                                <b>  <?php echo isset($_POST["services"]) ? monthChargeNet($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthChargeNet($month, $year); ?> </b>
                                            <?php }elseif ($_GET['page'] == "spent") { ?>
                                                <b>  <?php echo isset($_POST["services"]) ? monthCharge($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthCharge($month, $year); ?> </b>
                                            <?php } elseif ($_GET['page'] == "addbalance") { ?>
                                                <b>  <?php echo isset($_POST["services"]) ? monthPayments($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthPayments($month, $year); ?> </b>
                                            <?php } elseif ($_GET['page'] == "orders") { ?>
                                                <b>  <?php echo isset($_POST["services"]) ? monthOrders($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthOrders($month, $year); ?> </b>
                                            <?php } elseif ($_GET['page'] == "tickets") { ?>
                                                <b>  <?php echo isset($_POST["tickets"]) ? monthTickets($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthTickets($month, $year); ?> </b>
                                            <?php } elseif ($_GET['page'] == "users") { ?>
                                                <b>  <?php echo isset($_POST["tickets"]) ? monthUsers($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthUsers($month, $year); ?> </b>

                                            <?php }
                                        } else { ?>
                                            <b>  <?php echo isset($_POST["services"]) ? monthCharge($month, $year, ["services" => $_POST["services"], "status" => $_POST["statuses"]]) : monthCharge($month, $year); ?> </b>
                     
                                        <?php } ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= view('admin/yeni_admin/static/footer'); ?>