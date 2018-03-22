<?php
/**
 * coreZ PHP Payment Class
 * 
 * See included examples in /examples for usages instructions and examples.
 * 
 * @copyright Bavamont
 * @link http://labs.bavamont.com
 * 
 */

namespace Bavamont\CorezPay;

class CorezPay
{

	/**
	 * Payout wallet address
	 * Sets payout wallet address.
	 * @var string
	 */
	private $payoutWalletAddress = "ZrrEJze7MCA1dQmr8KRfFdugUhxQ28Vrtu";	

	/**
	 * Shell execution command
	 * Define how the exec() command is run.
	 * @var string
	 */
	private $execCommand = "echo '' | sudo -S ";

	/**
	 * Invoice id
	 * Sets default invoice id
	 * @var string
	 */
	private $invoiceID = "";

	/**
	 * Invoice amount
	 * Sets default invoice amount.
	 * @var float
	 */
	private $invoiceAmount = 1;

	/**
	 * Invoice delimiter
	 * Sets a default delimiter between invoice id and invoice amount.
	 * @var string
	 */
	private $invoiceDelimiter = "|";

	/**
	 * Invoice wallet account
	 * Sets the default invoice wallet account.
	 * @var string
	 */
	private $invoiceWalletAccount = "";

	/**
	 * Create default invoice wallet account and set a default invoice id.
	 */
	public function __construct()
    {
    	$this->invoiceID = time();
    	$this->invoiceWalletAccount = $this->invoiceID . " " . $this->invoiceDelimiter . " " . number_format($this->invoiceAmount, 8, '.', '');

		/**
		 * Check if core server is running
		 */
    	exec($this->execCommand . "corez-cli getbalance", $walletBalance);
    	if ( (strcmp(trim($walletBalance[0]), "error: couldn't connect to server") == 0) || empty(trim($walletBalance[0])) )
    	{
			/**
			 * Start core server
			 */    		
			exec($this->execCommand . "corezd -daemon", $startCore);
    	}
    }

	/**
	 * Get Account Address
	 *
	 * @param string $walletAccount Wallet account	
	 * @return string Wallet account address
	 */
	public function getAccountAddress($walletAccount)
	{
		exec($this->execCommand . "corez-cli getaccountaddress \"" . $walletAccount . "\"", $walletAddress);
		return trim($walletAddress[0]);
	}	

	/**
	 * Get Account
	 *
	 * @param string $walletAddress Wallet address
	 * @return string Wallet account 
	 */
	public function getAccount($walletAddress)
	{
		exec($this->execCommand . "corez-cli getaccount \"" . $walletAddress. "\"", $walletAccount);
		return trim($walletAccount[0]);
	}

	/**
	 * Get invoice id
	 *
	 * @return string Invoice id
	 */
	public function getInvoiceID()
	{
		return $this->invoiceID;
	}

	/**
	 * Get invoice smount by sccount
	 *
	 * @param string $walletAccount Wallet account		 
	 * @return float Invoice amount
	 */
	public function getInvoiceAmountByAccount($walletAccount)
	{
		return number_format(floatval(end(explode($this->invoiceDelimiter, $walletAccount))), 8, '.', '');
	}

	/**
	 * Get invoice amount by address
	 *
	 * @param string $walletAddress Wallet address	 
	 * @return float Invoice amount
	 */
	public function getInvoiceAmountByAddress($walletAddress)
	{
		$walletAccount = $this->getAccount($walletAddress);
		return $this->getInvoiceAmountByAccount($walletAccount);
	}	

	/**
	 * Generate invoice wallet address
	 *
	 * @return string Generated address for current payment
	 */
	public function generateInvoiceWalletAddress()
	{
		exec($this->execCommand . "corez-cli getnewaddress \"" . $this->invoiceWalletAccount . "\"", $generatedWalletAddress);
		return trim($generatedWalletAddress[0]);
	}

