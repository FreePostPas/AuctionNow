<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
	<h1>Connexion à <em>AuctionNow</em></h1>

	<div id="body">
		<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
		<?php endif; ?>
		
		<p>Pour vous connecter, veuillez utiliser les mêmes identifiants que pour vous connecter au jeu.</p>

		
		<p><a href="<?php echo site_url(); ?>">Retour à l'accueil</a></p>
	</div>

<?php include("include/footer.php"); ?>