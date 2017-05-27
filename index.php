<?php

// Init file poloniex.php.
require_once('poloniex_api.php');
require_once('apikey.php');

// Nouvelle instance de la classe poloniex.
$polo = new Poloniex_API($api_key, $secret_key);
$totalVolume = '';
$orderVolume = 0;
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
foreach($data as $key=>$value) 
{
	if($data[$key]['symbol'] == 'USDT')
			{
				$usd = $data[$key]['price_usd'];
				break;
			}
}
?>
<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from v4-alpha.getbootstrap.com/examples/dashboard/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2017 15:31:04 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">
	<meta http-equiv="refresh" content="10" />

    <title>CryptoTraders</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
      <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">Tableau de bord</a>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Accueil <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Options</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Aide</a>
          </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button>
        </form>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">Overview <span class="sr-only">(current)</span></a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="#">Nav item</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="#">Nav item again</a>
            </li>
          </ul>
        </nav>
        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
          <h3><b>Tableau de bords</b></h3>

          <section class="row text-center placeholders">
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <div class="text-muted">Something else</div>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
          </section>

          <h3><b>Mon compte</b></h3>
				<div id="totalCompte" style='color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
            <table class="table table-sortable">
              <thead>
                <tr>
                  <th>Crypto</th>
                  <th>Mon volume</th>
				  <th>Mon volume dispo</th>
				  <th>Volume total</th>
                  <th>BTC / USDT / USD</th>
                  <th>Dernier achat</th>
				  <th>Bénéfice</th>
                </tr>
              </thead>
              <tbody>
               <?php 
				$prixBtc = $tVolume['USDT_BTC']['last'];
				// Cycle through the array
				foreach ($pBalances as $cle => $monVolume) 
				{															
					$orderVolume = $cBalances[$cle]['onOrders'];
					$volumeTotal = $orderVolume + $monVolume;	
					echo "<tr>";					
					if($volumeTotal != 0.00000000 &&  $cle != 'USDT') 
					{
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
						}
						else
						{
							$totalVolume = $tVolume['USDT_BTC']['baseVolume'];
						}
						$usdtFormatValue = number_format($usdtValue, 2, '.', '');
						
						echo "<td>$cle</td>";
						echo "<td>$volumeTotal</td>";
						echo "<td>$volumeDispo</td>";
						echo "<td>$totalVolume</td>";
						echo "<td>$btcValue BTC / <b>$usdtFormatValue USDT / $prixUsd $</b></td>";
										
					}
					if($cle == 'USDT' && $cBalances[$cle]['available'] >= 0.01)
					{
						$volumeDispo = $cBalances[$cle]['available'] + 0;
						$btcValue = number_format($volumeTotal/$prixBtc,8);
						$tDollars = $tDollars + $volumeTotal;
						$tBtc = $tBtc + $btcValue;
						$prixUsd = number_format($volumeTotal*$usd, 2, '.', '');
						$totalUsd = $totalUsd + $prixUsd;
						echo "<td>$cle</td>";
						echo "<td>$volumeTotal</td>";
						echo "<td>$volumeDispo</td>";
						echo "<td>$volumeTotal</td>";
						$volumeTotal = number_format($volumeTotal, 2, '.', '');
						echo "<td>$btcValue BTC / <b>$volumeTotal USDT / $prixUsd $</b></td>";
					}
					echo "</tr>";	
				}
				$tFormatDollars = number_format($tDollars, 2, '.', '');
				$tFormatUSD = number_format($totalUsd, 2, '.', '');
				?>
					<script type="text/javascript">
	document.getElementById("totalCompte").innerHTML = "<p><b><?php echo $tBtc;?> BTC / <?php echo $tFormatDollars;?> USDT / <?php echo $tFormatUSD;?> $</b><p>";
	</script>
	
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.html"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>

<!-- Mirrored from v4-alpha.getbootstrap.com/examples/dashboard/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2017 15:31:05 GMT -->
</html>