	/**
	 * Get wallet balance by account
	 *
	 * @param string $walletAccount Wallet account 
	 * @return float Wallet address balance
	 */
	public function getWalletBalanceByAccount($walletAccount = "")
	{
		exec($this->execCommand . "corez-cli getbalance \"" . $walletAccount . "\"", $walletAccountBalance);
		return number_format(floatval(preg_replace('/\s+/', '', $walletAccountBalance[0])), 8, '.', '');
	}

	/**
	 * Get wallet balance by address
	 *
	 * @param string $walletAddress Wallet address
	 * @return float Wallet address balance
	 */
	public function getWalletBalanceByAddress($walletAddress = "")
	{
		$walletAccount = $this->getAccount($walletAddress);
		return $this->getWalletBalanceByAccount($walletAccount);
	}	

	/**
	 * Get wallet balance
	 *
	 * @return float Wallet balance
	 */
	public function getWalletBalance()
	{
		exec($this->execCommand . "corez-cli getbalance", $walletBalance);
		return number_format(floatval(preg_replace('/\s+/', '', $walletBalance[0])), 8, '.', '');
	}	

	/**
	 * Payout total balance to payout address
	 *
	 * @return array Payout result
	 */
	public function payoutTotalBalance()
	{
		$walletBalance = $this->getWalletBalance();
		exec($this->execCommand . "corez-cli sendtoaddress \"" . $this->payoutWalletAddress . "\" " . $walletBalance . " \"\" \"\" true", $payoutResult);
		return $payoutResult;
	}

	/**
	 * Payout amount to payout address
	 *
	 * @param float $payoutAmount Balance amount to payout
	 * @return array Payout result
	 */
	public function payoutAmount($payoutAmount)
	{
		exec($this->execCommand . "corez-cli sendtoaddress \"" . $this->payoutWalletAddress . "\" " . number_format($payoutAmount, 8, '.', '') . " \"\" \"\" true", $payoutResult);
		return $payoutResult;
	}

	/**
	 * Generates a wallet account for the payment
	 *
	 * @param string $invoiceID Invoice id
	 * @param float $invoiceAmount Invoice amount
	 * @return array
	 */
	public function generatePayment($invoiceID, $invoiceAmount)
	{
		
		$this->invoiceID = trim($invoiceID);
		$this->invoiceAmount = number_format(floatval($invoiceAmount), 8, '.', '');
		$this->invoiceWalletAccount = $invoiceID . " " . $this->invoiceDelimiter . " " . $this->invoiceAmount;
		$invoiceWalletAddress = $this->generateInvoiceWalletAddress();
		
		$paymentDetails = [
							'invoiceID'				=> $this->invoiceID,
							'invoiceAmount'			=> $this->invoiceAmount,
							'invoiceWalletAccount'	=> $this->invoiceWalletAccount,
							'invoiceWalletAddress'	=> $invoiceWalletAddress
							];

		return $paymentDetails;
	}

	/**
	 * Check if invoice is paid by wallet account
	 *
	 * @param string $invoiceWalletAccount Invoice wallet account
	 * @return bool
	 */
	public function isPaidByAccount($invoiceWalletAccount)
	{
		$invoiceAmount = $this->getInvoiceAmountByAccount($invoiceWalletAccount);
		$walletBalance = $this->getWalletBalanceByAccount($invoiceWalletAccount);
		if (($invoiceAmount <= 0) || ($walletBalance <= 0)) return false;
		else if (abs(floatval($invoiceAmount)-floatval($walletBalance)) < floatval(0.00000001)) return true;
		else return false;
	}

	/**
	 * Check if invoice is paid by wallet address
	 *
	 * @param string $invoiceWalletAddress Invoice wallet address
	 * @return bool
	 */
	public function isPaidByAddress($invoiceWalletAddress)
	{
		$invoiceWalletAccount = $this->getAccount($invoiceWalletAddress);
		return $this->isPaidByAccount($invoiceWalletAccount);
	}

}