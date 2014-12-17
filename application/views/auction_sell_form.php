<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
	<h1>Ajouter une enchère</h1>

	<div id="body">
		<p>Cette page permet l'ajout par un personnage d'item.</p>
		
		<div class="error">
			<?php echo validation_errors(); ?>
		</div>
		
		<?php echo form_open('auction/sell'); ?>

			<label>Item (id)</label>
			<input type="text" name="item_id" value="<?php echo set_value('item_id'); ?>"><br>
			<label>Quantité</label>
			<input type="text" name="quantity" value="<?php echo set_value('quantity'); ?>"><br>
			<label>Prix en achat immédiat</label>
			<input type="text" name="buy_now_amount" value="<?php echo set_value('buy_now_amount'); ?>"><br>
			<label>Prix d'enchère initiale</label>
			<input type="text" name="initial_bid_amount" value="<?php echo set_value('initial_bid_amount'); ?>"><br>
			<lael>Temps avant la fin de l'enchère</label>
			<?php echo form_dropdown('remaining_time', array(
				'48' => '48',
				'24' => '24',
				'12' => '12',
				'6'  => '6'
			)); ?><br>

			<input type="submit" value="Mettre aux enchères">
			<p>NB : Aucun style sur ce formulaire, ce n'est pas encore le but.</p>
		<?php echo form_close(); ?>
		
	</div>

<?php include("include/footer.php"); ?>