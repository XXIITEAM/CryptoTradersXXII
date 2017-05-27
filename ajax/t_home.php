
<?php

// Init file poloniex.php.
require_once('../include/poloniex_api.php');
require_once('../include/apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
$totalVolume = '';
$orderVolume = 0;
// Call get balances.
$pBalances = $polo->get_balances();
$cBalances = $polo->get_complete_balances();
$tVolume = $polo->get_ticker();
$tHitory = $polo->get_trad('BTC_XRP');
print_r($tHitory);
$tDollars = 0;
$tBtc = 0;
$url="https://api.coinmarketcap.com/v1/ticker/";
$json = file_get_contents($url);
$data = json_decode($json, TRUE);
$totalUsd = 0;

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
                
                $html[0] .= "<td>$cle</td>";
                $html[0] .= "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeDispo</td>";
                $html[0] .= "<td>$totalVolume</td>";
                $html[0] .= "<td>$btcValue BTC / <b>$usdtFormatValue USDT / $prixUsd $</b></td>";
                $html[0] .= "<td>$lastPrice</td>";
                $html[0] .= "<td></td>";
                $html[0] .= "<td></td>";
                $html[0] .= "</tr>";

        }
        if($cle == 'USDT' && $cBalances[$cle]['available'] >= 0.01)
        {
                $html[0] .= "<tr>";	
                $volumeDispo = $cBalances[$cle]['available'] + 0;
                $btcValue = number_format($volumeTotal/$prixBtc,8);
                $tDollars = $tDollars + $volumeTotal;
                $tBtc = $tBtc + $btcValue;
                $prixUsd = number_format($volumeTotal*$usd, 2, '.', '');
                $totalUsd = $totalUsd + $prixUsd;
                $html[0] .= "<td>$cle</td>";
                $html[0] .=  "<td>$volumeTotal</td>";
                $html[0] .= "<td>$volumeDispo</td>";
                $html[0] .=  "<td>$volumeTotal</td>";
                $volumeTotal = number_format($volumeTotal, 2, '.', '');
                $html[0] .= "<td>$btcValue BTC / <b>$volumeTotal USDT / $prixUsd $</b></td>";
                $html[0] .= "<td>$usd $</td>";
                $html[0] .= "<td></td>";
                $html[0] .= "<td></td>";
                $html[0] .= "</tr>";
        }
        	
}

$tFormatDollars = number_format($tDollars, 2, '.', '');
$tFormatUSD = number_format($totalUsd, 2, '.', '');
$html[1] ="<b>[ $tBtc BTC || $tFormatDollars USDT || $tFormatUSD $ ]</b>";
echo json_encode($html);
exit();