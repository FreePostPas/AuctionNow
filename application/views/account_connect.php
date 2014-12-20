<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
	<h1>Connexion à <em>AuctionNow</em></h1>

	<div id="body">
		<div class="error">
			<?php
				echo validation_errors();
				
				if($this->session->flashdata('flash') != NULL)
					echo $this->session->flashdata('flash');
			?>
		</div>

		<p>Pour vous connecter, veuillez utiliser les mêmes identifiants que pour vous connecter au jeu.</p>

		<?php echo form_open('account/connect'); ?>

		<label>Nom d'utilisateur</label>
		<input type="text" name="username" value="<?php echo set_value('username'); ?>"><br>
		<label>Mot de passe</label>
		<input type="text" name="password" value="<?php echo set_value('password'); ?>"><br>
		<br>

		<input type="submit" value="Mettre aux enchères">
		<p>NB : Aucun style sur ce formulaire, ce n'est pas encore le but.</p>
		<?php echo form_close(); ?>


		<p><a href="<?php echo site_url(); ?>">Retour à l'accueil</a></p>
	</div>

<?php include("include/footer.php"); ?>