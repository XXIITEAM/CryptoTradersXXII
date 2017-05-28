
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
// Call get balances.
$tHistory = $polo->get_trad('ALL');
//$tChart = $polo->get_chart('ALL');
//print_r($tChart);
// Cycle through the array
$html2= "";
//$cle ='AMP';
 //$cle = 'XRP';

if(isset($_POST['cleOrder'])){
$cle = $_POST['cleOrder'];
}
else {
    $cle = 'XRP';
}

foreach($tHistory as $keyHisto=>$valueHisto) 
        {
                 if($keyHisto == 'BTC_'.$cle || $keyHisto == 'USDT_'.$cle)
                 {
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
                    
                    $html2 .= "<tr>";        
                    $html2 .= "<td>$keyHisto</td>";
                    $html2 .= "<td>$globalTradeIDOrdre</td>";
                    $html2 .= "<td>$tradeIDOrdre</td>";
                    $html2 .= "<td>$dateOrdre</td>";
                    $html2 .= "<td>$rateOrdre</td>";
                    $html2 .= "<td>$montantOrdre</td>";
                    $html2 .= "<td>$totalOrdre</td>";
                    $html2 .= "<td>$fraisOrdre</td>";
                    $html2 .= "<td>$numOrdre</td>";
                    if($typeOrdre == "buy")
                    {
                        $html2 .= "<td class='orderBuy'><b>Achat</b></td>"; 
                    }
                    else
                    {
                        $html2 .= "<td class='orderSell'><b>Vente</b></td>";  
                    }
                    if($categorieOrdre == "exchange")
                    {
                        $html2 .= "<td class='orderExchange'><b>Exchange</b></td>"; 
                    }
                    else {
                        $html2 .= "<td class='orderMargin'><b>Margin</b></td>"; 
                    }
                    $html2 .= "</tr>";
                }
                break;
                 }                                 	
}
echo $html2;
exit();