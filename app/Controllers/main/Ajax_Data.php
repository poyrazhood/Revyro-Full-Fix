<?php

namespace App\Controllers\main;

use App\Controllers\BaseController;
use App\Models\clients_favorite;
use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Ajax_Data extends BaseController
{
    function format_amount_with_no_e($amount) {
    $amount = (string)$amount; // cast the number in string
    $pos = stripos($amount, 'E-'); // get the E- position
    $there_is_e = $pos !== false; // E- is found

    if ($there_is_e) {
        $decimals = intval(substr($amount, $pos + 2, strlen($amount))); // extract the decimals
        $amount = number_format($amount, $decimals, '.', ','); // format the number without E-
    }

    return $amount;
}
    function index()
    {
        ob_start(function($data) {
				$replace = [
					'/\>[^\S ]+/s'   => '>',
					'/[^\S ]+\</s'   => '<',
					'/([\t ])+/s'  => ' ',
					'/^([\t ])+/m' => '',
					'/([\t ])+$/m' => '',
					'~//[a-zA-Z0-9 ]+$~m' => '',
					'/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
					'/\>[\r\n\t ]+\</s'    => '><',
					'/}[\r\n\t ]+/s'  => '}',
					'/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
					'/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
					'/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
					'/\),[\r\n\t ]+/s'  => '),',
				];
				$data = preg_replace(array_keys($replace), array_values($replace), $data);
				$remove = ['</option>', '</li>', '</dt>', '</dd>', '</tr>', '</th>', '</td>'];
				$data = str_ireplace($remove, '', $data);
				return $data;
			});
        global $conn;
        global $_SESSION;

        $session = \Config\Services::session();
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $user = $this->getuser;
                if ($session->get('neira_userlogin')) {

        include APPPATH . 'ThirdParty/dil/'.$user["lang"].'.php';
        }else{
            include APPPATH . 'ThirdParty/dil/'.$settings["site_language"].'.php';
        }
        $settings = $this->settings;

        if ($settings["site_currency"] == "TRY") {
            $currency = "₺";
        } elseif ($settings["site_currency"] == "USD") {
            $currency = "$";
        } elseif ($settings["site_currency"] == "EUR") {
            $currency = "€";
        }
        $action = $_POST["action"];

        if ($action == "services_list"):
            if (!isset($_POST["category"])):
                $serviceList = "<option value='0'>" . $languageArray["neworder.no.service"] . "</option>";
                echo json_encode(['services' => $serviceList]);
                return 1;
            endif;
            $category = $_POST["category"];
            if ($category != -1) {

                $category_model = $conn->prepare("SELECT * FROM categories WHERE category_id =:id");
                $category_model->execute(array('id'=>$category));
                $category_model_fetch = $category_model->fetch(PDO::FETCH_ASSOC);
                if($category_model_fetch['price_line']){
                    $desc_price = $category_model_fetch['fiyat_siralama']?"ASC":"DESC";
                    $services = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_type=:type ORDER BY service_price {$desc_price} ");
                }else{
                $services = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_type=:type ORDER BY service_line ");
                }
                $services->execute(array('c_id' => $category, 'type' => 2));
                $services = $services->fetchAll(PDO::FETCH_ASSOC);

                if ($services):
                    $serviceList = "";
                else:
                    $serviceList = "<option value='0'>" . $languageArray["neworder.no.service"] . "</option>";
                endif;

                foreach ($services as $service) {
                    $search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id ");
                    $search->execute(array("service" => $service["service_id"], "c_id" => $user["client_id"]));

                    if ($service["service_secret"] == 2 || $search->rowCount()):
                        $multiName = json_decode($service["name_lang"], true);
                        if ($multiName[$user["lang"]]):
                            $name = $multiName[$user["lang"]];
                        else:
                            $name = $service["service_name"];
                        endif;
                        $serviceList .= "<option value='" . $service['service_id'] . "' ";
                        if (isset($_SESSION["data"]["services"]) && $_SESSION["data"]["services"] == $service['service_id']):
                            $serviceList .= "selected";
                        endif;
                        $serviceList .= ">" . $service["service_id"] . " - " . $name . " - " . priceFormat(service_price($service["service_id"])) . $currency . "</option>";
                    endif;
                }
            } else {
                $serviceList = "";
                $userfav = $conn->prepare("SELECT * FROM client_favorite WHERE user_id = :userid");
                $userfav->execute(array('userid' => $user['client_id']));
                $userfav = $userfav->fetchAll(PDO::FETCH_ASSOC);
                foreach ($userfav as $userf) {

                    $service = $conn->prepare("SELECT * FROM services WHERE service_id=:s_id ORDER BY service_line ");
                    $service->execute(array('s_id' => $userf['services_id']));
                    $service = $service->fetch(PDO::FETCH_ASSOC);
                    $search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id ");
                    $search->execute(array("service" => $service["service_id"], "c_id" => $user["client_id"]));

                    if ($service["service_secret"] == 2 || $search->rowCount()):
                        $multiName = json_decode($service["name_lang"], true);
                        if ($multiName[$user["lang"]]):
                            $name = $multiName[$user["lang"]];
                        else:
                            $name = $service["service_name"];
                        endif;
                        $serviceList .= "<option value='" . $service['service_id'] . "' ";
                        if (isset($_SESSION["data"]["services"]) && $_SESSION["data"]["services"] == $service['service_id']):
                            $serviceList .= "selected";
                        endif;
                        $serviceList .= ">" . $service["service_id"] . " - " . $name . " - " . priceFormat(service_price($service["service_id"])) . $currency . "</option>";
                    endif;
                }
            }
            echo json_encode(['services' => $serviceList]);

        elseif ($action == "service_detail"):
            if (!isset($_POST["service"])) {
                return 1;
            }
            $s_id = $_POST["service"];
            $service = $conn->prepare("SELECT * FROM services WHERE service_id=:s_id");
            $service->execute(array(
                's_id' => $s_id
            ));
            $service = $service->fetch(PDO::FETCH_ASSOC);
            $service["service_price"] = service_price($service["service_id"]);
            $serviceDetails = "";

            $multiDesc = json_decode($service["description_lang"], true);

            if (isset($multiDesc[$user["lang"]]) && $multiDesc[$user["lang"]]):
                $desc = $multiDesc[$user["lang"]];
            else:
                $desc = $service["service_description"];
            endif;

            if ($desc):
                $description = str_replace("\n", "", $desc);
                $serviceDetails .= '<div class="form-group fields" id="description">
<label for="service_description" class="control-label">' . $languageArray["neworder.description"] . '</label>
<div class="panel-body border-solid border-rounded" id="service_description">
' . $description . '
</div>
</div>';
            endif;
            if ($service["service_package"] == 1 || $service["service_package"] == 2 || $service["service_package"] == 3 || $service["service_package"] == 4):
                if ($service["want_username"] == 2):
                    $link_type = $languageArray["neworder.username"];
                else:
                    $link_type = $languageArray["neworder.url"];
                endif;
                if($settings['site_theme'] == 'smmspot'){
                $serviceDetails .= '<div class="form-group fields" id="order_link"><div class="fga mb-4"><label class="fla" for="orderLink">'.$link_type.'</label><div class="fg"><div class="fg-icon"><i class="far fa-link"></i></div><input class="fg-control" name="link" value="" type="text" id="field-orderform-fields-link"></div></div></div>';
}else{
            $serviceDetails .= '<div class="form-group fields" id="order_link">
<label class="control-label" for="field-orderform-fields-link">' . $link_type . '</label>
<input class="form-control" name="link" value="" type="text" id="field-orderform-fields-link">
</div>';
}
            endif;
            if ($service["service_package"] == 1):
                if($settings['site_theme'] == 'smmspot'){
                     $serviceDetails .= '<div class="form-group fields" id="order_quantity"><div class="fga mb-4"><label class="fla" for="orderQuantitiy">' . $languageArray["neworder.quantity"] . '</label><div class="fg"><div class="fg-icon"><i class="far fa-sort-numeric-up-alt"></i></div><input class="fg-control" name="quantity" value="" type="text" id="neworder_quantity"></div></div></div><small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>';


}else{
     $serviceDetails .= '<div class="form-group fields" id="order_quantity">
<label class="control-label" for="field-orderform-fields-quantity">' . $languageArray["neworder.quantity"] . '</label>
<input class="form-control" name="quantity" value="" type="text" id="neworder_quantity">
</div>
<small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>
';
              }
            endif;
            if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13 || $service["service_package"] == 14 || $service["service_package"] == 15):
                $x = isset($_SESSION["data"]["username"])?$_SESSION["data"]["username"]:"";
                $serviceDetails .= '<div class="form-group fields" id="order_link">
<label class="control-label" for="field-orderform-fields-link">' . $languageArray["neworder.username"] . '</label>
<input class="form-control" name="username" value="' . $x . '" type="text" id="field-orderform-fields-link">
</div>';
            endif;
            if ($service["service_package"] == 3):
                $serviceDetails .= '<div class="form-group fields" id="order_quantity">
<label class="control-label" for="field-orderform-fields-quantity">' . $languageArray["neworder.quantity"] . '</label>
<input class="form-control" name="quantity" value="" type="text" id="neworder_quantity" disabled="">
</div>
<small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>
';
            endif;
            if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13):
                $pos = isset($_SESSION["data"]["posts"])?$_SESSION["data"]["posts"]:"";
                $min = isset($_SESSION["data"]["min"])?$_SESSION["data"]["min"]:"";
                $max = isset($_SESSION["data"]["max"])?$_SESSION["data"]["max"]:"";
                $expr = isset($_SESSION["data"]["expiry"])?$_SESSION["data"]["expiry"]:"";

                $serviceDetails .= '<div class="form-group fields" id="order_link">
