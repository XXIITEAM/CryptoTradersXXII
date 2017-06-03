
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
$totalVolume = '';
$orderVolume = 0;
$gain = 0;
$gainTotal =0;
// Call get balances.
$pBalances = $polo->get_balances();
$cBalances = $polo->get_complete_balances();
$tVolume = $polo->get_ticker();
$tDollars = 0;
$tBtc = 0;
$url="https://api.coinmarketcap.com/v1/ticker/";
$json = file_get_contents($url);
$data = json_decode($json, TRUE);
$totalUsd = 0;
$lastBuy = 0;
$lastPrice = 0;
$monnaies = 'BTC';
$tHistory = $polo->get_trad('ALL');
foreach($data as $key=>$value) 
{
	if($data[$key]['symbol'] == 'USDT')
			{
				$usd = $data[$key]['price_usd'];
				break;
			}
}


// Cycle through the array
$html = array();
$html[0] = "";
$i = 0;
if(isset($_POST["filtre"]))
{
    $filtre = 'tous';
}
else
{
    $filtre = "";
}
foreach ($pBalances as $cle => $monVolume) 
{	$prixBtc = $tVolume['USDT_BTC']['last'];													
        $orderVolume = $cBalances[$cle]['onOrders'];
        $volumeTotal = number_format($orderVolume + $monVolume, 8, '.', '');	
        if($volumeTotal != 0.00000000 &&  $cle != 'USDT' || $filtre == 'tous') 
        {
                $html[0] .= "<tr>";
                $volumeDispo = number_format($cBalances[$cle]['available'] + 0, 8, '.', '');
                $btcValue = $cBalances[$cle]['btcValue'];
                $usdtValue = $btcValue * $prixBtc;
                $tDollars = $tDollars + $usdtValue;
                $tBtc = $tBtc + $btcValue;
                $prixUsd = number_format($usdtValue*$usd, 2, '.', '');
                $totalUsd = $totalUsd + $prixUsd;
                $lastBuy = 0;
                 foreach($tHistory as $keyHisto=>$valueHisto) 
                        {
                            if($keyHisto == 'BTC_'.$cle || $keyHisto == 'USDT_'.$cle)
                            {
                                foreach($tHistory[$keyHisto] as $keyHisto2=>$valueHisto2) 
                                {
                                    if($tHistory[$keyHisto][$keyHisto2]['type'] == 'buy')
                                    {      
                                       if($keyHisto == 'BTC_'.$cle)
                                       {
                                           $monnaies = 'BTC';
                                           $lastBuy =$tHistory[$keyHisto][$keyHisto2]['rate'];
                                           $lastBuy .= " BTC";
                                       }
                                       else
                                       {
                                           $monnaies = 'USDT';
                                           $lastBuy =number_format($tHistory[$keyHisto][$keyHisto2]['rate'], 2, '.', '');
                                           $lastBuy .= " USDT"; 
                                           
                                       }
                                       break;
                                    }
                                    
                                }

                            }
                            if($lastBuy > 0)
                            {
                                break;
                            }
                        }
                if($cle != 'BTC')
                {
                        if(isset($tVolume['BTC_'.$cle]))
                        {        
                            $totalVolume = $tVolume['BTC_'.$cle]['baseVolume'];
                            if($monnaies == 'BTC')
                            {
                                $lastPrice = $tVolume['BTC_'.$cle]['last']." BTC";
                            }
                            else
                            {
                                if(isset($tVolume['USDT_'.$cle]))
                                {
                                $lastPrice = number_format($tVolume['USDT_'.$cle]['last'], 2, '.', '')." USDT";
                                }
                            }
                        }
                }
                else
                {
                        $totalVolume = number_format($tVolume['USDT_BTC']['baseVolume'], 8, '.', '');
                        $lastPrice = number_format($prixBtc, 2, '.', '').' USDT';                       
                }
                $usdtFormatValue = number_format($usdtValue, 2, '.', '');
                
              
                if($lastBuy > 0)  
                {
                $gain = ( ( $lastPrice - $lastBuy ) / $lastBuy ) * 100;
                }
                $gainFormat = number_format($gain, 2, '.', '');
                if($gainFormat >= 0)
                {
                    $gainFormat = '+'.$gainFormat;
                }
                $cleId[$i] =  $cle;
                $html[0] .= '<td><b><a href="index.php?page=order&monnaie='. $cleId[$i].'">'. $cleId[$i].'</a></b></td>';
                $html[0] .= "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeDispo</td>";
                $html[0] .= "<td>$totalVolume</td>";
                $html[0] .= "<td>$lastPrice</td>";
                $html[0] .= "<td>$lastBuy</td>";
                $html[0] .= "<td>$btcValue BTC / $usdtFormatValue USDT / <b>$prixUsd $</b></td>";
                if($gainFormat >= 0)
                {
                   $html[0] .= "<td class='pourcentGain'><b>$gainFormat %</b></td>"; 
                }
                else
                {
                   $html[0] .= "<td class='pourcentPerte'><b>$gainFormat %</b></td>";  
                }
                $gainTotal = $gainTotal+$gain;
                $html[0] .= "</tr>";
                
                
                
        }
        if($cle == 'USDT')
        {
                $lastBuy = 0;
                $html[0] .= "<tr>";
                $btcValue = number_format($volumeTotal/$prixBtc, 8, '.', '');
                $tDollars = $tDollars + $volumeTotal;
                $tBtc = $tBtc + $btcValue;
                $prixUsd = number_format($volumeTotal*$usd, 2, '.', '');
                $totalUsd = $totalUsd + $prixUsd;
                $cleId[$i] =  $cle;
                $html[0] .= '<td><b><a href="index.php?page=order&monnaie='. $cleId[$i].'">'. $cleId[$i].'</a></b></td>';
                $html[0] .=  "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeTotal</td>";
                $html[0] .=  "<td>$volumeTotal</td>";
                $volumeTotal = number_format($volumeTotal, 2, '.', '');
                $html[0] .= "<td>$usd $</td>";
                $html[0] .= "<td>-</td>";
                $html[0] .= "<td>$btcValue BTC / $volumeTotal USDT / <b>$prixUsd $</b></td>";
                $html[0] .= "<td>-</td>";
                $html[0] .= "</tr>";
        }
     $i ++;   	
}

