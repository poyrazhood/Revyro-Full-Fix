<?php
function countRow($data)
{
    $db = \Config\Database::connect();
    //global $conn;
    $where = "";

    $row = $db->table($data['table']);
    if (isset($data['where'])) {
        $row = $row->where($data['where'])->countAllResults();
    } else {
        $row = $row->countAllResults();
    }
    return $row;
    //$row = $conn->prepare("SELECT * FROM " . $data["table"] . " " . $where . " ");
    //$row->execute($execute);
    //$validate = $row->rowCount();
    //return $validate;
}
    function bakiye_format($amount) {
    $amount = (string)$amount; // cast the number in string
    $pos = stripos($amount, 'E-'); // get the E- position
    $there_is_e = $pos !== false; // E- is found

    if ($there_is_e) {
        $decimals = intval(substr($amount, $pos + 2, strlen($amount))); // extract the decimals
        $amount = number_format($amount, $decimals, '.', ','); // format the number without E-
    }

    return $amount;
}
function site_symbol($charge = "TRY"){
    if($charge == "TRY"):
        return '₺';
    elseif($charge == "USD"):
        return '$';
    elseif($charge == "EUR"):
        return '€';
    endif;
}
function birlesme_bolme($q)
{
    $kalan = $q % 2;
    $bolum = floor($q / 2);
    return array($bolum, $bolum + $kalan);

}
function dripfeedstatutoTR($status)
{
    switch ($status) {
        case "active":
            $statu = "Aktif";
            break;
        case "canceled":
            $statu = "İptal";
            break;
        case "completed":
            $statu = "Tamamlandı";
            break;
        default:
            return $statu;
    }
}
function subscriptionstatutoTR($status)
{
    switch ($status) {
        case "active":
            $statu = "Aktif";
            break;
        case "canceled":
            $statu = "İptal";
            break;
        case "completed":
            $statu = "Tamamlanmış";
            break;
        case "paused":
            $statu = "Durdurulmuş";
            break;
        case "expired":
            $statu = "Süresi dolmuş";
            break;
        case "limit":
            $statu = "Gönderimde";
            break;
        default:
            return $statu;
    }
}
function weePayMobile()
{
    $mobile = false;
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    if (preg_match("/(android|bb\\d+|meego).+mobile|avantgo|bada\\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i", $useragent) || preg_match("/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\\-(n|u)|c55\\/|capi|ccwa|cdm\\-|cell|chtm|cldc|cmd\\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\\-s|devi|dica|dmob|do(c|p)o|ds(12|\\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\\-|_)|g1 u|g560|gene|gf\\-5|g\\-mo|go(\\.w|od)|gr(ad|un)|haie|hcit|hd\\-(m|p|t)|hei\\-|hi(pt|ta)|hp( i|ip)|hs\\-c|ht(c(\\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\\-(20|go|ma)|i230|iac( |\\-|\\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\\/)|klon|kpt |kwc\\-|kyo(c|k)|le(no|xi)|lg( g|\\/(k|l|u)|50|54|\\-[a-w])|libw|lynx|m1\\-w|m3ga|m50\\/|ma(te|ui|xo)|mc(01|21|ca)|m\\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\\-2|po(ck|rt|se)|prox|psio|pt\\-g|qa\\-a|qc(07|12|21|32|60|\\-[2-7]|i\\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\\-|oo|p\\-)|sdk\\/|se(c(\\-|0|1)|47|mc|nd|ri)|sgh\\-|shar|sie(\\-|m)|sk\\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\\-|v\\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\\-|tdg\\-|tel(i|m)|tim\\-|t\\-mo|to(pl|sh)|ts(70|m\\-|m3|m5)|tx\\-9|up(\\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\\-|your|zeto|zte\\-/i", substr($useragent, 0, 4))) {
        $mobile = true;
    }
    return $mobile;
}

function copy_dir($src, $dst, $url = null)
{
    if (!file_exists($url)) {
        $sonuc = mkdir($url, '0777');
        chmod($sonuc, 0777);
    }
    if (is_dir($src)) {
        if (!is_dir($dst)) mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." and $file != ".." && $file != "uploads" && $file != "images" && $file != "cache" && $file != "img") {
                copy_dir("$src/$file", "$dst/$file", $url);
            }
        }
    } else if (file_exists($src)) {
        copy($src, $dst);
    }
}

