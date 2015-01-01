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
			$this->load->model('account_model');
			$data['characters'] = $this->account_model->get_characters_by_account_guid($this->session->userdata('account_guid'));

			$this->load->view('account_overview', $data);
		}

	}

	public function connect()
	{
		if($this->session->userdata('connected'))
			redirect('account/index', 'location');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('account_model');

		 //Validation rules
		$this->form_validation->set_rules('username', 'Nom d\'utilisateur', 'required');
		$this->form_validation->set_rules('password', 'Mot de passe', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('account_connect');
			return;
		}
		else
		{			
			if($this->account_model->get_hashed_password_by_username($this->input->post('username')) != strtoupper(sha1(strtoupper($this->input->post('username')).':'.strtoupper($this->input->post('password'))))) //Note password hash: UPPER(SHA1(CONCAT(UPPER(`username`), ':', UPPER(<pass>))));
			{
				$this->session->set_flashdata('flash', 'Mauvais couple pseudo/mot de passe.');
				$this->load->view('account_connect');
				return; //If password don't match, function stop properly here
			}

			$this->account_model->set_account_user_data_by_username($this->input->post('username'));
			$this->session->set_userdata('connected', TRUE);
			redirect('account/index', 'location');
		}

	}

	public function choose_character($character_guid)
	{
		if(!$this->session->userdata('connected'))
		{
			$this->session->set_flashdata('flash', 'Vous devez être connecté pour accéder à cette page.');
			redirect('account/connect', 'location');
		}

		if(isset($character_guid))
		{
			$this->load->model('account_model');
			if($this->account_model->is_character_of_account($this->session->userdata('account_guid'), $character_guid))
			{
				$this->session->set_userdata('character', $this->account_model->get_character_by_guid($character_guid));
				$this->session->set_userdata('character_guid', $character_guid);
				$this->session->set_flashdata('flash', 'Personnage sélectionné avec succès.');
			}
			else $this->session->set_flashdata('flash', 'Erreur.');

			redirect('auction', 'location');

		}

	}
}


/* End of file account.php */
/* Location: ./application/controllers/account.php */