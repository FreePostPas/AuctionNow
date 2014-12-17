<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$CI =& get_instance();
	$this->load->library('wow'); //To get a name before wowhead
	include("include/header.php");
?>
	<h1>Bienvenue sur <em>AuctionNow</em> !</h1>

	<div id="body">
		<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
		<?php endif; ?>
		
		<p>Cette page est sensé être la future page d'accueil d'ActionNow, elle sera aussi la page d'affichage des dernières enchères ajoutées.</p>

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
			<?php foreach($last_auctions as $item): ?>
				<tr>
					<td><a href="<?php echo site_url('auction/see/'. $item['0']) ?>" rel="item=<?php echo $item['1']; ?>&amp;domain=fr"><?php echo $CI->wow->get_item_name_by_id($item['0']); ?></a></td> <!-- Text is changed by the WowHead tookit in the good language. -->
					<td>x<?php echo $item['2']; ?></td>
					<td><?php echo $item['6']; ?></td>
					<td><?php echo $item['5']; ?></td>
					<td><?php echo $item['3']['0'].'PO '.$item['3']['1'].'PA '.$item['3']['2'].'PC'; ?></td>
					<td><?php echo $item['4']['0'].'PO '.$item['4']['1'].'PA '.$item['4']['2'].'PC'; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<p><a href="<?php echo site_url('auction/sell'); ?>">Vendre un item</a></p>
	</div>

<?php include("include/footer.php"); ?>