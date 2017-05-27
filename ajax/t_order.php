
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
// Call get balances.
$tHistory = $polo->get_trad('ALL');
print_r($tHistory);
// Cycle through the array
$html = array();
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
                    
                    $html[$i] .= "<tr>";        
                    $html[$i] .= "<td>$keyHisto</td>";
                    $html[$i] .= "<td>$globalTradeIDOrdre</td>";
                    $html[$i] .= "<td>$tradeIDOrdre</td>";
                    $html[$i] .= "<td>$dateOrdre</td>";
                    $html[$i] .= "<td>$rateOrdre</td>";
                    $html[$i] .= "<td>$montantOrdre</td>";
                    $html[$i] .= "<td>$totalOrdre</td>";
                    $html[$i] .= "<td>$fraisOrdre</td>";
                    $html[$i] .= "<td>$numOrdre</td>";
                    $html[$i] .= "<td>$typeOrdre</td>";
                    $html[$i] .= "<td>$categorieOrdre</td>";
                    $html[$i] .= "</tr>";
                    $i++;
                }
print_r($html);
            }
                                  	
}

echo json_encode($html);
exit();