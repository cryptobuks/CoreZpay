<?php
/*
  				COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>
Copyright 2018 Bavamont, www.bavamont.com

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author Original author: sergio <jsonrpcphp@inservibile.org>
 * @author Modified CURL version: Bavamont, www.bavamont.com
 */

namespace PHPRpcWalletWrapper;

class jsonRPCClient {
		
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;
	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url) 
	{
		$this->url = $url;
		empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
		$this->id = 1;
	}
	
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) 
	{
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}
	
	/**
	 * Performs a jsonRCP request with CURL and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	public function __call($method,$params) 
	{
		
		if (!is_scalar($method)) 
		{
			throw new Exception('Method name has no scalar value');
		}
		
		if (is_array($params)) 
		{
			$params = array_values($params);
		} else {
			throw new Exception('Params must be given as array');
		}
		
		if ($this->notification) 
		{
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}
		
		$request = array(
						'method' => $method,
						'params' => $params,
						'id' => $currentId
						);

		$request = json_encode($request);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($ch),true);

		if (!$this->notification) 
		{
			if ($response['id'] != $currentId) 
			{
				throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
			}			
			return $response;
			
		} else {
			return true;
		}
	}
}
?>