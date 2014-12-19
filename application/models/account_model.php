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

	public function get_characters_by_account_guid($account_guid)
	{
		$this->load->database('character');
		$query = $this->db->get_where('character', 'account', $account_guid);

		if($query->num_rows() > 0)
		{
			$data = array();
			foreach($query->result_array() as $character)
			{
				if($character->level >= 10)
				{
					$row = array(
						'guid' => $result->guid,
						'name' => $result->name,
						'level' => $result->level,
						'race' => $result->race,
						'class' => $result->class,
						'money' => $result->money
						);
					array_push($data, $row);
				}
			}
			if(empty($data))
				return 1; // If there is no one character with minimal level to use auction house
			
			return $data;
		}

		$data = array(
				array('guid' => '333', 'name' => 'MySuperPseudo', 'level' => '90', 'money' => '2332323'),
				array('guid' => '2300', 'name' => 'MyBestReroll', 'level' => '90', 'money' => '90009000'),
				array('guid' => '7000', 'name' => 'MyBadReroll', 'level' => '8', 'money' => '77')
			);

		return NULL;
	}

	public function get_character_by_guid($character_guid)
	{
		$this->load->database('character');
		$query = $this->db->get_where('characters', 'guid', $character_guid);

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			$data = array(
					'guid' => $result->guid,
					'name' => $result->name,
					'level' => $result->level,
					'race' => $result->race,
					'class' => $result->class,
					'money' => $result->money
				);

			return $data;
		}


		return NULL;
	}

	
}

/* End of file account_models.php */
/* Location: ./application/models/account_model.php */