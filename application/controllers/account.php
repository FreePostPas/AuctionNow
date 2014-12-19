<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Account
	* Author : Adrien Albaladejo/Freegos/Ures/FreePostPas (only one crazy man)
*/

class Account extends CI_Controller
{
	public function index()
	{
		if(!$this->session->userdata('connected'))
			redirect('account/connect', 'location');
		else
		{
			$this->load->model('account');
			$data['characters'] = $this->account_model->get_characters_by_account_guid($account_guid);
			$this->load->view('account_overview', $data);
		}
	}

	public function connect()
	{
		$this->load->view('account_connect');
	}

	public function choose_character($character_guid)
	{
		if(!$this->session->userdata('connected'))
		{
			$this->session->set_flashdata('flash', 'Vous devez être connecté pour accéder à cette page.');
			redirect('account/connect', 'location');
		}

		if(is_integer($character_guid))
		{
			$this->load->model('account');
			$this->session->set_userdata('character', $this->account_model->get_character_by_guid($character_guid));
		}

	}
}


/* End of file account.php */
/* Location: ./application/controllers/account.php */