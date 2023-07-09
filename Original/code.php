<?php

$json = file_get_contents($argv[1]);




foreach (explode("\n", file_get_contents($argv[1])) as $row) {
//{"bin":"45717360","amount":"100.00","currency":"EUR"}
    if (empty($row)) break;
    $p = explode(",",$row);//['"bin":"45717360"','"amount":"100.00"','"currency":"EUR"']
    $p2 = explode(':', $p[0]);//['"bin"','"45717360"']
    $value[0] = trim($p2[1], '"');//'45717360'
    $p2 = explode(':', $p[1]);//['"amount"','"100.00"']
    $value[1] = trim($p2[1], '"');//'100.00'
    $p2 = explode(':', $p[2]);//['"currency"','"EUR"']
    $value[2] = trim($p2[1], '"}');//'EUR'


    //{"number":{},"scheme":"visa","country":{"numeric":"840","alpha2":"US","name":"United States of America","emoji":"🇺🇸","currency":"USD","latitude":38,"longitude":-97},"bank":{"name":"VERMONT NATIONAL BANK","url":"www.communitynationalbank.com","phone":"(802) 744-2287"}}
    $binResults = file_get_contents('https://lookup.binlist.net/' .$value[0]);//'45717360'
    if (!$binResults)
        die('error!');
    $r = json_decode($binResults);
    $isEu = isEu($r->country->alpha2);//"US"

    //{"rates":{"CAD":1.5641,"HKD":9.2041,"ISK":160.0,"PHP":57.587,"DKK":7.4398,"HUF":357.65,"CZK":26.66,"AUD":1.6327,"RON":4.858,"SEK":10.4178,"IDR":17671.49,"INR":87.3415,"BRL":6.3109,"RUB":89.5924,"HRK":7.5368,"JPY":125.82,"THB":37.172,"CHF":1.0768,"SGD":1.6207,"PLN":4.4504,"BGN":1.9558,"TRY":8.8997,"CNY":8.0987,"NOK":10.6933,"NZD":1.7739,"ZAR":19.7876,"USD":1.1876,"MXN":25.1792,"ILS":4.0807,"GBP":0.9219,"KRW":1404.73,"MYR":4.9232},"base":"EUR","date":"2020-09-14"}
    $rate = @json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'), true)['rates'][$value[2]];//'EUR'
    if ($value[2] == 'EUR' or $rate == 0) {
        $amntFixed = $value[1];//same as original amount
    }
    if ($value[2] != 'EUR' or $rate > 0) {
        $amntFixed = $value[1] / $rate;// original amount / rate
    }

    echo $amntFixed * ($isEu == 'yes' ? 0.01 : 0.02);
    print "\n";
}

function isEu($c) {
    $result = false;
    switch($c) {
        case 'AT':
        case 'BE':
        case 'BG':
        case 'CY':
        case 'CZ':
        case 'DE':
        case 'DK':
        case 'EE':
        case 'ES':
        case 'FI':
        case 'FR':
        case 'GR':
        case 'HR':
        case 'HU':
        case 'IE':
        case 'IT':
        case 'LT':
        case 'LU':
        case 'LV':
        case 'MT':
        case 'NL':
        case 'PO':
        case 'PT':
        case 'RO':
        case 'SE':
        case 'SI':
        case 'SK':
            $result = 'yes';
            return $result;
        default:
            $result = 'no';
    }
    return $result;
}
