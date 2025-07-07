<?php
class SMMApi
{
    public $api_url = '';
    public $apiKEY = '';
    public function action($data, $api)
    {
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];

        if ($settings["proxy_mode"] == 1) {

            $proxy = new \App\Models\proxy();
            $proxy = $proxy->where('id', '1')->get()->getResultArray()[0];

            $proxysx = base64_decode($proxy['ip']) . ":" . base64_decode($proxy['port']);
            $proxyauth = base64_decode($proxy['user']) . ":" . base64_decode($proxy['pass']);
        }
        $ch = curl_init($api);
        if ($settings["proxy_mode"] == 1) {
            curl_setopt($ch, CURLOPT_PROXY, $proxysx);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
        }
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        //curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }

        curl_close($ch);
        return json_decode($result);
    }
}
class socialsmedia_api
{
    private $data = array();
    function query($data = array())
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $data["apiurl"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'jsonapi' => json_encode(array_merge($this->data, $data), JSON_UNESCAPED_UNICODE)
            ))
        ));
        $cr = curl_exec($ch);
        if (curl_errno($ch) == 0 && !empty($cr))
            return @json_decode($cr, true);
        else
            return false;
    }
}