function serviceTypeGetList($type)
{
    switch ($type) {
        case "Default":
            $service_type = 1;
            break;
        case "Package":
            $service_type = 2;
            break;
        case "Custom Comments":
            $service_type = 3;
            break;
        case "Custom Comments Package":
            $service_type = 4;
            break;
        case "Mentions":
            $service_type = 5;
            break;
        case "Mentions with hashtags":
            $service_type = 6;
            break;
        case "Mentions custom list":
            $service_type = 7;
            break;
        case "Mentions custom list":
            $service_type = "8";
            break;
        case "Mentions user followers":
            $service_type = 9;
            break;
        case "Mentions media likers":
            $service_type = 10;
            break;
        case "Subscriptions":
            $service_type = 11;
            break;
        default:
            $service_type = 1;
            return $service_type;
    }
    return $service_type;
}

function folder_bul($folder, $ver, $folder_s = null, $folder_real = null)
{
    //echo $folder;

    if (is_null($folder_s)) {
        $uzanti = FCPATH . "version/" . $ver . "/";
        $uzanti_real = FCPATH;
        $klasor_g = opendir($uzanti);
    } else {
        $uzanti = $folder_s;
        $uzanti_real = $folder_real;
        $klasor_g = opendir($uzanti);

    }
    //echo $klasor_g;
    while ($dosya_g = readdir($klasor_g)) {

        if (is_dir($uzanti . $dosya_g) && $dosya_g != ".." && $dosya_g != "." && $dosya_g != "backup") {
            echo $dosya_g . "<br>";
            folder_bul($dosya_g, $ver, $uzanti . $dosya_g . "/", $uzanti_real . $dosya_g . "/");
            //        echo $dosya_g;
            copy_dir($uzanti . $dosya_g . "/", $uzanti_real . $dosya_g . "/", $uzanti_real);
        } else {

        }
    }
    closedir($klasor_g);
    return 1;
}


function username_check($username)
{
    if (preg_match("/^[a-z\\d_]{4,32}\$/i", $username)) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}

function EmailCreateApiKey($data){
    $data = md5(rand(9999, 2324332));
    return $data;
}
function CreateApiKey($data)
{
    global $conn;
    $yedek = $data;
    $data = md5($data["email"] . $data["username"] . rand(9999, 2324332));
    $row = $conn->prepare("SELECT * FROM clients WHERE apikey=:key ");
    $row->execute(["key" => $data]);
    if ($row->rowCount()) {
        CreateApiKey($yedek);
    } else {
        return $data;
    }
}

function email_check($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}

function array_group_by($arr, $key)
{
    if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
        trigger_error("array_group_by(): The key should be a string, an integer, a float, or a function", 256);
    }
    $isFunction = !is_string($key) && is_callable($key);
    $grouped = [];
    foreach ($arr as $value) {
        $groupKey = NULL;
        if ($isFunction) {
            $groupKey = $key($value);
        } else {
            if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                $groupKey = $value[$key];
            }
        }
        $grouped[$groupKey][] = $value;
    }
    if (2 < func_num_args()) {
        $args = func_get_args();
        foreach ($grouped as $groupKey => $value) {
            $params = array_merge([$value], array_slice($args, 2, func_num_args()));
            $grouped[$groupKey] = call_user_func_array("array_group_by", $params);
        }
    }
    return $grouped;
}

function ticketStatu($status)
{
    switch ($status) {
        case "closed":
            $statu = "Kapalı";
            break;
        case "answered":
            $statu = "Yanıtlanmış";
            break;
        case "pending":
            $statu = "Cevap bekliyor";
            break;
        default:
            return $status;
    }
}