<label class="control-label" for="field-orderform-fields-link">' . $languageArray["neworder.posts"] . '</label>
<input class="form-control" name="posts" value="' . $pos . '" type="text" id="field-orderform-fields-link">
</div>';
                $serviceDetails .= '<div class="form-group fields" id="order_min">
<label class="control-label" for="order_count">' . $languageArray["neworder.quantity"] . '</label>
<div class="row">
<div class="col-md-6">
<input type="text" class="form-control" id="order_count" name="min" value="' . $min . '" placeholder="Minimum">
</div>
<div class="col-md-6">
<input type="text" class="form-control" id="order_count" name="max" value="' . $max. '" placeholder="Maximum">
</div>
</div>
<small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>
</div>
<div class="form-group fields" id="order_delay">
<div class="row">
<div class="col-md-6">
<label class="control-label" for="field-orderform-fields-delay">' . $languageArray["neworder.delay"] . '</label>
<select class="form-control" name="delay" id="field-orderform-fields-delay">
<option value="0" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 0):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>' . $languageArray["neworder.no.delay"] . '</option>
<option value="300" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 300):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>5 ' . $languageArray["neworder.minute"] . '</option>
<option value="600" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 600):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>10 ' . $languageArray["neworder.minute"] . '</option>
<option value="900" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 900):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>15 ' . $languageArray["neworder.minute"] . '</option>
<option value="1800" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 1800):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>30 ' . $languageArray["neworder.minute"] . '</option>
<option value="3600" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 3600):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>60 ' . $languageArray["neworder.minute"] . '</option>
<option value="5400" ';
                if (isset($_SESSION["data"]["delay"]) && $_SESSION["data"]["delay"] == 5400):
                    $serviceDetails .= ' selected';
                endif;
                $serviceDetails .= '>90 ' . $languageArray["neworder.minute"] . '</option>
