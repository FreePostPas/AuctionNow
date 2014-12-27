<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$CI =& get_instance();
	$CI->load->library('wow'); //To get a name before wowhead
	include("include/header.php");
?>
	<h1>Enchère n°<?php echo $auction['auction_guid']; ?></h1>
	<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
	<?php endif; ?>

	<div id="body">
		<p>Cette page est sensé être la page d'enchère plus détaillé avec notamment la possibilité d'enchérir ou d'acheter.</p>
		
		<ul>
			<li><a href="http://www.wowhead.com/item=<?php echo $auction['item_id']; ?>"><?php echo $CI->wow->get_item_name_by_id($auction['item_id']); ?></a></li>
			<li>Quantitée : x<?php echo $auction['quantity']; ?></li>
			<li>Vendu par <?php echo $auction['seller_name']; ?></li>
			<li>Encore <?php echo $auction['remaining_time']; ?> secondes avant la fin de la vente</li>
			<?php if($auction['buy_now_amount'] != "0"): ?><li>Prix en achat immédiat : <?php echo $auction['buy_now_amount']; ?></li><?php endif; ?>
			<li>Dernière enchère : <?php echo $auction['last_bid_amount']; ?></li>
		</ul>
		<p>
			<?php if($auction['buy_now_amount'] != "0"): ?><a href="<?php echo site_url('auction/buy/'.$auction['auction_guid']); ?>">Achat immédiat</a> (<strong><?php echo $auction['buy_now_amount']; ?></strong>, attention, l'achat n'est pas annulable)<br><?php endif; ?>
			<a href="<?php echo site_url('auction/bid/'.$auction['auction_guid']); ?>">Enchérir</a>
		</p>


		<p><a href="<?php echo site_url(); ?>">Retour à l'accueil</a></p>
	</div>

<?php include("include/footer.php"); ?>