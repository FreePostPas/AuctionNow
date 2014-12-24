<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Name: Auction
	* Author : Adrien Albaladejo/Freegos/Ures/FreePostPas (only one crazy man)
	* Extra: Default controller with method list_auction()
*/

class Auction extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auction_model');
	}

	public function index()
	{
		$this->load->library('Soap');
		echo $this->soap->cmd('auctionhouse_cli buyout 25');
		redirect('auction/list_auction', 'location');
	}

	public function list_auction()
	{
		$data['last_auctions'] = $this->auction_model->get_last_ten_auctions();
		$this->load->view('auction_list_page', $data);		
	}

	public function see($guid)
	{
		$data['auction'] = $this->auction_model->get_auction($guid);
		$this->load->view('auction_info_page', $data);
	}

	public function buy($guid)
	{
		if($this->session->userdata('character_guid') == NULL)
		{
			$this->session->set_flashdata('flash', 'Achat impossible : vous n\'avez pas <a href="'.base_url('account/index').'">selectionné votre personnage</a>.');
			redirect('auction/see/'.$guid, 'location');
		}
		else
		{
			$valid = $this->auction_model->buy($guid, $character_guid); //$guid refers to auction's guid
			if(!$valid)
			{
				$this->session->set_flashdata('flash', 'Achat impossible (avez-vous assez d\'argent ?).');
				redirect('auction/see/'.$guid, 'location');
			}

			$this->session->set_flashdata('flash', 'Achat effectué. Vous devriez recevoir dans votre boite au lettre votre item.');
			redirect('auction/list_auction', 'location');
		}
	}

	public function bid($guid, $bid_amount)
	{
		if($this->session->userdata('character_guid') == NULL)
		{
			$this->session->set_flashdata('flash', 'Enchère impossible : vous n\'avez pas <a href="'.base_url('account/index').'">selectionné votre personnage</a>.');
			redirect('auction/see/'.$guid, 'location');
		}
		else
		{
			$valid = $this->auction_model->bid($guid, $character_guid, $bid_amount); //$guid refers to auction's guid
			if(!$valid)
			{
				$this->session->set_flashdata('flash', 'Enchère impossible (avez-vous assez d\'argent ?).');
				redirect('auction/see/'.$guid, 'location');
			}

			$this->session->set_flashdata('flash', 'Enchère effectué.');
			redirect('auction/see/'.$guid, 'location');
		}
	}

	/* Really not easy to implement (and not truly important?)
	public function sell()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		 //Validation rules
		$this->form_validation->set_rules('item_id', 'Id de l\'item', 'required|integer|is_natural');
		$this->form_validation->set_rules('quantity', 'Quantité', 'required|integer|is_natural');
		$this->form_validation->set_rules('buy_now_amount', 'Enchère initiale', 'required|integer|is_natural');
		$this->form_validation->set_rules('initial_bid_amount', 'Montant initial', 'required|integer|is_natural');
		$this->form_validation->set_rules('remaining_time', 'Temps restant', 'required|integer|is_natural');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('auction_sell_form');
		}
		else
		{
			$valid = $this->auction_model->sell($character_guid, $item_id, $quantity, $buy_now_amount, $initial_bid_amount, $remaining_time);
			if(!$valid)
			{
				$this->session->set_flashdata('flash', 'Erreur.');
				redirect('auction/sell', 'location');
			}
			else
			{
				$this->session->set_flashdata('flash', 'Enchère ajouté.');
				redirect('auction', 'location');
			}
		}
		
	}*/

}

/* End of file auction.php */
/* Location: ./application/controllers/auction.php */