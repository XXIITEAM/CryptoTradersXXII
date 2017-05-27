
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
$totalVolume = '';
$orderVolume = 0;
$gain = 0;
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
$tHistory = $polo->get_trad('ALL');
foreach($data as $key=>$value) 
{
	if($data[$key]['symbol'] == 'USDT')
			{
				$usd = $data[$key]['price_usd'];
				break;
			}
}

$prixBtc = $tVolume['USDT_BTC']['last'];
// Cycle through the array
$html = array();
$html[0] = "";
$i = 0;
foreach ($pBalances as $cle => $monVolume) 
{															
        $orderVolume = $cBalances[$cle]['onOrders'];
        $volumeTotal = $orderVolume + $monVolume;	
        if($volumeTotal != 0.00000000 &&  $cle != 'USDT') 
        {
                $html[0] .= "<tr>";
                $volumeDispo = $cBalances[$cle]['available'] + 0;
                $btcValue = $cBalances[$cle]['btcValue'];
                $usdtValue = $btcValue * $prixBtc;
                $tDollars = $tDollars + $usdtValue;
                $tBtc = $tBtc + $btcValue;
                $prixUsd = number_format($usdtValue*$usd, 2, '.', '');
                $totalUsd = $totalUsd + $prixUsd;
                $lastBuy = 0;
                if($cle != 'BTC')
                {
                        $totalVolume = $tVolume['BTC_'.$cle]['baseVolume'];
                        $lastPrice = $tVolume['BTC_'.$cle]['last']." BTC";
                }
                else
                {
                        $totalVolume = $tVolume['USDT_BTC']['baseVolume'];
                        $lastPrice = number_format($prixBtc, 2, '.', '').' USDT';
                }
                $usdtFormatValue = number_format($usdtValue, 2, '.', '');
                
              
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
                                           $lastBuy =$tHistory[$keyHisto][$keyHisto2]['rate'];
                                           $lastBuy .= " BTC";
                                           $gain = ( ( $lastPrice - $lastBuy ) / $lastBuy ) * 100;
                                       }
                                       else
                                       {
                                           $lastBuy =number_format($tHistory[$keyHisto][$keyHisto2]['rate'], 2, '.', '');
                                           $lastBuy .= " USDT"; 
                                           $gain = ( ( $lastPrice - $lastBuy ) / $lastBuy ) * 100;
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
               
                $gainFormat = number_format($gain, 2, '.', '');
                if($gainFormat >= 0)
                {
                    $gainFormat = '+ '.$gainFormat;
                }
                $cleId[$i] =  $cle;
                $html[0] .= '<td><b><a http://localhost/CryptoTradersXXII/index.php?page=order&cle='. $cleId[$i].'">'. $cleId[$i].'</a></b></td>';
                $html[0] .= "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeDispo</td>";
                $html[0] .= "<td>$totalVolume</td>";
                $html[0] .= "<td>$lastPrice</td>";
                $html[0] .= "<td>$lastBuy</td>";
                $html[0] .= "<td>$btcValue BTC / $usdtFormatValue USDT / <b>$prixUsd $</b></td>";
                if($gainFormat >= 0)
                {
                   $html[0] .= "<td style='color:green'><b>$gainFormat %</b></td>"; 
                }
                else
                {
                   $html[0] .= "<td style='color:red'><b>$gainFormat %</b></td>";  
                }
                
                $html[0] .= "</tr>";

        }
        if($cle == 'USDT' && $cBalances[$cle]['available'] >= 0.01)
        {
                $lastBuy = 0;
                $html[0] .= "<tr>";	
                $volumeDispo = $cBalances[$cle]['available'] + 0;
                $btcValue = number_format($volumeTotal/$prixBtc, 8, '.', '');
                $tDollars = $tDollars + $volumeTotal;
                $tBtc = $tBtc + $btcValue;
                $prixUsd = number_format($volumeTotal*$usd, 2, '.', '');
                $totalUsd = $totalUsd + $prixUsd;
                $cleId[$i] =  $cle;
                $html[0] .= '<td><b><a href="http://localhost/CryptoTradersXXII/index.php?page=order&cle='. $cleId[$i].'">'. $cleId[$i].'</a></b></td>';
                $html[0] .=  "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeDispo</td>";
                $html[0] .=  "<td>$volumeTotal</td>";
                $volumeTotal = number_format($volumeTotal, 2, '.', '');
                $html[0] .= "<td>$usd $</td>";
                $html[0] .= "<td>$lastBuy</td>";
                $html[0] .= "<td>$btcValue BTC / $volumeTotal USDT / <b>$prixUsd $</b></td>";
                $html[0] .= "<td style='color:green'><b>0 %</b></td>";
                $html[0] .= "</tr>";
        }
     $i ++;   	
}

$tFormatDollars = number_format($tDollars, 2, '.', '');
$tFormatUSD = number_format($totalUsd, 2, '.', '');
$html[1] ="<b>[ $tBtc BTC || $tFormatDollars USDT || $tFormatUSD $ ]</b>";
echo json_encode($html);
exit();