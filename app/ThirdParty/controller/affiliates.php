<?php

$title .= $languageArray["affiliates.title"];

$refCount = countRow(["table" => "clients", "where" => ["referral" => $user['client_id']]]);

if( $settings["referral"] == 1  ){
    redirect()->to(base_url(''));
    echo "<script> window.location.href = '" . base_url() . "';</script>";
    exit();
}
