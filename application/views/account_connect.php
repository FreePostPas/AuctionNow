<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
<body>
<div id="wrap">
	<div id="login_back_site"><a href="#">Retour au site</a></div>
	
	<div id="login_content">
		<h1 id="prebox_title">AuctionNow</h1>
		<p id="prebox_description">Pour accéder à l'hotel des ventes en ligne, vous devez d'abord vous connecter avec votre compte serveur.</p>

		<div id="box">
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
				<input type="password" name="password">
				<a href="#">Mot de passe oublié</a><br>
				<a href="#">Créer un compte pour le serveur</a>

				<input type="submit" value="Connexion">
			<?php echo form_close(); ?>
			<div class="clear"></div>
			<p></p>
		</div>
	</div>
</div>
<?php include("include/footer.php"); ?>