function dayPayments($day, $ay, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["methods"])) {
        if (count($extra["methods"])) {
            $where = "&& ( ";
            foreach ($extra["methods"] as $method) {
                $where .= "payment_method='" . $method . "' || ";
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    $first = $year . "-" . $ay . "-" . $day . " 00:00:00";
    $last = $year . "-" . $ay . "-" . $day . " 23:59:59";
    $row = $conn->query("SELECT SUM(payment_amount) FROM payments WHERE payment_delivery='2' && payment_status='3' && payment_create_date<='" . $last . "' && payment_create_date>='" . $first . "' " . $where . "  ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row["SUM(payment_amount)"];
    return number_format($charge, 2, ".", ",");
}

function monthPayments($ay, $year, $extra = NULL)
{
    global $conn;
    if (!empty($extra["methods"])) {
        if (count($extra["methods"])) {
            $where = "&& ( ";
            foreach ($extra["methods"] as $method) {
                $where .= "payment_method='" . $method . "' || ";
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    $first = $year . "-" . $ay . "-1 00:00:00";
    $last = $year . "-" . $ay . "-31 23:59:59";
    $row = $conn->query("SELECT SUM(payment_amount) FROM payments WHERE payment_delivery='2' && payment_status='3' && payment_create_date<='" . $last . "' && payment_create_date>='" . $first . "' " . $where . " ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row["SUM(payment_amount)"];
    return number_format($charge, 2, ".", ",");
}

function dayCharge($day, $ay, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $ay . "-" . $day . " 00:00:00";
    $last = $year . "-" . $ay . "-" . $day . " 23:59:59";
    $row = $conn->query("SELECT SUM(order_charge) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' && dripfeed='1' && subscriptions_type='1'   " . $where . "   ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row["SUM(order_charge)"];
    return number_format($charge, 2, ".", ",");
}

function dayChargeNet($day, $ay, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $ay . "-" . $day . " 00:00:00";
    $last = $year . "-" . $ay . "-" . $day . " 23:59:59";
    $row = $conn->query("SELECT SUM(order_profit) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' && dripfeed='1' && subscriptions_type='1' && order_api!='0' " . $where . "  ")->fetch(PDO::FETCH_ASSOC);
    $row2 = $conn->query("SELECT SUM(order_charge) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' && dripfeed='1' && subscriptions_type='1'  " . $where . "  ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row2["SUM(order_charge)"] - $row["SUM(order_profit)"];

    return number_format($charge, 2, ".", ",");
}

function monthCharge($month, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-1 00:00:00";
    $last = $year . "-" . $month . "-31 23:59:59";
    $row = $conn->query("SELECT SUM(order_charge) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "'  && dripfeed='1' && subscriptions_type='1' " . $where . "   ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row["SUM(order_charge)"];
    return number_format($charge, 2, ".", ",");
}

function monthChargeNet($month, $year, $extra = NULL)
{
    global $conn;
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-1 00:00:00";
    $last = $year . "-" . $month . "-31 23:59:59";
    $row = $conn->query("SELECT SUM(order_profit) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' && dripfeed='1' && subscriptions_type='1' && order_api!='0' " . $where . "  ")->fetch(PDO::FETCH_ASSOC);
    $row2 = $conn->query("SELECT SUM(order_charge) FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' && dripfeed='1' && subscriptions_type='1'  " . $where . "  ")->fetch(PDO::FETCH_ASSOC);
    $charge = $row2["SUM(order_charge)"] - $row["SUM(order_profit)"];
    return number_format($charge, 2, ".", ",");
}

function orderStatu($statu)
{

    switch ($statu) {
        case 'active':
            $statu = "Aktif";
            break;
        case 'completed':
            $statu = "Tamamlandı";
            break;
        case 'canceled':
            $statu = "İptal";
            break;
    }

    return $statu;
}

function dayOrders($day, $month, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    if (!empty($extra["status"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-" . $day . " 00:00:00";
    $last = $year . "-" . $month . "-" . $day . " 23:59:59";
    return $row = $conn->query("SELECT order_id FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' " . $where . " ")->rowCount();
}

function dayTickets($day, $month, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    if (!empty($extra["status"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-" . $day . " 00:00:00";
    $last = $year . "-" . $month . "-" . $day . " 23:59:59";
    return $row = $conn->query("SELECT ticket_id FROM tickets WHERE time<='" . $last . "' && time>='" . $first . "' " . $where . " ")->rowCount();
}
function monthTickets($month, $year, $extra = NULL)
{
    global $conn;
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-1 00:00:00";
    $last = $year . "-" . $month . "-31 23:59:59";
    return $row = $conn->query("SELECT ticket_id FROM tickets WHERE time<='" . $last . "' && time>='" . $first . "' " . $where . " ")->rowCount();
}
function dayUsers($day, $month, $year, $extra = NULL)
{
    global $conn;
    $where = "";
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ") ";
        } else {
            $where = "";
        }
    }
    if (!empty($extra["status"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-" . $day . " 00:00:00";
    $last = $year . "-" . $month . "-" . $day . " 23:59:59";
    return $row = $conn->query("SELECT client_id FROM clients WHERE register_date<='" . $last . "' && register_date>='" . $first . "' " . $where . " ")->rowCount();
}
function monthUsers($month, $year, $extra = NULL)
{
    global $conn;
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-1 00:00:00";
    $last = $year . "-" . $month . "-31 23:59:59";
    return $row = $conn->query("SELECT client_id FROM clients WHERE register_date<='" . $last . "' && register_date>='" . $first . "' " . $where . " ")->rowCount();
}

function monthOrders($month, $year, $extra = NULL)
{
    global $conn;
    if (!empty($extra["status"])) {
        if (count($extra["status"])) {
            $where = "&& ( ";
            if (in_array("cron", $extra["status"])) {
                $where .= "order_detail='cronpending' || ";
            }
            if (in_array("fail", $extra["status"])) {
                $where .= "order_error!='-' || ";
            }
            foreach ($extra["status"] as $statu) {
                if ($statu != "cron" || $statu != "fail") {
                    $where .= "order_status='" . $statu . "' || ";
                }
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        } else {
            $where = "";
        }
    }
    if (!empty($_POST["services"]) && count($_POST["services"])) {
        $where .= "&& ( ";
        foreach ($extra["services"] as $service) {
            $where .= " service_id='" . $service . "' || ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }
    $first = $year . "-" . $month . "-1 00:00:00";
    $last = $year . "-" . $month . "-31 23:59:59";
    return $row = $conn->query("SELECT order_id FROM orders WHERE order_create<='" . $last . "' && order_create>='" . $first . "' " . $where . " ")->rowCount();
}
function ortalama($array)
{
    $toplam = 0;
    $sayi = count($array);
    foreach ($array as $ort) {
        if (is_numeric($ort)) {
            $toplam += $ort;
        } else {
            $sayi--;
        }
    }
    if ($sayi) {
        $islem = $toplam / $sayi;
        return $islem;
    }
    return "NaN";
}
function convertSecToStr($secs)
{
    $output = "";
    if (86400 <= $secs) {
        $days = floor($secs / 86400);
        $secs = $secs % 86400;
        $output = $days . " Gün";
        if ($days != 1) {
            $output .= "";
        }
        if (0 < $secs) {
            $output .= ", ";
        }
    }
    if (3600 <= $secs) {
        $hours = floor($secs / 3600);
        $secs = $secs % 3600;
        $output .= $hours . " Saat";
        if ($hours != 1) {
            $output .= "";
        }
        if (0 < $secs) {
            $output .= ", ";
        }
    }
    if (60 <= $secs) {
        $minutes = floor($secs / 60);
        $secs = $secs % 60;
        $output .= $minutes . " Dakika";
        if ($minutes != 1) {
            $output .= "";
        }
        if (0 < $secs) {
            $output .= " ";
        }
    }
    return $output;
}
function title2($lang = "tr", $key, $key2 = "")
{
    $convertLang = ["tr" => ["index" => "Anasayfa", "clients" => "Kullanıcılar", "orders" => "Siparişler", "dripfeeds" => "Drip-feeds", "tasks" => "Tasks", "subscriptions" => "Abonelikler", "services" => "Servisler", "payments" => ["online" => "Ödemeler", "bank" => "Banka Ödemeleri"], "tickets" => "Destek", "reports" => "İstatistikler", "appearance" => ["pages" => "Sayfalar", "blog" => "Blog", "menu" => "Menü", "themes" => "Tema Ayarları", "language" => "Dil Ayarları"], "settings" => ["general" => "Genel", "providers" => "Sağlayıcılar", "payment-methods" => "Ödeme Yöntemleri", "bank-accounts" => "Banka Hesapları", "modules" => "Modüller", "integrations" => "Entegrasyonlar", "subject" => "Destek Modülü", "alert" => "Bildirimler", "payment-bonuses" => "Bonuslar"], "child-panels" => "Child Panels", "logs" => "Loglar", "provider_logs" => "Sağlayıcı Logları", "guard_logs" => "Koruma Logları", "account" => "Hesabım"], "en" => ["index" => "Home"]];
    if ($key2 != "") {
        return $convertLang[$lang][$key][$key2];
    }
    return $convertLang[$lang][$key];
}

function rateSync($sayi, $yuzde)
{
    return $sayi * $yuzde / 100;
}

function permalink($str, $options = array())
{
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function route($id)
{
    $ana_url = base_url();
    $ana_url = explode('/', $ana_url);
    $path = $_SERVER['REQUEST_URI'];
    if ($ana_url[2]) {
        $path = str_replace($ana_url[2] . "/", '', $path);
    }
    $path = explode('/', $path);
    if (array_key_exists($id, $path)) {
        $path[$id] = explode('?', $path[$id])[0];
        return $path[$id];
    } else {
        return 0;
    }

}

function userlogin_check($username, $pass)
{
    global $conn;
    $row = $conn->prepare("SELECT * FROM clients WHERE username=:username && password=:password ");
    $row->execute(["username" => $username, "password" => md5(sha1(md5($pass)))]);
    if ($row->rowCount()) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}

function guardDeleteAllRoles()
{
    global $conn;
    $update = $conn->prepare("UPDATE clients SET access=:access WHERE client_id=:c_id ");
    $update->execute(["c_id" => $user["client_id"], "access" => "{\"admin_access\":\"0\"}"]);
    header("Location:" . base_url(""));
}

function guardLogout()
{
    global $_SESSION;
    unset($_SESSION["neira_userid"]);
    unset($_SESSION["neira_userpass"]);
    unset($_SESSION["neira_userlogin"]);
    setcookie("u_id", $user["client_id"], time() - 604800, "/", NULL, NULL, true);
    setcookie("u_password", $user["password"], time() - 604800, "/", NULL, NULL, true);
    setcookie("u_login", "ok", time() - 604800, "/", NULL, NULL, true);
    setcookie("a_login", "ok", time() - 604800, "/", NULL, NULL, true);
    session_destroy();
    header("Location:" . base_url(""));
}

function sendMail($arr)
{
    global $conn, $settings, $mail;
    $email = \Config\Services::email();
    $settings = new \App\Models\settings();
    $settings = $settings->where('id', '1')->get()->getResultArray()[0];
    if ($settings["smtp_protocol"] != 0) {
        $email->SMTPCrypto = $settings["smtp_protocol"];
    }
    $email->SMTPUser = $settings["smtp_user"];
    $email->SMTPPass = $settings["smtp_pass"];
    $email->SMTPHost = $settings["smtp_server"];
    $email->SMTPPort = $settings["smtp_port"];
    $email->protocol = $settings["smtp_type"];;
    $email->newline = "\r\n";
    $email->charset = 'utf-8';
    $email->mailType = 'html';
    $sablon = $settings['mail_sablon'];
    $sablon = str_replace('{mail_icerik_cek}',$arr["body"],$sablon);
    if (is_array($arr["mail"])):
        foreach ($arr["mail"] as $goMail) {
            $email->clear();
            $email->setTo($goMail);
            $email->setFrom($settings['smtp_user'], $settings["site_title"]);


            $email->setSubject($arr["subject"]);
            $email->setMessage($sablon);
            $email->send();

        }
    else:
        $email->clear();

        $email->setFrom($settings['smtp_user'], $settings["site_title"]);
        $email->setTo($arr["mail"]);

        $email->setSubject($arr["subject"]);
        $email->setMessage($sablon);
        $email->send();

    endif;
    /*


    try {

        //$mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $settings["smtp_server"];
        $mail->SMTPAuth = true;
        $mail->Username = $settings["smtp_user"];
        $mail->Password = $settings["smtp_pass"];
        if ($settings["smtp_protocol"] != 0):
            $mail->SMTPSecure = $settings["smtp_protocol"];
        endif;
        $mail->Port = $settings["smtp_port"];

        $mail->SetLanguage("tr", "phpmailer/language");
        $mail->CharSet = "utf-8";
        $mail->Encoding = "base64";

        $mail->setFrom($settings["smtp_user"], $settings["site_title"]);
        if (is_array($arr["mail"])):
            foreach ($arr["mail"] as $goMail) {
                $mail->ClearAddresses();
                $mail->addAddress($goMail);
                $mail->isHTML(true);
                $mail->Subject = $arr["subject"];
                $mail->Body = $arr["body"];
                $mail->send();
            }
        else:
            $mail->addAddress($arr["mail"]);
            $mail->isHTML(true);
            $mail->Subject = $arr["subject"];
            $mail->Body = $arr["body"];
            $mail->send();
        endif;

        return 1;
    } catch (Exception $e) {
        //echo $e;
        //exit();
        return 0;
    }
    */

    return 1;
}

function client_price($service, $userid = "")
{
    global $conn;
    global $user;
    $session = \Config\Services::session();
    if($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1){

    $userid = 42;
    $row = $conn->prepare("SELECT * FROM clients_price WHERE service_id=:s_id && client_id=:c_id ");
    $row->execute(["s_id" => $service, "c_id" => $userid]);
    $db = \Config\Database::connect();
    $row = $db->table("clients_price")->where(array(
        'service_id'=>$service,
        'client_id' => $userid
        ));
    if ($row->countAllResults()) {
        $x = $db->table("clients_price")->where(array(
        'service_id'=>$service,
        'client_id' => $userid
        ))->get()->getResultArray()[0];
        $price = $x["service_price"];
    } else {
        $row = $conn->prepare("SELECT * FROM services WHERE service_id=:id");
        $row->execute(["id" => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
            $row = $db->table("services")->where(array(
        'service_id'=>$service,
        ))->get()->getResultArray()[0];
        $price = $row["service_price"];
    }
    }else{
        $db = \Config\Database::connect();
        $row = $conn->prepare("SELECT * FROM services WHERE service_id=:id");
        $row->execute(["id" => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
            $row = $db->table("services")->where(array(
        'service_id'=>$service,
        ))->get()->getResultArray()[0];
        $price = $row["service_price"];
    }
    return $price;
}

function open_bankpayment($user)
{
    global $conn;
    $row = $conn->prepare("SELECT * FROM payments WHERE client_id=:client && payment_status=:status && payment_method=:method ");
    $row->execute(["client" => $user, "status" => 1, "method" => 6]);
    $validate = $row->rowCount();
    return $validate;
}

function generate_shopier_form($data)
{
    $api_key = $data->apikey;
    $secret = $data->apisecret;
    $user_registered = date("Y.m.d");
    $time_elapsed = time() - strtotime($user_registered);
    $buyer_account_age = (int)($time_elapsed / 86400);
    $currency = 0;
    $dataArray = $data;
    $productinfo = $data->item_name;
    $producttype = 1;
    $productinfo = str_replace("\"", "", $productinfo);
    $productinfo = str_replace("\"", "", $productinfo);
    $current_language = 0;
    $current_lan = 0;
    $modul_version = "1.0.4";
    $random_number = rand(1000000, 9999999);
    $args = ["API_key" => $api_key, "website_index" => $data->website_index, "platform_order_id" => $data->order_id, "product_name" => $productinfo, "product_type" => $producttype, "buyer_name" => $data->buyer_name, "buyer_surname" => $data->buyer_surname, "buyer_email" => $data->buyer_email, "buyer_account_age" => $buyer_account_age, "buyer_id_nr" => 0, "buyer_phone" => $data->buyer_phone, "billing_address" => $data->billing_address, "billing_city" => $data->city, "billing_country" => "TR", "billing_postcode" => "", "shipping_address" => $data->billing_address, "shipping_city" => $data->city, "shipping_country" => "TR", "shipping_postcode" => "", "total_order_value" => $data->ucret, "currency" => $currency, "platform" => 0, "is_in_frame" => 1, "current_language" => $current_lan, "modul_version" => $modul_version, "random_nr" => $random_number];
    $data = $args["random_nr"] . $args["platform_order_id"] . $args["total_order_value"] . $args["currency"];
    $signature = hash_hmac("SHA256", $data, $secret, true);
    $signature = base64_encode($signature);
    $args["signature"] = $signature;
    $args_array = [];
    foreach ($args as $key => $value) {
        $args_array[] = "<input type='hidden' name='" . $key . "' value='" . $value . "'/>";
    }
    if (!empty($dataArray->apikey) && !empty($dataArray->apisecret) && !empty($dataArray->website_index)) {
        $_SESSION["data"]["payment_shopier"] = true;
        return "<html> <!doctype html><head> <meta charset=\"UTF-8\"> <meta content=\"True\" name=\"HandheldFriendly\"> <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n      <meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />\n      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=0\" /> <title lang=\"tr\">Güvenli Ödeme Sayfası</title><body><head>\n      <form action=\"https://www.shopier.com/ShowProduct/api_pay4.php\" method=\"post\" id=\"shopier_payment_form\" style=\"display: none\">" . implode("", $args_array) . "<script>setInterval(function(){document.getElementById(\"shopier_payment_form\").submit();},2000)</script></form></body></html>";
    }
}

function open_ticket($user)
{
    global $conn;
    $row = $conn->prepare("SELECT * FROM tickets WHERE client_id=:client && status=:status ");
    $row->execute(["client" => $user, "status" => "pending"]);
    $validate = $row->rowCount();
    return $validate;
}

function robot($value)
{
    $ch = curl_init($value);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    $result = curl_exec($ch);
    if (curl_errno($ch) != 0 && empty($result)) {
        $result = false;
    }
    curl_close($ch);
    return $result;
}

function private_str($str, $start, $end)
{
    $after = mb_substr($str, 0, $start, 'utf8');
    $repeat = str_repeat('*', $end);
    $before = mb_substr($str, ($start + $end), strlen($str), 'utf8');
    return $after . $repeat . $before;
}

function GetIP()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return ($ip);
}

function themeExtras($which)
{
    global $conn;
    $settings = $conn->prepare("SELECT * FROM settings WHERE id=:bir ");
    $settings->execute(array('bir' => 1));
    $settings = $settings->fetch(PDO::FETCH_ASSOC);
    $theme = $conn->prepare("SELECT * FROM themes WHERE theme_dirname=:dir ");
    $theme->execute(array('dir' => $settings['site_theme']));
    $theme = $theme->fetch(PDO::FETCH_ASSOC);
    return json_decode($theme["theme_extras"], true);

}

function themeExtras_switch($which)
{
    global $conn;
    $theme = $conn->prepare("SELECT * FROM themes WHERE theme_dirname=:dir ");
    $theme->execute(array('dir' => $which));
    $theme = $theme->fetch(PDO::FETCH_ASSOC);
    return json_decode($theme["theme_extras"], true);

}

function userdata_check($where, $data)
{
    global $conn;
    $row = $conn->prepare("SELECT * FROM clients WHERE " . $where . "=:data ");
    $row->execute(["data" => $data]);
    if ($row->rowCount()) {
        $validate = true;
    } else {
        $validate = false;
    }
    return $validate;
}

function new_ticket($user)
{
    global $conn;
    $row = $conn->prepare("SELECT * FROM tickets WHERE client_id=:client && support_new=:new ");
    $row->execute(["client" => $user, "new" => 2]);
    $validate = $row->rowCount();
    return $validate;
}

function service_price($service)
{
    global $conn;
    global $user;
    $session = \Config\Services::session();
    if($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1){

    $userid = $session->get('neira_userid');
    $row = $conn->prepare("SELECT * FROM clients_price WHERE service_id=:s_id && client_id=:c_id ");
    $row->execute(["s_id" => $service, "c_id" => $userid]);
    $db = \Config\Database::connect();
    $row = $db->table("clients_price")->where(array(
        'service_id'=>$service,
        'client_id' => $userid
        ));
    if ($row->countAllResults()) {
        $x = $db->table("clients_price")->where(array(
        'service_id'=>$service,
        'client_id' => $userid
        ))->get()->getResultArray()[0];
        $price = $x["service_price"];
    } else {
        $row = $conn->prepare("SELECT * FROM services WHERE service_id=:id");
        $row->execute(["id" => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
            $row = $db->table("services")->where(array(
        'service_id'=>$service,
        ))->get()->getResultArray()[0];
        $price = $row["service_price"];
    }
    }else{
        $db = \Config\Database::connect();
        $row = $conn->prepare("SELECT * FROM services WHERE service_id=:id");
        $row->execute(["id" => $service]);
        $row = $row->fetch(PDO::FETCH_ASSOC);
            $row = $db->table("services")->where(array(
        'service_id'=>$service,
        ))->get()->getResultArray()[0];
        $price = $row["service_price"];
    }
    return bakiye_format($price);
}

function getRows($data)
{
    global $conn;
    $where = "";
    $order = "";
    $order = "";
    $limit = "";
    $execute[] = "";
    if ($data["where"]) {
        $where = "WHERE ";
        foreach ($data["where"] as $key => $value) {
            $where .= " " . $key . "=:" . $key . " && ";
            $execute[$key] = $value;
        }
        $where = substr($where, 0, -3);
    }
    if ($data["order"]) {
        $order = "ORDER BY " . $data["order"] . " " . $data["order_type"];
    }
    if ($data["limit"]) {
        $limit = "LIMIT " . $data["limit"];
    }
    $row = $conn->prepare("SELECT * FROM " . $data["table"] . " " . $where . " " . $order . " " . $limit . " ");
    $row->execute($execute);
    if ($row->rowCount()) {
        $rows = $row->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $rows = [];
    }
    return $rows;
}

function getRow($data)
{
    global $conn;
    $where = "WHERE ";
    foreach ($data["where"] as $key => $value) {
        $where .= " " . $key . "=:" . $key . " && ";
        $execute[$key] = $value;
    }
    $where = substr($where, 0, -3);
    $row = $conn->prepare("SELECT * FROM " . $data["table"] . " " . $where . " ");
    $row->execute($execute);
    if ($row->rowCount()) {
        $row = $row->fetch(PDO::FETCH_ASSOC);
    } else {
        $row = [];
    }
    return $row;
}

function priceFormat($price)
{
    $priceExplode = explode(".", $price);
    if (isset($priceExplode[1]) && $priceExplode[1]) {
        if (strlen($priceExplode[1]) == 1) {
            return $price . "0";
        }
        return $price;
    }
    return $price . ".00";
}

function timezones()
{
    $timezones = [
        ['label' => '(UTC -12:00) Baker/Howland Island', 'timezone' => '-54000'],
        ['label' => '(UTC -11:00) Niue', 'timezone' => '-50400'],
        ['label' => '(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti', 'timezone' => '-46800'],
        ['label' => '(UTC -9:30) Marquesas Islands', 'timezone' => '-45000'],
        ['label' => '(UTC -9:00) Alaska Standard Time, Gambier Islands', 'timezone' => '-43200'],
        ['label' => '(UTC -8:00) Pacific Standard Time, Clipperton Island', 'timezone' => '-39600'],
        ['label' => '(UTC -7:00) Mountain Standard Time', 'timezone' => '-36000'],
        ['label' => '(UTC -6:00) Central Standard Time', 'timezone' => '-32400'],
        ['label' => '(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time', 'timezone' => '-28800'],
        ['label' => '(UTC -4:30) Venezuelan Standard Time', 'timezone' => '-27000'],
        ['label' => '(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time', 'timezone' => '-25200'],
        ['label' => '(UTC -3:30) Newfoundland Standard Time', 'timezone' => '-23400'],
        ['label' => '(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay', 'timezone' => '-21600'],
        ['label' => '(UTC -2:00) South Georgia/South Sandwich Islands', 'timezone' => '-18000'],
        ['label' => '(UTC -1:00) Azores, Cape Verde Islands', 'timezone' => '-14400'],
        ['label' => '(UTC) Greenwich Mean Time, Western European Time', 'timezone' => '-10800'],
        ['label' => '(UTC +1:00) Central European Time, West Africa Time', 'timezone' => '-7200'],
        ['label' => '(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time', 'timezone' => '-3600'],
        ['label' => '(UTC +3:00) Moscow Time, East Africa Time, Arabia Standard Time', 'timezone' => '0'],
        ['label' => '(UTC +3:30) Iran Standard Time', 'timezone' => '1800'],
        ['label' => '(UTC +4:00) Azerbaijan Standard Time, Samara Time', 'timezone' => '3600'],
        ['label' => '(UTC +4:30) Afghanistan', 'timezone' => '5400'],
        ['label' => '(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time', 'timezone' => '7200'],
        ['label' => '(UTC +5:30) Indian Standard Time, Sri Lanka Time', 'timezone' => '9000'],
        ['label' => '(UTC +5:45) Nepal Time', 'timezone' => '9900'],
        ['label' => '(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time', 'timezone' => '10800'],
        ['label' => '(UTC +6:30) Cocos Islands, Myanmar', 'timezone' => '12600'],
        ['label' => '(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam', 'timezone' => '14400'],
        ['label' => '(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time', 'timezone' => '18000'],
        ['label' => '(UTC +8:45) Australian Central Western Standard Time', 'timezone' => '20700'],
        ['label' => '(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time', 'timezone' => '21600'],
        ['label' => '(UTC +9:30) Australian Central Standard Time', 'timezone' => '23400'],
        ['label' => '(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time', 'timezone' => '25200'],
        ['label' => '(UTC +10:30) Lord Howe Island', 'timezone' => '27000'],
        ['label' => '(UTC +11:00) Srednekolymsk Time, Solomon Islands, Vanuatu', 'timezone' => '28800'],
        ['label' => '(UTC +11:30) Norfolk Island', 'timezone' => '30600'],
        ['label' => '(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time', 'timezone' => '32400'],
        ['label' => '(UTC +12:45) Chatham Islands Standard Time', 'timezone' => '35100'],
        ['label' => '(UTC +13:00) Samoa Time Zone, Phoenix Islands Time, Tonga', 'timezone' => '36000'],
        ['label' => '(UTC +14:00) Line Islands', 'timezone' => '39600']
    ];
    return $timezones;
}

function servicePackageType($type)
{
    switch ($type) {
        case '1':
            return "Default";
            break;
        case '2':
            return "Package";
            break;
        case '3':
            return "Special comments";
            break;
        case '4':
            return "Package comments";
            break;
        case '5':
            return "Comments Like";
            break;
        case '6':
            return "Voting";
            break;
        default:
            return "Subscriptions";
            break;
    }

}
function unlinks($x)
{
    return 1;
}