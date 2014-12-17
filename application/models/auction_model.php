<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Auction_model
	* Modele de recuperation des encheres depuis le serveur de jeu
	* Couche d'abstraction via SOAP (et plus particulierement nuSOAP)
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

		//Waiting for abstraction. SOAP?
		$data = array(
					//Format: Auction_guid, item_id, quantity,last_bid_amount, buy_now_amount, seller_name, remaining_time (seconds)

					array('10', '4543', '1', $this->wow->money_to_po('300'), $this->wow->money_to_po('60000'), 'Liuny', '2000'),
					array('9', '3330', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('600'), 'Liuny', '2000'),
					array('8', '4542', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('60000'), 'Liuny', '2000'),
					array('7', '4543', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('60'), 'Gota', '2000'),
					array('6', '4548', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('602340'), 'Electrizia', '2000'),
					array('5', '1383', '1', $this->wow->money_to_po('7430000'), $this->wow->money_to_po('400333300'), 'Ures', '2000'),
					array('4', '5341', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('60000'), 'Gabana', '2000'),
					array('3', '4234', '20', $this->wow->money_to_po('30000'), $this->wow->money_to_po('60000'), 'SecretMan', '2000'),
					array('2', '3434', '1', $this->wow->money_to_po('3232400'), $this->wow->money_to_po('60000'), 'AnonymousOuhOuh', '2000'),
					array('1', '2234', '1', $this->wow->money_to_po('30000'), $this->wow->money_to_po('60000'), 'LoremIpsum', '2000')
			);
		return $data;
	}

	public function get_auction($guid)
	{
		//Format: Auction_guid, item_id, quantity,last_bid_amount, buy_now_amount, seller_name, remaining_time (seconds)
		$data = array('3', '4234', '20', '30000', '60000', 'SecretMan', '2000'); //Waiting for abstraction. SOAP?

		return $data;
	}

	public function buy($auction_guid, $character_guid)
	{
		//Waiting for abstraction. SOAP?
		return TRUE;
	}

	public function bid($auction_guid, $character_guid, $bid_amount)
	{
		//Waiting for abstraction. SOAP?
		return TRUE;
	}

	public function sell($character_guid, $item_id, $quantity, $buy_now_amount, $initial_bid_amount, $remaining_time)
	{
		//Waiting for abstraction. SOAP?
		
		/*
			* Must check if item is into character's bag
			* Then add auction if he has enought money to add auction (via SOAP?)
		*/
		return TRUE;
	}

}

/* End of file auction_models.php */
/* Location: ./application/models/auction_model.php */