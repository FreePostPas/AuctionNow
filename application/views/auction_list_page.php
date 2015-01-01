<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$CI =& get_instance();
	$CI->load->library('wow'); //To get a name before wowhead and show price
	include("include/header.php");
?>

<div id="header">
	<h1>AuctionNow</h1>
	<a href="#">Serveur</a>
</div>

<?php if(!$this->wow->is_empty($this->session->userdata('character'))): ?>
<div id="character_info">
	<p><?php echo $this->session->userdata['character']['name']; ?> (Démoniste humain)<br>Niveau <?php echo $this->session->userdata['character']['level']; ?><br><?php echo $this->wow->money_to_po($this->session->userdata['character']['money'], TRUE); ?></p>
	<p id="change_character"><a href="<?php echo site_url('account'); ?>">Choisir un autre personnage</a></p>
</div>
<?php else: //Normaly impossible ?>
<div id="character_info">
	<p><a href="<?php echo site_url('account'); ?>">Aucun personnage selectionné. Erreur.</a></p>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('flash') != NULL): ?>
<div class="error">
	<?php echo $this->session->flashdata('flash'); ?>
</div>
<?php endif; ?>

<div id="wrap">
	<p>Aucune recherche n'a été spécifié, les 10 dernières enchères sont affichées.</p>
	<form id="filter">
		<fieldset>
			<legend>Filtres</legend>
			<div class="filter_field">
				<label>Nom de l'item</label>
				<input type="number" name="searched_name">
			</div>
			<div class="filter_field">
				<label>Catégorie</label>
				<select>
					<option selected="selected">Toutes</option>
					<option>Armes</option>
					<option>Armures</option>
					<option>Sac</option>
					<option>Consommable</option>
					<option>Potion</option>
					<option>Parchemin</option>
				</select>
			</div>
			<div class="filter_field">
				<label>Sous-catégorie</label>
				<select>
					<option selected="selected">Toutes</option>
					<option>Baguettes</option>
					<option>Epée</option>
					<option>Hache</option>
					<option>Dague</option>
					<option>Lance</option>
					<option>Main</option>
				</select>
			</div>
			<div class="filter_field">
				<label>Sous-sous-catégorie</label>
				<select>
					<option selected="selected">Toutes</option>
					<option>Baguettes</option>
					<option>Epée</option>
					<option>Hache</option>
					<option>Dague</option>
					<option>Lance</option>
					<option>Main</option>
				</select>
			</div>
			<div class="filter_field">
				<label>Niveaux</label>
				<div id="filter_level_class">
					<input type="number" name="minlevel" placeholder="Min" class="filter_level_class">
					-
					<input type="number" name="maxlevel" placeholder="Max" class="filter_level_class">
				</div>
			</div>
			<div class="filter_field">
				<label>Rareté</label>
				<select>
					<option selected="selected">Gris</option>
					<option>Blanc</option>
					<option>Inhabituelle</option>
					<option>Rare</option>
					<option>Epique</option>
					<option>Légendaire</option
					<option>Main</option>
				</select>
			</div>
		</fieldset>

		<input type="checkbox" name="only_buyout" id="only_buyout">
		<label for="only_buyout">Uniquement en achat immédiat</label>
		<div id="filter_buttons">
			<input type="button" name="search" value="Réinitialiser">
			<input type="button" name="initialize" value="Recherche">
		</div>
		<div class="clear"></div>
	</form>

	<hr>

	<table>
		<thead>
			<tr>
				<td>Item</td>
				<td>Quantité</td>
				<td>Niveau</td>
				<td>Durée</td>
				<td>Prix</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		<?php if($last_auctions != NULL): foreach($last_auctions as $item): ?>
				<tr>
					<td><a href="<?php echo site_url('auction/see/'. $item['auction_guid']) ?>" rel="item=<?php echo $item['item_id']; ?>&amp;domain=fr"><?php echo $CI->wow->get_item_name_by_id($item['item_id']); ?></a></td> <!-- Text is changed by the WowHead tookit in the good language. -->
					<td>x<?php echo $item['quantity']; ?></td>
					<td>x<?php echo $item['level']; ?></td>
					<td><?php echo $item['remaining_time']; ?></td>
					<td><?php echo $CI->wow->money_to_po($item['last_bid_amount'], TRUE); ?><?php if($item['buy_now_amount'] != 0): ?><br><?php echo $CI->wow->money_to_po($item['buy_now_amount'], TRUE); ?><?php endif; ?></td>
					<td><a href="#">Enchérir</a><?php if($item['buy_now_amount'] != 0): ?><br><a href="#">Achat immédiat</a><?php endif; ?></td>
				</tr>
			<?php endforeach; else: ?>
			<tr><td colspan="6">Aucune enchère.</td></tr>
			<?php endif; ?>

			
		</tbody>
	</table>
</div>

<?php include("include/footer.php"); ?>