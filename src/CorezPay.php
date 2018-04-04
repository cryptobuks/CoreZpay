<?php
/**
 * CoreZ PHP Payment Class (RPC Version)
 * 
 * See included examples in /examples for usages instructions and examples.
 * 
 * @author Bavamont, www.bavamont.com
 * @link https://github.com/bavamont
 * 
 */

namespace CorezPay;

use PHPRpcWalletWrapper\Wallet as Wallet;

class CorezPay
{
	/**
	 * RPC Client
	 * @var string
	 */
    private $coreRpcClient;

	/**
	 * Payout wallet address
	 * Sets payout wallet address.
	 * @var string
	 */
	protected $payoutWalletAddress = 'ZrrEJze7MCA1dQmr8KRfFdugUhxQ28Vrtu';	

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
	public function __construct($rpcProtocol = 'http://', $rpcUser, $rpcPassword, $rpcHost, $rpcPort)
    {
		$this->coreRpcClient = new Wallet($rpcProtocol, $rpcUser, $rpcPassword, $rpcHost, $rpcPort);
    	$this->invoiceID = time();
    	$this->invoiceWalletAccount = $this->invoiceID . ' ' . $this->invoiceDelimiter . ' ' . number_format($this->invoiceAmount, 8, '.', '');

		/**
		 * Check if core server is running
		 */
		$response = $this->coreRpcClient->getBalance();
		if ($response['error'])
		{
			throw new Exception($response['error']['message']);
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
		$response = $this->coreRpcClient->getAccountAddress($walletAccount);
		if (!$response['error'])
		{
			$returnResult['success'] = trim($response['result']);
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
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
		$response = $this->coreRpcClient->getAccount($walletAddress);
		if (!$response['error'])
		{
			$returnResult['success'] = trim($response['result']);
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
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
	 * @return array
	 */
	public function getInvoiceAmountByAddress($walletAddress)
	{
		$response = $this->getAccount($walletAddress);
		if ($response['error']['code'] == 0) return ['success' => $this->getInvoiceAmountByAccount($response['success']), 'error' => ['code' => 0, 'message' => '']];
		else return $response;
	}	

	/**
	 * Generate invoice wallet address
	 *
	 * @return array
	 */
	public function generateInvoiceWalletAddress()
	{
		$response = $this->coreRpcClient->getNewAddress($this->invoiceWalletAccount);
		if (!$response['error'])
		{
			$returnResult['success'] = trim($response['result']);
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
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
		$response = $this->coreRpcClient->getBalance($walletAccount);
		if (!$response['error'])
		{
			$returnResult['success'] = number_format(floatval(preg_replace('/\s+/', '', trim($response['result']))), 8, '.', '');
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
        return $returnResult;        
	}

	/**
	 * Get wallet balance by address
	 *
	 * @param string $walletAddress Wallet address
	 * @return array
	 */
	public function getWalletBalanceByAddress($walletAddress = '')
	{
		$response = $this->getAccount($walletAddress);
		if ($response['error']['code'] == 0) return ['success' => $this->getWalletBalanceByAccount($response['success']), 'error' => ['code' => 0, 'message' => '']];
		else return $response;
	}	

	/**
	 * Get wallet balance
	 *
	 * @return float Wallet balance
	 */
	public function getWalletBalance()
	{
		$response = $this->coreRpcClient->getBalance();
		if (!$response['error'])
		{
			$returnResult['success'] = number_format(floatval(preg_replace('/\s+/', '', $response['result'])), 8, '.', '');
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
        return $returnResult;
	}	

	/**
	 * Payout total balance to payout address
	 *
	 * @return array Payout result
	 */
	public function payoutTotalBalance()
	{
		$response = $this->getWalletBalance();
		if ($response['error']['code'] == 0)
		{
			$response2 = $this->coreRpcClient->sendToAddress($this->payoutWalletAddress, $response['success'], '', '', true);
			if ($response2['error'])
			{
				$returnResult['success'] = $response2['result'];
				$returnResult['error']['code'] = 0;
				$returnResult['error']['message'] = '';
			} else {
				$returnResult['success'] = '';
				$returnResult['error']['code'] = $response2['error']['code'];
				$returnResult['error']['message'] = $response2['error']['message'];
			}
	        return $returnResult;
	    } else $response;
	}

	/**
	 * Payout amount to payout address
	 *
	 * @param float $payoutAmount Balance amount to payout
	 * @return array Payout result
	 */
	public function payoutAmount($payoutAmount)
	{
		$response = $this->coreRpcClient->sendToAddress($this->payoutWalletAddress, number_format($payoutAmount, 8, '.', ''), '', '', true);
		if (!$response['error'])
		{
			$returnResult['success'] = $response['result'];
			$returnResult['error']['code'] = 0;
			$returnResult['error']['message'] = '';
		} else {
			$returnResult['success'] = '';
			$returnResult['error']['code'] = $response['error']['code'];
			$returnResult['error']['message'] = $response['error']['message'];
		}
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
		$response = $this->generateInvoiceWalletAddress();
		if ($response['error']['code'] == 0)
		{		
			$paymentDetails = [
								'invoiceID'				=> $this->invoiceID,
								'invoiceAmount'			=> $this->invoiceAmount,
								'invoiceWalletAccount'	=> $this->invoiceWalletAccount,
								'invoiceWalletAddress'	=> $response['success']
								];
			return ['success' => $paymentDetails, 'error' => ['code' => 0, 'message' => '']];
		} else return $response;
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
		$response = $this->getWalletBalanceByAccount($invoiceWalletAccount); // walletBalance
		if ($response['error']['code'] == 0)
		{				
			if (($invoiceAmount <= 0) || (floatval($response['success']) <= 0)) return ['success' => false, 'error' => ['code' => 99, 'message' => 'Invalid amounts.']];
			else if (abs(floatval($invoiceAmount)-floatval($response['success'])) < floatval(0.00000001)) return ['success' => true, 'error' => ['code' => 0, 'message' => '']];
			else return ['success' => false, 'error' => ['code' => 98, 'message' => 'Amounts error.']];
		} else return ['success' => false, 'error' => ['code' => $response['error']['code'], 'message' => $response['error']['message']]];
	}

	/**
	 * Check if invoice is paid by wallet address
	 *
	 * @param string $invoiceWalletAddress Invoice wallet address
	 * @return bool
	 */
	public function isPaidByAddress($invoiceWalletAddress)
	{
		$response = $this->getAccount($invoiceWalletAddress);
		if ($response['error']['code'] == 0) return $this->isPaidByAccount($response['success']);
		else return ['success' => false, 'error' => ['code' => $response['error']['code'], 'message' => $response['error']['message']]];
	}

}