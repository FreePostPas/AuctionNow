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
	{redirect('auction/list_auction', 'location'); //Redirect to avoid duplicate content (instead of $this->load->view('auction_list_page'));
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
		if($this->auction_model->can_be_buy_instant($guid))
		{
			if($this->session->userdata('character_guid') == NULL)
			{
				$this->session->set_flashdata('flash', 'Achat impossible : vous n\'avez pas <a href="'.base_url('account/index').'">selectionné votre personnage</a>.');
				redirect('auction/see/'.$guid, 'location');
			}
			else
			{
				$character_guid = $this->session->userdata('character_guid');

				$valid = $this->auction_model->buy($guid, $character_guid); //$guid refers to auction's guid
				if($valid !== true)
				{
					switch($valid)
					{
						case "Miss money":
							$this->session->set_flashdata('flash', 'Vous n\'avez pas assez d\'argent pour acheter immédiatement cette enchère.');
							break;
						case "No auction":
							$this->session->set_flashdata('flash', 'L\'enchère que vous souhaitez acheter n\'existe plus.');
							break;
						case "Bidder is owner":
							$this->session->set_flashdata('flash', 'Vous ne pouvez pas acheter vos propres enchères.');
							break;
						default:
							$this->session->set_flashdata('flash', 'Erreur interne : '.$valid);
							break;
					}
					
					redirect('auction/see/'.$guid, 'location');
				}
				else
				{
					$this->session->set_flashdata('flash', 'Achat effectué. Vous devriez recevoir dans votre boite au lettre votre item.');
					redirect('auction/list_auction', 'location');	
				}			
			}
		}
		else
		{
			$this->session->set_flashdata('flash', 'Il est impossible de faire un achat immédiat sur cette enchère.');
			redirect('auction/see/'.$guid, 'location');
		}
	}

	public function bid($guid)
	{
		$tmp = $this->input->post('bid_amount'); //rValue problem with isset and direct input -> need tmp var
		if(isset($tmp) && is_int($this->input->post('bid_amount')))
		{
			$bid_amount = $this->input->post('bid_amount');
		}
		else
		{
			$this->session->set_flashdata('flash', 'Enchère impossible : vous n\'avez pas précisé la somme de votre enchère en PC (NB : 1PA = 1 000PC et 1PO = 1 000 000PC. Ex : 34PO 7PA 3 PC = 34 007 003PC');
			redirect('auction/see/'.$guid, 'location');
		}
		
		if($this->session->userdata('character_guid') == NULL)
		{
			$this->session->set_flashdata('flash', 'Enchère impossible : vous n\'avez pas <a href="'.base_url('account/index').'">selectionné votre personnage</a>.');
			redirect('auction/see/'.$guid, 'location');
		}
		else
		{
			$character_guid = $this->session->userdata('character_guid');

			$valid = $this->auction_model->bid($guid, $character_guid, $bid_amount); //$guid refers to auction's guid
			if(!$valid)
			{
				switch($valid)
				{
					case "Miss money":
						$this->session->set_flashdata('flash', 'Vous n\'avez pas assez d\'argent pour faire une offre sur cette enchère.');
						break;
					case "No auction":
						$this->session->set_flashdata('flash', 'L\'enchère sur laquelle vous souhaitez faire une offre n\'existe plus.');
						break;
					case "Bidder is owner":
						$this->session->set_flashdata('flash', 'Vous ne pouvez pas faire d\'offrs vos propres enchères.');
						break;
					default:
						$this->session->set_flashdata('flash', 'Erreur interne : '.$valid);
						break;
				}
				redirect('auction/see/'.$guid, 'location');
			}
			else
			{
				$this->session->set_flashdata('flash', 'Enchère effectué.');
				redirect('auction/see/'.$guid, 'location');
			}
		}
	}
}

/* End of file auction.php */
/* Location: ./application/controllers/auction.php */