</select>
</div>
<div class="col-md-6">
<label for="field-orderform-fields-expiry">' . $languageArray["neworder.expiry"] . '</label>
<div class="input-group" id="datetimepicker">
<input class="form-control" name="expiry" id="expiryDate" value="' . $expr . '" type="date" autocomplete="off">
<span class="input-group-btn">
<button class="btn btn-default clear-datetime" id="clearExpiry" type="button"><span class="fa far fa-trash-alt"></span></button>
</span>
</div>
</div>
</div>
</div>';
            endif;
            if ($service["service_package"] == 3 || $service["service_package"] == 4):
                $ses = isset($_SESSION["data"]["comments"]) ? $_SESSION["data"]["comments"] : "";
                $serviceDetails .= '<div class="form-group fields" id="order_comment">
<label class="control-label">' . $languageArray["neworder.comments"] . '</label>
<textarea class="form-control counter" name="comments" id="neworder_comment" cols="30" rows="10" data-related="quantity">' . $ses . '</textarea>
</div>';
            endif;
            if ($service["service_package"] == 5):
                if ($service["want_username"] == 2):
                    $link_type = $languageArray["neworder.username"];
                else:
                    $link_type = $languageArray["neworder.url"];
                endif;
                $serviceDetails .= '<div class="form-group fields" id="order_link">
<label class="control-label" for="field-orderform-fields-link">' . $link_type . '</label>
<input class="form-control" name="link" value="" type="text" id="field-orderform-fields-link">
</div>';
                $serviceDetails .= '<div class="form-group fields" id="order_quantity">
<label class="control-label" for="field-orderform-fields-quantity">' . $languageArray["neworder.quantity"] . '</label>
<input class="form-control" name="quantity" value="" type="text" id="neworder_quantity">
</div>
<small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>
';
                $serviceDetails .= '<div class="form-group fields" id="order_username">
