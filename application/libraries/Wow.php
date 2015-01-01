<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	* Name: Wow
	* Author : Adrien Albaladejo/Freegos/Ures/FreePostPas (only one crazy man)	
	* Wow is a library who has been made to contain few functions associates to world of warcraft
	* without any true relation between them
*/
class Wow {
	public function money_to_po($original_amount, $formated = FALSE)
	{
		/*
			* Name: money_to_po()
			* Transform an amount in the format of WoW : PO/PA/PC
		*/
		$po = floor($original_amount/10000);
		$pa = floor(($original_amount - $po*10000)/100);
		$pc = floor($original_amount - $po*10000 - $pa*100);

		if(!$formated)
			return array($po, $pa, $pc);
		else
			return $po.'PO '.$pa.'PA '.$pc.'PC';
	}

	public function get_item_name_by_id($id)
	{
		/*
			* Name: get_item_name_by_id($id)
			* Return the name of item from World's database waiting for a translation by the WowHead
			* toolkit.
			* In the case js is disable, this name is the only shown.
		*/
			
		$CI =& get_instance();
			
		$worlddb = $CI->load->database('world', TRUE);
		$query = $worlddb->select('name')->where('entry', $id)->get('item_template'); //Could be entry or id depending of world db version
			
		if($query->num_rows() > 0)
		{	
			$result = $query->row();
			return $result->name;
		}
		return 'Chargement...';
	}

	public function get_character_name_by_guid($guid)
	{
		/*
			* Name: get_character_name_by_guid($guid)
			* Return string containing name of character (selected by guid)
		*/

		$CI =& get_instance();
		
		$character_db = $CI->load->database('character', TRUE); //Database interface must be save into other var to avoid conflict
		$query = $character_db->select('name')->where('guid', $guid)->get('characters');

		if($query->num_rows() > 0)
		{
			$result = $query->row();
			return $result->name;
		}
		else
			return NULL;
	}

	public function get_item_instance_by_guid($guid, $field = NULL)
	{
		/*
			* Name: get_character_name_by_guid($guid)
			* Return string containing name of character (selected by guid)
		*/

		$CI =& get_instance();
		
		$character_db = $CI->load->database('character', TRUE); //Database interface must be save into other var to avoid conflict
		$query = $character_db->where('guid', $guid)->get('item_instance');

		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$data = array(
					"entry" => $result->itemEntry,
					"quantity" => $result->count
				);
			switch($field)
			{
				case "entry":
					return $data['entry'];
					break; //Unused line
				case "quantity":
					return $data['quantity'];
					break; //Unused line
				default:
					return $data;
					break; //Unused line
			}
		}

	}

	static function is_empty($val) {
		if(isset($val) && !empty($val))
			return false;
		return true;
	}
}

/* End of file wow.php */
/* Location: ./application/librairies/wow.php */