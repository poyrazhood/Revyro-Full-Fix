<?php
function XMLPOST($PostAddress, $xmlData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $PostAddress);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=UTF-8"));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    $result = curl_exec($ch);
    return $result;
}

function SMSUser($tel, $text)
{
    global $conn, $settings;
    $ss = new \App\Models\Settings();
    $settings = $ss->where('id','1')->get()->getResultArray()[0];
    $xml = "" .
        "<sms>" .
        "<username>" . $settings["sms_user"] . "</username>" .
        "<password>" . $settings["sms_pass"] . "</password>" .
        "<header>" . $settings["sms_title"] . "</header>" .
        "<validity>2880</validity>" .
        "<message>" .
        "<gsm>" .
        "<no>" . $tel . "</no>" .
        "</gsm>" .
        "<msg><![CDATA[" . $text . "]]></msg>" .
        "</message>" .
        "</sms>";

    $send = XMLPOST('http://sms.bizimsms.mobi:8080/api/smspost/v1', $xml);
    $sonuc = substr($send, 0, 2);
    if ($sonuc["0"] == 00) {
        return true;
    } else {
        return false;
    }
}

function SMSToplu($tel, $text)
{
    global $conn, $settings;
    $ss = new \App\Models\Settings();
    $settings = $ss->where('id','1')->get()->getResultArray()[0];
    $xml = "" .
        "<sms>" .
        "<username>" . $settings["sms_user"] . "</username>" .
        "<password>" . $settings["sms_pass"] . "</password>" .
        "<header>" . $settings["sms_title"] . "</header>" .
        "<validity>2880</validity>" .
        "<message>" .
        "<gsm>" .
        $tel .
        "</gsm>" .
        "<msg><![CDATA[" . $text . "]]></msg>" .
        "</message>" .
        "</sms>";
    $send = XMLPOST('http://sms.bizimsms.mobi:8080/api/smspost/v1', $xml);
    $sonuc = substr($send, 0, 2);
    if ($sonuc["0"] == 00) {
        return true;
    } else {
        return false;
    }

}

?>