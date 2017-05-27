
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
// Call get balances.
$tHistory = $polo->get_trad('ALL');
//print_r($tHistory);
// Cycle through the array
$html = array();
$html[0] = "";
$cle = "XRP";
foreach($tHistory as $keyHisto=>$valueHisto) 
        {
            if($keyHisto == 'BTC_'.$cle || $keyHisto == 'USDT_'.$cle)
            {
                foreach($valueHisto as $keyHisto2 => $valueHisto2) 
                {
                    $globalTradeID = $valueHisto2['globalTradeID'];
                    $tradeID = $valueHisto2['tradeID'];
                    $date = $valueHisto2['date'];
                    $rate = $valueHisto2['rate'];
                    $amount = $valueHisto2['amount'];
                    $total = $valueHisto2['total'];
                    $frais = $valueHisto2['fee'];
                    $html[0] .= "<tr>";        
                    $html[0] .= "<td>$keyHisto</td>";
                    $html[0] .= "<td>$globalTradeID</td>";
                    $html[0] .= "<td>$date</td>";
                    $html[0] .= "<td>$rate</td>";
                    $html[0] .= "<td>$amount</td>";
                    $html[0] .= "<td>$total</td>";
                    $html[0] .= "<td>$frais</td>";
                    $html[0] .= "</tr>";
                }

            }
                                  	
}

echo json_encode($html);
exit();