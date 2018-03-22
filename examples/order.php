<?php 
/**
 * IMPORTANT
 * 
 * In order to test this code you need to install coreZ. See README.md for details.
 *
 * This is a usage example only! it does not cover every possible use-case, 
 * nor should it be used as is in a production environment.
 */

	include_once('../src/CorezPay.php');

	$coreZ = new Bavamont\CorezPay\CorezPay;

	$invoiceWalletAddress = preg_replace('/[^0-9a-zA-Z_]/',"",$_GET['invoiceWalletAddress']);
	if (empty($invoiceWalletAddress))
	{

		$invoiceID = 'Example invoice #' . time();                            // Invoice/Order nr.
		$invoiceAmount = number_format(floatval($_GET['amount']),8,".","");   // Invoice/Order amount in CRZ
    if ($invoiceAmount <= 0) $invoiceAmount = 0.01;

		/**
		 * generatePayment() returns an array with these variables:
		 * invoiceID
		 * invoiceAmount
		 * invoiceWalletAccount
		 * invoiceWalletAddress
		 */	
		$paymentInformation = $coreZ->generatePayment($invoiceID, $invoiceAmount);
		$invoiceWalletAddress = $paymentInformation['invoiceWalletAddress'];
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
        <p class="lead pt-3">Thank you for your order.<br><br>Please send <strong><?php echo $invoiceAmount; ?> CRZ</strong> to the following address:</p>
        <h5><?php echo $invoiceWalletAddress; ?></h5>
        <div id="paymentStatus" name="paymentStatus">
	        <div class="alert alert-warning my-4" role="alert">
  	  			Awaiting payment...
  	  			<br>
  	  			<i class="fas fa-spinner fa-spin my-3" style="font-size:32px;"></i>
  	  			<br>
  	  			It can take a couple of minutes for the transaction to be confirmed.
            <br>
            All amounts sent will be used for future development.
    			</div>
    		</div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <h4 class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Your Order</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Donation</h6>
                <small class="text-muted">Thank you for your support.</small>
              </div>
              <span class="text-muted"><?php echo $invoiceAmount; ?> CRZ</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Total</span>
              <strong><?php echo $invoiceAmount; ?> CRZ</strong>
            </li>
          </ul>
        </div>
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
    <script>

    	function checkPayment() {
	    	$.get( "order.php", { invoiceWalletAddress: "<?php echo $invoiceWalletAddress; ?>"}, 
		    	function(data) {
		    		if (data == 1)
		    		{
						$("#paymentStatus").html('<div class="alert alert-success my-4" role="alert"><strong>Well done!</strong> You have successfully paid.</div>');
		    		} else {
		    			setTimeout( function() { checkPayment(); }, 5000);
		    		}
		  		}
		  	);
	  	};   

    	$( document ).ready(function() {
    		checkPayment();
    	});

    </script>
    
  </body>
</html>

<?php

} else {

	/**
	 * isPaidByAddress() returns a bool. 
	 * true if order is paid, false if not.
	 */	
	echo $coreZ->isPaidByAddress($invoiceWalletAddress);

}

?>