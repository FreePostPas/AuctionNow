<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	* Name: Wow
	* Wow is a library who has been made to contain few functions associates to world of warcraft
	* without any true relation between them
*/
class Wow {
        public function money_to_po($original_amount)
        {
        	/*
				* Name: money_to_po()
				* Transform an amount in the format of WoW : PO/PA/PC
        	*/
			$po = floor($original_amount/10000);
			$pa = floor(($original_amount - $po*10000)/100);
			$pc = floor($original_amount - $po*10000 - $pa*100);

			return array($po, $pa, $pc);

        }

        public function get_item_name_by_id($id)
        {
        	/*
				* Name: get_item_name_by_id($id)
				* Return the name of item from World's database waiting for a translation by the WowHead
				* toolkit.
				* In the case js is disable, this name is the only shown.
        	*/
			/*
				$CI =& get_instance();
			
				$worlddb = $CI->load->database('world', TRUE);
				$query = $worlddb->from('item_template')->select('name')->where('id', $id); //Could be entry on some server
				return $query->row()->name;
			*/
			//Before world db connection
			return 'Chargement...';
        }
}

/* End of file wow.php */
/* Location: ./application/librairies/wow.php */