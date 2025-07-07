<?php
include FCPATH . "Glycon.php";
$config =
    array('db' => array(
        'name'    =>  DBNAMES,
        'host'    =>  DBHOSTS,
        'user'    =>  DBUSERS,
        'pass'    =>  DBPASSS,
        'charset' =>  'utf8mb4'
    )
    );;
try {
    $conn = new PDO("mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"] . ";charset=" . $config["db"]["charset"] . ";", $config["db"]["user"], $config["db"]["pass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e) {
    die($e->getMessage());
}
?>