<label class="control-label">' . $languageArray["neworder.username"] . '</label>
<input class="form-control" name="order_username" id="order_username" cols="30" rows="10" data-related="order_username"></input>
</div>';
            endif;
                        if ($service["service_package"] == 6):
                if ($service["want_username"] == 2):
                    $link_type = $languageArray["neworder.username"];
                else:
                    $link_type = $languageArray["neworder.url"];
                endif;
                $serviceDetails .= '<div class="form-group fields" id="order_link">
<label class="control-label" for="field-orderform-fields-link">' . $link_type . '</label>
<input class="form-control" name="link" value="" type="text" id="field-orderform-fields-link">
</div>';
                $serviceDetails .= '<div class="form-group fields" id="order_quantity">
<label class="control-label" for="field-orderform-fields-quantity">' . $languageArray["neworder.quantity"] . '</label>
<input class="form-control" name="quantity" value="" type="text" id="neworder_quantity">
</div>
<small class="help-block min-max">Min: ' . number_format($service["service_min"], 0, '.', '.') . ' - Max: ' . number_format($service["service_max"], 0, '.', '.') . '</small>
';
                $serviceDetails .= '<div class="form-group fields" id="answer_number">
<label class="control-label">Answer Number</label>
<input class="form-control" name="answer_number" id="answer_number" cols="30" rows="10" data-related="answer_number"></input>
</div>';
            endif;
            if ($service["service_dripfeed"] == 2):
                if ($_SESSION["data"]["check"]):
                    $check = "checked";
                endif;


                if ($settings['site_theme'] == 'platinum') {

                    $serviceDetails .= '<div id="dripfeed">
             
             
<div class="form-group fields" id="order_check">

<label class="control-label has-depends " for="dripfeedcheckbox">

<div class="custom-control custom-checkbox">
<input name="name" value="1" class="custom-control-input" type="checkbox" ' . $check . ' id="dripfeedcheckbox">
                <label class="custom-control-label" for="remember">' . $languageArray["neworder.dripfeed.title"] . '</label>
              </div>
</label>
<div class="hidden" id="dripfeed-options">
<div class="form-group">
<label class="control-label" for="dripfeed-runs">' . $languageArray["neworder.runs.title"] . '</label>
<input class="form-control" name="runs" value="' . $_SESSION["data"]["runs"] . '" type="text" id="dripfeed-runs">
</div>
<div class="form-group">
<label class="control-label" for="dripfeed-interval">' . $languageArray["neworder.interval.title"] . '</label>
<input class="form-control" name="interval" value="' . $_SESSION["data"]["interval"] . '" type="text" id="dripfeed-interval">
</div>
<div class="form-group">
<label class="control-label" for="dripfeed-totalquantity">' . $languageArray["neworder.totalquantity.title"] . '</label>
<input class="form-control" name="total_quantity" value="' . $_SESSION["data"]["total_quantity"] . '" type="text" id="dripfeed-totalquantity" readonly="">
</div>
</div>
</div>
</div>';

                } else {

                    $serviceDetails .= '<div id="dripfeed">
<div class="form-group fields" id="order_check">
<label class="control-label has-depends " for="dripfeedcheckbox">
<input name="name" value="1" class="custom-control-input" type="checkbox" ' . $check . ' id="dripfeedcheckbox">
' . $languageArray["neworder.dripfeed.title"] . '
</label>
<div class="hidden" id="dripfeed-options">
<div class="form-group">
<label class="control-label" for="dripfeed-runs">' . $languageArray["neworder.runs.title"] . '</label>
<input class="form-control" name="runs" value="' . $_SESSION["data"]["runs"] . '" type="text" id="dripfeed-runs">
</div>
<div class="form-group">
<label class="control-label" for="dripfeed-interval">' . $languageArray["neworder.interval.title"] . '</label>
<input class="form-control" name="interval" value="' . $_SESSION["data"]["interval"] . '" type="text" id="dripfeed-interval">
</div>
<div class="form-group">
<label class="control-label" for="dripfeed-totalquantity">' . $languageArray["neworder.totalquantity.title"] . '</label>
<input class="form-control" name="total_quantity" value="' . $_SESSION["data"]["total_quantity"] . '" type="text" id="dripfeed-totalquantity" readonly="">
</div>
</div>
</div>
</div>';

                }


            endif;
            if (isset($_POST["runs"])) {
                $runs = $_POST["runs"];
                if (!$runs):
                    $runs = 1;
                endif;
            } else {
                $runs = 2;
            }

            if ($runs < 1) {
                $runs = 1;
            }


            $dripfeed = isset($_POST["dripfeed"]) ? $_POST["dripfeed"] : "bos";
            $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : 1;
            if ($s_id != 0 && $dripfeed == "bos"):
                $price = $quantity * $service["service_price"] / 1000;
                $data = ['details' => $serviceDetails, 'price' => priceFormat($price) . $currency];
            elseif ($s_id != 0 && $dripfeed == "var"):
                $price = $runs * $quantity * $service["service_price"] / 1000;
                $data = ['details' => $serviceDetails, 'price' => priceFormat($price) . $currency];
            elseif ($s_id != 0 && !isset($dripfeed) && $service["service_package"] != 2):
                $data = ['details' => $serviceDetails];
            elseif (!isset($dripfeed) && $service["service_package"] == 2):
                $price = $service["service_price"];
                $data = ['details' => $serviceDetails, 'price' => priceFormat($price) . $currency];
            else:
                $data = ['empty' => 1];
            endif;
            if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13):
                $data["sub"] = 1;
            elseif($service["service_package"] == 14 || $service["service_package"] == 15):
                $data['price'] = (priceFormat($price)*1000).$currency;

            elseif($service["service_package"] == 4 || $service["service_package"] == 2):
                $data['price'] = (priceFormat($price)*1000).$currency;
            else:
                $data['price'] = "0".$currency;
            endif;

            echo json_encode($data);
            unset($_SESSION["data"]);

        elseif ($action == "service_price"):
            $service = $_POST["service"];
            $services = $conn->prepare("SELECT * FROM services WHERE service_id=:s_id");
            $services->execute(array(
                's_id' => $service
            ));
            $services = $services->fetch(PDO::FETCH_ASSOC);
            $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : 1;
            $comments = isset($_POST["comments"]) ? $_POST["comments"] : "";
            $dripfeed = isset($_POST["dripfeed"]) ? $_POST["dripfeed"] : "";
            $runs = isset($_POST["runs"]) ? $_POST["runs"] : 0;
            if (!$runs):
                $runs = 1;
            endif;

            if ($runs < 1) {
                $runs = 1;
            }

            if ($quantity < 1) {
                $quantity = 1;
            }

            $price = service_price($service) / 1000;
            if ($comments):
                $quantity = count(explode("\n", $comments));
            endif;

            if ($quantity == 0) {
                $totalPrice = service_price($service) . $currency;
            } elseif ($dripfeed == "var") {
                $totalPrice = priceFormat($price * $quantity * $runs);
                $totalPrice .= $currency;
            } else {
                $totalPrice = priceFormat($price * $quantity);
                $totalPrice .= $currency;
            }
            if($services['service_package'] == 4){
                $totalPrice = priceFormat($price*1000);
                $totalPrice.= $currency;
            }
            $totalPrice = $this->format_amount_with_no_e($totalPrice);
            echo json_encode(['price' => $totalPrice, 'commentsCount' => $quantity, 'totalQuantity' => $runs * $quantity]);
        elseif ($action == "favorite_ekle"):
            $id = $_POST['favori'];
            $user_id = $user['client_id'];
            $clients = new clients_favorite();
            if (!$clients->where(array('user_id' => $user_id, 'services_id' => $id,))->countAllResults()) {
                $clients->save(array(
                    'user_id' => $user_id,
                    'services_id' => $id,
                ));
                $data = [
                    'status'=>202,
                    'message' => 'Success',
                    'id' => $id
                    ];
                return $this->response->setJSON($data);
            } else {
                $clients->where(array('user_id' => $user_id, 'services_id' => $id))->delete();

                $data = [
                    'status'=>202,
                    'message' => 'Failed',
                    'id' => $id
                    ];
                return $this->response->setJSON($data);
            }
            elseif ($action == "favorite_kontrol"):
            $id = $_POST['favori'];
            $user_id = $user['client_id'];
            $clients = new clients_favorite();
            if (!$clients->where(array('user_id' => $user_id))->countAllResults()) {
                $data = [
                    'status'=>200,
                    'field' => 0
                    ];
                return $this->response->setJSON($data);
            } else {
                $tum = $clients->select("services_id")->where(array('user_id' => $user_id))->get()->getResultArray();
                $data = [
                    'status'=>200,
                    'field' => $tum
                    ];
                return $this->response->setJSON($data);
            }
        endif;

    }
}