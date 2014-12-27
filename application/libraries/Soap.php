<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
	* Name: Soap
	* Author : Adrien Albaladejo/Freegos/Ures/FreePostPas (only one crazy man)
	* Soap is a library to communicate via SOAP (a web protocol) to TrinityCore.
*/

class Soap {
	private $_ip;
	private $_port;
	private $_user;
	private $_pass;
	private $_soap;
 
	public function __construct()
	{
		/*
			* Library constructor
			* Use data from config/auctionnow.php to initialize SOAP connection
		*/

		$CI =& get_instance();    
		$CI->config->load('auctionnow', TRUE);

		if(!$this->is_empty($CI->config->item('soap_adress', 'auctionnow')) && !$this->is_empty($CI->config->item('soap_port', 'auctionnow')) && !$this->is_empty($CI->config->item('soap_username', 'auctionnow')) && !$this->is_empty($CI->config->item('soap_password', 'auctionnow')))
		{
			$this->_ip = $CI->config->item('soap_adress', 'auctionnow');
			$this->_port = $CI->config->item('soap_port', 'auctionnow');
			$this->_user = $CI->config->item('soap_username', 'auctionnow');
			$this->_pass = $CI->config->item('soap_password', 'auctionnow');
		}
		else
			die("You have to config SOAP connection in config/auctionnow.php");
 
		$this->connect();
	}
 
	private function connect()
	{
		try {
			$this->_soap = new SoapClient(NULL, array(
				'location' => 'http://'.$this->_ip.':'.$this->_port.'/',
				'uri' => 'urn:TC',
				'style' => SOAP_RPC,
				'login' => $this->_user,
				'password' => $this->_pass,
				'keep_alive' => false
			));
		} catch(Exception $e)
		{
			die("[SOAP] Erreur: ".$e->getMessage());
		}
	}

	public function cmd($cmd)
	{
	/*
		* Name: cmd($cmd)
		* Execute on worldserver $cmd and return the response. Use connection defined in
		* constructor with value from config/auctionnow.php

		* Try/catch is very very weird but it work :/
	*/
		//try
		//{
			$result = $this->_soap->executeCommand(new SoapParam(utf8_encode("$cmd"), "command"));
			return $result; //Return the TrinityCore message
		/*}
		catch (SoapFault $e)
		{
			return $e->getMessage(); // Return the TrinityCore message
		}*/
	}

	static function is_empty($val) {
		if(isset($val) && !empty($val))
			return false;
		return true;
	}
}

/* End of file soap.php */
/* Location: ./application/librairies/soap.php */