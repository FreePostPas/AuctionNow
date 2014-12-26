<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Auction_model
	* Modele de recuperation des encheres depuis le serveur de jeu
	* Couche d'abstraction de SOAP (et plus particulierement nuSOAP)
	* Le systeme devrait normalement fonctionner en executant des commandes customs sur le serveur accessibles 
	* des le niveau "joueur" (ou presque) pour assurer un maximum de securite. 
	* Ces commandes pourraient etre des equivalents du GUI de l'auction house avec un paramètre 
	* supplémentaire : le GUID du personnage. La commande fait les vérifications
	* necessaires, si l'ajout est possible, alors on ajoute l'enchere.
*/

class Auction_model extends CI_model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_last_ten_auctions()
	{
		$this->load->library('Wow'); //To convert standard money to PO/PA/PC and get a name before the wowhead toolkit
		$this->load->database('character');

		$query = $this->db->where('username', strtoupper($username))->get('auctionhouse');

		if($query->num_rows() > 0)
		{
			$data = array(); //Array of array of unique auction
			foreach($query->result_array() as $auction)
			{
				//Format: Auction_guid, item_id, quantity,last_bid_amount, buy_now_amount, seller_name, remaining_time (seconds)
				$row = array(
					'auction_guid'  => $auction['id'],
					'item_id'  => $auction['itemguid'],
					'quantity' => $auction['level'],
					'last_bid_amount'  => $auction['lastbid'],
					'buy_now_amount' => $auction['buyoutprice'],
					'seller_name' => $auction['itemowner'],
					'remaining_time' => time() - $auction['time'] //seconds before auction end
					);
				array_push($data, $row);
			}
			if(empty($data)) //No need but it is here
				return NULL;
			else
				return $data;
		}
		else
			return NULL;
	}

	public function get_auction($guid)
	{
		$this->load->database('character');

		$query = $this->db->where('username', strtoupper($username))->get('auctionhouse');

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return array(

				);
		}
		else
			return NULL;
	}

	public function buy($auction_guid, $character_guid)
	{
		$this->load->library('Soap');
		$return = $this->soap->cmd('ah_cli buyout '.$auction_guid.' '.$character_guid);
		if($return == "Sucess")
			return true;
		else
			return $return;
	}

	public function bid($auction_guid, $character_guid, $bid_amount)
	{
		$this->load->library('Soap');
		$return = $this->soap->cmd('ah_cli bid '.$auction_guid.' '.$character_guid.' '.$bid_amount);

		if($return == "Sucess")
			return true;
		else
			return $return;
	}
}

/* End of file auction_models.php */
/* Location: ./application/models/auction_model.php */