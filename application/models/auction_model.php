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

	public function get_auction_search($limit = 20, $offset = 0, $searched_name = NULL, $level_min = NULL, $level_max = NULL, $usable = NULL, $inventory_type = NULL, $item_class = NULL, $item_sub_class = NULL, $quality = NULL)
	{
		$this->load->database('character');
		$this->db->join('characters.item_instance ii', 'ii.guid = ah.itemGuid');
		$this->db->join('world.item_template it', 'ii.itemEntry = it.entry');

		//Search filters
		if($level_min != NULL)
			$this->db->where('it.RequiredLevel >', $level_min);
		if($level_max != NULL)
			$this->db->where('it.RequiredLevel <', $level_max);
		if($usable != NULL)
		{
			//Play with bitmask here
			$this->db->where('it.RequiredLevel <', $level_max);
		}
		if($inventory_type != NULL)
			$this->db->where('it.InventoryType', $inventory_type);
		if($item_class != NULL)
			$this->db->where('it.class', $item_class);
		if($item_sub_class != NULL)
			$this->db->where('it.subclass', $item_sub_class);
		if($quality != NULL)
			$this->db->where('it.Quality', $quality);


		$this->db->limit($limit);
		$this->db->offset($offset);
		$query = $this->db->get('characters.auctionhouse ah');

		if($query->num_rows() > 0)
		{
			$data = array();
			foreach($query->result() as $item)
			{
				//Format: Auction_guid, item_id, quantity,last_bid_amount, buy_now_amount, seller_name, remaining_time (seconds)
				$row = $this->hydrate_auction_data($item);
				array_push($data, $row);
			}
			return $data;
		}
		return NULL;
	}

	public function get_auction($guid)
	{
		$this->load->database('character');

		$query = $this->db->where('id', $guid)->get('auctionhouse');

		if($query->num_rows() > 0)
		{
			return $this->hydrate_auction_data($query->row());
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

	public function can_be_buy_instant($guid)
	{
		$this->load->database('character');
		$query = $this->db->select('buyoutprice')->where('id', $guid)->get('auctionhouse');
	
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			if($result->buyoutprice == 0)
				return false;
			return true;
		}
	}

	private function hydrate_auction_data($auction)
	{
		/*
			* Name : hydrate_auction_data($auction)
			* Return array containing a row of formated data from raw data of database
		*/
		$this->load->library("Wow");

		$data = array(
				'auction_guid'  => $auction->id,
				'item_id'  => $this->wow->get_item_instance_by_guid($auction->itemguid, 'entry'),
				'quantity' => $this->wow->get_item_instance_by_guid($auction->itemguid, 'quantity'),
				'last_bid_amount'  => $auction->lastbid,
				'level'  => $auction->RequiredLevel,
				'buy_now_amount' => $auction->buyoutprice,
				'seller_name' => $this->wow->get_character_name_by_guid($auction->itemowner),
				'remaining_time' => time() - $auction->time //seconds before auction end
			);

		return $data;
	}
}

/* End of file auction_models.php */
/* Location: ./application/models/auction_model.php */