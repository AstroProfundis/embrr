<?php
function curl_redirect_exec($ch) {
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 301 || $http_code == 302) {
        list($header) = explode("\r\n\r\n", $data, 2);
        $matches = array();
        //this part has been changes from the original
        preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
        $url = trim(str_replace($matches[1],"",$matches[0]));
        //end changes
        $url_parsed = parse_url($url);
        if (isset($url_parsed)) {
            curl_setopt($ch, CURLOPT_URL, $url);
            return curl_redirect_exec($ch);
        }
    }
    return $data;
}

if(isset($_GET['imgurl']))
{
    $url = $_GET['imgurl'];
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE); //301&302
    $ret = curl_redirect_exec($ch);
    $Httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $Hsize = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
    curl_close($ch);
    if($Httpcode == '200')
    {
        $header = substr($ret,0,$Hsize);
        $pat = '/(Content-Type:\s?image\/\w+)/i';
        $matchRet = preg_match_all($pat,$header,$m);
        if($matchRet)
        {
            $header = $m[0][0];
            $ret = substr($ret,$Hsize);
            Header($header);
            echo $ret;
        }
        else
        {
            echo 'image not found';
        }
    }
    else
    {
        echo 'image loading error, code: '.$Httpcode;
    }
}
?>