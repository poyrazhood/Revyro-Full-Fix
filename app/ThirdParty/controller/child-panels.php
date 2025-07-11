<?php

$title = $languageArray["childpanels.title"];


if ($settings["panel_selling"] == 1) {
    redirect()->to(base_url(''));
    echo "<script> window.location.href = '" . base_url() . "';</script>";
    exit();
}

$tickets = $conn->prepare("SELECT * FROM child_panels WHERE client_id=:c_id ORDER BY panel_created DESC ");
$tickets->execute(array("c_id" => $user["client_id"]));
$tickets = $tickets->fetchAll(PDO::FETCH_ASSOC);
$ticketList = [];
foreach ($tickets as $ticket) {
    foreach ($ticket as $key => $value) {
        if ($key == "panel_status") {
            $t[$key] = $languageArray["tickets.status." . $value];
        } else {
            $t[$key] = $value;
        }
    }
    array_push($ticketList, $t);
}


if ($_POST) {
    foreach ($_POST as $key => $value) {
        $_SESSION["data"][$key] = $value;
    }

    $domain = htmlentities($_POST["domain"]);
    $panel_currency = htmlentities($_POST["panel_currency"]);
    $price = htmlentities($settings["panel_price"]);

    if (empty($domain)) {
        $error = 1;
        $errorText = $languageArray["error.child.domain"];
    } elseif ($price > $user["balance"]) {
        $error = 1;
        $errorText = $languageArray["error.child.balance"];
    } elseif (($user["balance"] - $price < "-" . $user["debit_limit"]) && $user["balance_type"] == 1) {
        $error = 1;
        $errorText = $languageArray["error.child.balance"];
    } else {
        $conn->beginTransaction();
        $insert = $conn->prepare("INSERT INTO child_panels SET 
    client_id=:c_id,
    panel_domain=:domain,
    panel_currency=:panel_currency,
    panel_price=:price,
    panel_created=:created
   ");
        $insert = $insert->execute(array(
            "c_id" => $user["client_id"],
            "domain" => $domain,
            "panel_currency" => $panel_currency,
            "price" => $price,
            "created" => date("Y.m.d H:i:s")
        ));
        $last_id = $conn->lastInsertId();

        $update = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
        $update = $update->execute(array("balance" => $user["balance"] - $price, "spent" => $user["spent"] + $price, "id" => $user["client_id"]));

        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert2 = $insert2->execute(array("c_id" => $user["client_id"], "action" => $price . " TL tutarında yeni child panel sipariş geçildi #" . $last_id . ".", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));

        if ($insert): $id = $conn->lastInsertId(); endif;

        if ($insert):
            $conn->commit();
            unset($_SESSION["data"]);
            $success = 1;
            $successText = $languageArray["error.child.success"];
        else:
            $conn->rollBack();
            $error = 1;
            $errorText = $languageArray["error.child.fail"];
        endif;
    }

}

