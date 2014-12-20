<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	* Name: Wow
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
}

/* End of file wow.php */
/* Location: ./application/librairies/wow.php */