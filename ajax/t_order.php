
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
                $i=0;
                foreach($valueHisto as $keyHisto2 => $valueHisto2) 
                {
                    $globalTradeIDOrdre = $valueHisto2['globalTradeID'];
                    $tradeIDOrdre = $valueHisto2['tradeID'];
                    $dateOrdre = $valueHisto2['date'];
                    $rateOrdre = $valueHisto2['rate'];
                    $montantOrdre = $valueHisto2['amount'];
                    $totalOrdre = $valueHisto2['total'];
                    $fraisOrdre = $valueHisto2['fee'];
                    $numOrdre = $valueHisto2['orderNumber'];
                    $typeOrdre = $valueHisto2['type'];
                    $categorieOrdre = $valueHisto2['category'];
                    
                    $html[0] .= "<tr>";        
                    $html[0] .= "<td>$keyHisto</td>";
                    $html[0] .= "<td>$globalTradeIDOrdre</td>";
                    $html[0] .= "<td>$tradeIDOrdre</td>";
                    $html[0] .= "<td>$dateOrdre</td>";
                    $html[0] .= "<td>$rateOrdre</td>";
                    $html[0] .= "<td>$montantOrdre</td>";
                    $html[0] .= "<td>$totalOrdre</td>";
                    $html[0] .= "<td>$fraisOrdre</td>";
                    $html[0] .= "<td>$numOrdre</td>";
                    $html[0] .= "<td>$typeOrdre</td>";
                    $html[0] .= "<td>$categorieOrdre</td>";
                    $html[0] .= "</tr>";
                    $i = $i++;
                }
            }
                                  	
}

echo json_encode($html);
exit();