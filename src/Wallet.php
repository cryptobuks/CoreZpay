<?php
/**
 * PHP RPC Wallet Wrapper
 * A wallet core RPC client wrapper.
 * 
 * @author Bavamont, www.bavamont.com
 * @link https://github.com/bavamont
 * 
 */

namespace PHPRpcWalletWrapper;

use PHPRpcWalletWrapper\jsonRPCClient;

class Wallet
{
    private $walletServer;
    private $jsonRpc;

    public function __construct($rpcProtocol = 'http://', $rpcUser, $rpcPassword, $rpcHost, $rpcPort)
    {
        $this->walletServer = $rpcProtocol . $rpcUser . ':' . $rpcPassword . '@' . $rpcHost . ':' . $rpcPort;
        $this->jsonRpc = new jsonRPCClient($this->walletServer);
    }

    /**
     * Get Account
     *
     * @param string $address
     * @return array
     */
    public function getAccount($address)
    {
        return $this->jsonRpc->getaccount($address);
    }

    /**
     * Get Account Address
     *
     * @param string $account
     * @return array
     */
    public function getAccountAddress($account)
    {
        return $this->jsonRpc->getaccountaddress($account);
    }

    /**
     * Get Addresses By Account
     *
     * @param string $account
     * @return array
     */
    public function getAddressesByAccount($account)
    {
        return $this->jsonRpc->getaddressesbyaccount($account);
    }

    /**
     * Get Balance
     *
     * @param string $account
     * @param int $minconf
     * @return array
     */
    public function getBalance($account = '', int $minconf = 6)
    {
        if (!empty($account)) return $this->jsonRpc->getbalance($account, $minconf);
        else return $this->jsonRpc->getbalance();
    }

    /**
     * Get New Address
     *
     * @param string $account
     * @return array
     */
    public function getNewAddress($account)
    {
        return $this->jsonRpc->getnewaddress($account);
    }

    /**
     * Get Received By Account
     *
     * @param string $account
     * @param int $minconf
     * @return array
     */
    public function getReceivedByAccount($account, int $minconf = 6)
    {
        return $this->jsonRpc->getreceivedbyaccount($account, $minconf);
    }

    /**
     * Get Received By Address
     *
     * @param string $address
     * @param int $minconf
     * @return array
     */
    public function getReceivedByAddress($address, int $minconf = 6)
    {
        return $this->jsonRpc->getreceivedbyaccount($address, $minconf);
    }

    /**
     * Get Transaction
     *
     * @param string $txid
     * @return array
     */
    public function getTransaction($txid)
    {
        return $this->jsonRpc->gettransaction($txid);
    }

    /**
     * Get Unconfirmed Balance
     *
     * @return array
     */
    public function getUnconfirmedBalance()
    {
        return $this->jsonRpc->getunconfirmedbalance();
    }

    /**
     * Get Wallet Info
     *
     * @return array
     */
    public function getWalletInfo()
    {
        return $this->jsonRpc->getwalletinfo();
    }

    /**
     * Instant Send To Address
     *
     * @param string $address
     * @param float $address
     * @param string $comment
     * @param string $commentTo
     * @param bool $subtractFeeFromAmount
     * @return array
     */
    public function instantSendToAddress($address, float $amount, $comment = '', $commentTo = '', bool $subtractFeeFromAmount = true)
    {
        return $this->jsonRpc->instantsendtoaddress($address, $amount, $comment, $commentTo, $subtractFeeFromAmount);
    }

    /**
     * List Accounts
     *
     * @param int $minconf     
     * @return array
     */
    public function listAccounts(int $minconf = 10)
    {
        return $this->jsonRpc->listAccounts($minconf);
    }

    /**
     * List Received By Account
     *
     * @param int $minconf     
     * @return array
     */
    public function listReceivedByAccount(int $minconf = 10)
    {
        return $this->jsonRpc->listreceivedbyaccount($minconf);
    }

    /**
     * List Received By Address
     *
     * @param int $minconf     
     * @return array
     */
    public function listReceivedByAddress(int $minconf = 10)
    {
        return $this->jsonRpc->listreceivedbyaddress($minconf);
    }

    /**
     * List Transactions
     *
     * @param string $account
     * @param int $count     
     * @param string $from
     * @return array
     */
    public function listTransactions($account, int $count = 10, $from = '')
    {
        return $this->jsonRpc->listtransactions($account, $count, $from);
    }

    /**
     * Move
     *
     * @param string $fromAccount
     * @param string $toAddress     
     * @param float $amount
     * @param int $minconf
     * @param string $comment
     * @return array
     */
    public function move($fromAccount, $toAccount, float $amount, int $minconf = 6, $comment = '')
    {
        return $this->jsonRpc->move($fromAccount, $toAccount, $amount, $minconf, $comment);
    }     

    /**
     * Send From
     *
     * @param string $fromAccount
     * @param string $toAddress     
     * @param float $amount
     * @param int $minconf
     * @param string $comment
     * @param string $commentTo
     * @return array
     */
    public function sendFrom($fromAccount, $toAddress, float $amount, int $minconf = 6, $comment = '', $commentTo = '')
    {
        return $this->jsonRpc->sendfrom($fromAccount, $toAddress, $amount, $minconf, $comment, $commentTo);
    }    

    /**
     * Send Many
     *
     * @param string $fromAccount
     * @param string $addressList
     * @param int $minconf
     * @param string $comment
     * @param bool $subtractFeeFromAmount
     * @param bool $useIs
     * @param bool $usePs     
     * @return array
     */
    public function sendMany($fromAccount, $addressList, int $minconf = 6, $comment = '', bool $subtractFeeFromAmount = true, bool $useIs = false, bool $usePs = false)
    {
        return $this->jsonRpc->sendmany($fromAccount, $addressList, $minconf, $comment, $subtractFeeFromAmount, $useIs, $usePs);
    } 

    /**
     * Send To Address
     *
     * @param string $address
     * @param float $amount
     * @param string $comment
     * @param string $commentTo
     * @param bool $subtractFeeFromAmount
     * @param bool $useIs
     * @param bool $usePs
     * @return array
     */
    public function sendToAddress($address, float $amount, $comment = '', $commentTo = '', bool $subtractFeeFromAmount = true, bool $useIs = false, bool $usePs = false)
    {
        return $this->jsonRpc->sendtoaddress($address, $amount, $comment, $commentTo, $subtractFeeFromAmount, $useIs, $usePs);
    }

    /**
     * Set Account
     *
     * @param string $address
     * @param string $account
     * @return array
     */
    public function setAccount($address, $account)
    {
        return $this->jsonRpc->setaccount($address, $account);
    }      
}