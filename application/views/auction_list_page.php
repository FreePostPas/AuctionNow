<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$CI =& get_instance();
	$CI->load->library('wow'); //To get a name before wowhead and show price
	include("include/header.php");
?>
	<h1>Bienvenue sur <em>AuctionNow</em> !</h1>

	<div id="body">
		<p><em>AuctionNow</em> vous permet d'enchérir ou de faire de nouveau achat depuis n'importe où !<br>Retrouvez sur cette page les dernières enchères ou recherchez celle qui  pourraient vous intéresser.</p>

		<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
		<?php endif; ?>
		
		<h2>Les 10 dernières enchères</h2>
		<table>
			<thead>
			<tr>
				<td>Item</td>
				<td>Quantité</td>
				<td>Temps restant</td>
				<td>Vendeur</td>
				<td>Enchère actuelle</td>
				<td>Achat immédiat</td>
			</tr>	
			</thead>
			<?php if($last_auctions != NULL): foreach($last_auctions as $item): ?>
				<tr>
					<td><a href="<?php echo site_url('auction/see/'. $item['auction_guid']) ?>" rel="item=<?php echo $item['item_id']; ?>&amp;domain=fr"><?php echo $CI->wow->get_item_name_by_id($item['item_id']); ?></a></td> <!-- Text is changed by the WowHead tookit in the good language. -->
					<td>x<?php echo $item['quantity']; ?></td>
					<td><?php echo $item['remaining_time']; ?></td>
					<td><?php echo $item['seller_name']; ?></td>
					<td><?php echo $CI->wow->money_to_po($item['last_bid_amount'], TRUE); ?></td>
					<td><?php echo $CI->wow->money_to_po($item['buy_now_amount'], TRUE); ?></td>
				</tr>
			<?php endforeach; else: ?>
			<tr><td colspan="6">Aucune enchère.</td></tr>
			<?php endif; ?>
		</table>

		<h2>Rechercher des enchères</h2>
		<p></p>

	</div>

<?php include("include/footer.php"); ?>