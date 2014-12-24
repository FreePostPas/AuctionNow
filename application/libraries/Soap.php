<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
	* Name: Wow
	* Wow is a library who has been made to contain few functions associates to world of warcraft
	* without any true relation between them
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

        //if(!empty($this->config->item('soap_ip', 'auctionnow')) && !empty($this->config->item('soap_port', 'auctionnow')) && !empty($this->config->item('soap_username', 'auctionnow')) && !empty($this->config->item('soap_password', 'auctionnow')))
        //{
            $this->_ip = "127.0.0.1";//$this->config->item('soap_ip', 'auctionnow');
            $this->_port = "7878";//$this->config->item('soap_port', 'auctionnow');
            $this->_user = "admin";//$this->config->item('soap_username', 'auctionnow');
            $this->_pass = "admin";//$this->config->item('soap_password', 'auctionnow');
        //}
 
        $this->connect();
    }
 
    private function connect()
    {
    	// Try connection (with exception)
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
	*/
		$result = $this->_soap->executeCommand(new SoapParam($cmd, 'command'));
		return $result;
	}
}

/* End of file soap.php */
/* Location: ./application/librairies/soap.php */
