<?php 
/**
 * IMPORTANT
 * 
 * See README.md for details.
 *
 * This is a usage example only! it does not cover every possible use-case, 
 * nor should it be used as is in a production environment.
 */

  include('../src/jsonRPCClient.php');
  include('../src/Wallet.php');
  include('../src/CorezPay.php');

  /**
   *  Add the RPC settings from your wallet server (.conf file).
   */
  $rpcProtocol  = 'http://';
  $rpcUser      = '';
  $rpcPassword  = '';
  $rpcHost      = '';
  $rpcPort      = '';

  try {
    $CoreZ = new CorezPay\CorezPay($rpcProtocol, $rpcUser, $rpcPassword, $rpcHost, $rpcPort);
  } catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
  }

  /**
   * Get wallet balance
   */
  $walletBalance = 0;
  $walletBalanceResult = $CoreZ->getWalletBalance();
  if ($walletBalanceResult['error']['code'] == 0) $walletBalance = $walletBalanceResult['success'];
  else $walletBalance = 'Error: ' . $paymentInformation['error']['message'];

  /**
   * Payout total balance to payout address
   */
  if (($walletBalanceResult['error']['code'] == 0) && ($walletBalance > 0)) $CoreZ->payoutTotalBalance();

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CorezPay example with Bootstrap</title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<!-- FontAwesome CSS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css" integrity="sha384-v2Tw72dyUXeU3y4aM2Y0tBJQkGfplr39mxZqlTBDUZAb9BGoC40+rdFCG0m10lXk" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">

  </head>

  <body class="bg-light">

    <div class="container">
      <div class="text-center pt-5">
        <h2>CorezPay Example</h2>
        <p class="lead pt-3">
        	You have a total of <strong><?php echo $walletBalance; ?> CRZ</strong> in your wallet.
        	<br>
        	They will now be paid out to your payout address.
        </p>
      </div>

      <footer class="pt-3 text-muted text-center text-small">
        <p class="mb-1">Copyright &copy; Your Company</p>
      </footer>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>