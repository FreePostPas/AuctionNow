<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Account
*/

class Account extends CI_Controller
{
	public function index()
	{
		
	}

	public function connect()
	{

	}

	public function choose_character()
	{
		$this->load->view('account_connect');
	}
}


/* End of file account.php */
/* Location: ./application/controllers/account.php */