$tFormatDollars = number_format($tDollars, 2, '.', '');
$tFormatUSD = number_format($totalUsd, 2, '.', '');

//---------------------Total gains/pertes en % et $
$gFormatTotal = number_format($gainTotal, 2, '.', '');
$totalGainCalcul = str_replace( ".", "", $gFormatTotal);
if ($gFormatTotal > 100.00) {
    $totalGainCalcul1 = "2.".$totalGainCalcul;
} 
else {
    $totalGainCalcul1 = "1.".$totalGainCalcul;
}
$totalGainDol = $tFormatUSD / $totalGainCalcul1;
$totalGainDol1 = $tFormatUSD - $totalGainDol;
$gFormatTotalGainDol1 = number_format($totalGainDol1, 2, '.', '');
$html[1] ="<b>[ $tBtc BTC || $tFormatDollars USDT || $tFormatUSD $ ]</b>";
if($gFormatTotal >= 0)
    {
       $html[2] = "<tr>
                <th colspan=6></th>
                <th colspan=1 style='text-align:center'>Gains : $gFormatTotalGainDol1 $</th>
                <th class='pourcentGain' colspan=1 style='text-align:center'>Gains : $gFormatTotal %</th>
                </tr>";
    }
else
    {
        $html[2] = "<tr>
                <th colspan=6></th>
                <th colspan=1 style='text-align:center'>Pertes : $gFormatTotalGainDol1 $</th>
                <th class='pourcentPerte' colspan=1 style='text-align:center'>Pertes : $gFormatTotal %</th>
                </tr>";
    }
//----------------------------------Fin calcul Gain Total
    
echo json_encode($html);
exit();