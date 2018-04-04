<?php
/**
 * CoreZ PHP Payment Class (Non-RPC Version)
 * 
 * See included examples in /examples for usages instructions and examples.
 * 
 * @author Bavamont, www.bavamont.com
 * @link https://github.com/bavamont
 * 
 */

namespace CorezPay;

class CorezPay
{

	/**
	 * Payout wallet address
	 * Sets payout wallet address.
	 * @var string
	 */
	protected $payoutWalletAddress = 'ZrrEJze7MCA1dQmr8KRfFdugUhxQ28Vrtu';	

	/**
	 * Shell execution command
	 * Define how the exec() command is run.
	 * @var string
	 */
	protected $execCommand = "echo '' | sudo -S ";

	/**
	 * Invoice id
	 * Sets default invoice id
	 * @var string
	 */
	protected $invoiceID = '';

	/**
	 * Invoice amount
	 * Sets default invoice amount.
	 * @var float
	 */
	protected $invoiceAmount = 1;

	/**
	 * Invoice delimiter
	 * Sets a default delimiter between invoice id and invoice amount.
	 * @var string
	 */
	protected $invoiceDelimiter = '|';

	/**
	 * Invoice wallet account
	 * Sets the default invoice wallet account.
	 * @var string
	 */
	protected $invoiceWalletAccount = '';

	/**
	 * Create default invoice wallet account and set a default invoice id.
	 */
	public function __construct()
    {
    	$this->invoiceID = time();
    	$this->invoiceWalletAccount = $this->invoiceID . ' ' . $this->invoiceDelimiter . ' ' . number_format($this->invoiceAmount, 8, '.', '');

		/**
		 * Check if core server is running
		 */
        exec($this->execCommand . ' corez-cli getbalance', $walletBalance, $return);
        if ($return < 0)
        {
            /**
             * Start server
             */         
            exec($this->execCommand . ' corezd -daemon', $startCore);
        }
    }

	/**
	 * Get Account Address
	 *
	 * @param string $walletAccount Wallet account	
	 * @return array
	 */
	public function getAccountAddress($walletAccount)
	{
		exec($this->execCommand . 'corez-cli getaccountaddress "' . $walletAccount . '"', $walletAddress, $return);
        if ($return == 0) $returnResult['success'] = trim($walletAddress[0]);
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
	}	

	/**
	 * Get Account
	 *
	 * @param string $walletAddress Wallet address
	 * @return array
	 */
	public function getAccount($walletAddress)
	{
		exec($this->execCommand . 'corez-cli getaccount "' . $walletAddress. '"', $walletAccount, $return);
        if ($return == 0) $returnResult['success'] = trim($walletAccount[0]);
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
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
		$result = $this->getAccount($walletAddress);
		if ($result['error'] == 0) return ['success' => $this->getInvoiceAmountByAccount($result['success']), 'error' => 0];
		else return ['success' => '', 'error' => $result['error']];
	}	

	/**
	 * Generate invoice wallet address
	 *
	 * @return array
	 */
	public function generateInvoiceWalletAddress()
	{
		exec($this->execCommand . 'corez-cli getnewaddress "' . $this->invoiceWalletAccount . '"', $generatedWalletAddress, $return);
        if ($return == 0) $returnResult['success'] = trim($generatedWalletAddress[0]);
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
	}

	/**
	 * Get wallet balance by account
	 *
	 * @param string $walletAccount Wallet account 
	 * @return array
	 */
	public function getWalletBalanceByAccount($walletAccount = '')
	{
		exec($this->execCommand . 'corez-cli getbalance "' . $walletAccount . '"', $walletAccountBalance, $return);
        if ($return == 0) $returnResult['success'] = number_format(floatval(preg_replace('/\s+/', '', $walletAccountBalance[0])), 8, '.', '');
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
	}

	/**
	 * Get wallet balance by address
	 *
	 * @param string $walletAddress Wallet address
	 * @return float Wallet address balance
	 */
	public function getWalletBalanceByAddress($walletAddress = '')
	{
		$result = $this->getAccount($walletAddress);
		if ($result['error'] == 0) return ['success' => $this->getWalletBalanceByAccount($result['success']), 'error' => 0];
		else return ['success' => '', 'error' => $result['error']];
	}	

	/**
	 * Get wallet balance
	 *
	 * @return float Wallet balance
	 */
	public function getWalletBalance()
	{
		exec($this->execCommand . 'corez-cli getbalance', $walletBalance, $return);
        if ($return == 0) $returnResult['success'] = number_format(floatval(preg_replace('/\s+/', '', $walletBalance[0])), 8, '.', '');
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
	}	

	/**
	 * Payout total balance to payout address
	 *
	 * @return array Payout result
	 */
	public function payoutTotalBalance()
	{
		$result = $this->getWalletBalance();
		if ($result['error'] == 0)
		{
			exec($this->execCommand . 'corez-cli sendtoaddress "' . $this->payoutWalletAddress . '" ' . $result['success'] . ' "" "" true', $payoutResult, $return);
	        if ($return == 0) $returnResult['success'] = $payoutResult;
	        else $returnResult['success'] = '';
	        $returnResult['error'] = $return;
	        return $returnResult;
	    } else return ['success' => '', 'error' => $result['error']];
	}

	/**
	 * Payout amount to payout address
	 *
	 * @param float $payoutAmount Balance amount to payout
	 * @return array Payout result
	 */
	public function payoutAmount($payoutAmount)
	{
		exec($this->execCommand . 'corez-cli sendtoaddress "' . $this->payoutWalletAddress . '" ' . number_format($payoutAmount, 8, '.', '') . ' "" "" true', $payoutResult, $return);
        if ($return == 0) $returnResult['success'] = $payoutResult;
        else $returnResult['success'] = '';
        $returnResult['error'] = $return;
        return $returnResult;
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
		$this->invoiceWalletAccount = $invoiceID . ' ' . $this->invoiceDelimiter . ' ' . $this->invoiceAmount;

		$result = $this->generateInvoiceWalletAddress();
		if ($result['error'] == 0)
		{		
			$paymentDetails = [
								'invoiceID'				=> $this->invoiceID,
								'invoiceAmount'			=> $this->invoiceAmount,
								'invoiceWalletAccount'	=> $this->invoiceWalletAccount,
								'invoiceWalletAddress'	=> $result['success']
								];

			return['success' => $paymentDetails, 'error' => 0];
		} else return ['success' => '', 'error' => $result['error']];
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
		$result = $this->getWalletBalanceByAccount($invoiceWalletAccount); // walletBalance
		if ($result['error'] == 0)
		{				
			if (($invoiceAmount <= 0) || ($result['success'] <= 0)) return ['success' => false, 'error' => 99];
			else if (abs(floatval($invoiceAmount)-floatval($result['success'])) < floatval(0.00000001)) return ['success' => true, 'error' => 0];
			else return ['success' => false, 'error' => 98];
		} else return ['success' => false, 'error' => $result['error']];
	}

	/**
	 * Check if invoice is paid by wallet address
	 *
	 * @param string $invoiceWalletAddress Invoice wallet address
	 * @return bool
	 */
	public function isPaidByAddress($invoiceWalletAddress)
	{
		$result = $this->getAccount($invoiceWalletAddress);
		if ($result['error'] == 0) return $this->isPaidByAccount($result['success']);
		else return ['success' => false, 'error' => $result['error']];
	}

}