<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Account_model
	* Author : Adrien Albaladejo/Freegos/Ures/FreePostPas (only one crazy man)
	* Modele de recuperation des encheres depuis le serveur de jeu
	* Couche d'abstraction de la base de donnees character
	* Seul des requetes SELECT sont effectues. Le seul but est de connecter les joueurs, voir la quantite d argent
	* et selectionner le personnage utiliser
*/

class Account_model extends CI_model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
		* Connection part
	*/

	public function get_hashed_password_by_username($username)
	{
		$this->load->database('auth');

		$query = $this->db->select('sha_pass_hash')->where('username', strtoupper($username))->get('account');

		if($query->num_rows() > 0)
		{
			$result = $query->row();
			echo $result->sha_pass_hash;
			return $result->sha_pass_hash;
		}
		return NULL;
	}

	public function set_account_user_data_by_username($username)
	{
		$this->load->database('auth');
		$query = $this->db->select('id, username')->where('username', strtoupper($username))->get('account');
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$this->session->set_userdata('account_guid', $result->id);
			$this->session->set_userdata('account_username', $result->username);
		}
	}

	/*
		* Get character part
	*/

	public function get_characters_by_account_guid($account_guid)
	{
		$this->load->database('character');
		$query = $this->db->get_where('characters', array('account' => $account_guid));

		if($query->num_rows() > 0)
		{
			$this->config->load('auctionnow', TRUE);

			$data = array();
			foreach($query->result_array() as $character)
			{
				if($character['level'] >= $this->config->item('min_level_to_bid', 'auctionnow'))
				{
					$row = array(
						'guid'  => $character['guid'],
						'name'  => $character['name'],
						'level' => $character['level'],
						'race'  => $character['race'],
						'class' => $character['class'],
						'money' => $character['money']
						);
					array_push($data, $row);
				}
			}
			if(empty($data))
				return 1; // If there is no one character with minimal level to use auction house
			
			return $data;
		}

		return NULL; // If there is no one character with the account
	}

	public function get_character_by_guid($character_guid)
	{
		$this->load->database('character');
		$query = $this->db->get_where('characters', array('guid' => $character_guid));

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$data = array(
					'guid'  => $result->guid,
					'name'  => $result->name,
					'level' => $result->level,
					'race'  => $result->race,
					'class' => $result->class,
					'money' => $result->money
				);
			return $data;
		}

		return NULL;
	}

	public function is_character_of_account($account_guid, $character_guid)
	{
		$this->load->database('character');
		$query = $this->db->where(array('account' => $account_guid, 'guid' => $character_guid))->get('characters');
		if($query->num_rows() != 0)
			return TRUE;
		return FALSE;
	}

	
}

/* End of file account_models.php */
/* Location: ./application/models/account_model.php */