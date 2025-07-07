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
    $settings = $ss->where('id', '1')->get()->getResultArray()[0];
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
              <mainbody>
                <header>
                  <company>Netgsm</company>
                  <usercode>' . $settings["sms_user"] . '</usercode>
                  <password>' . $settings["sms_pass"] . '</password>
                  <type>1:n</type>
                  <msgheader>' . $settings["sms_title"] . '</msgheader>
                </header>
                <body>
                  <msg><![CDATA[' . $text . ']]></msg>
                  <no>' . $tel . '</no>
                </body>
              </mainbody>';

    $send = XMLPOST('http://api.netgsm.com.tr/xmlbulkhttppost.asp', $xml);
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
    $settings = $ss->where('id', '1')->get()->getResultArray()[0];
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
              <mainbody>
                <header>
                  <company>Netgsm</company>
                  <usercode>' . $settings["sms_user"] . '</usercode>
                  <password>' . $settings["sms_pass"] . '</password>
                  <type>1:n</type>
                  <msgheader>' . $settings["sms_title"] . '</msgheader>
                </header>
                <body>
                  <msg><![CDATA[' . $text . ']]></msg>
                  ' . $tel . '
                </body>
              </mainbody>';

    $send = XMLPOST('http://api.netgsm.com.tr/xmlbulkhttppost.asp', $xml);
    $sonuc = substr($send, 0, 2);
    if ($sonuc["0"] == 00) {
        return true;
    } else {
        return false;
    }

}

?>