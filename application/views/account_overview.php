<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
<?php 
	$CI =& get_instance(); 
	$CI->load->library('wow');
?>

<div id="wrap">
	<div id="login_back_site"><a href="<?php echo site_url(); ?>">Retour au site</a></div>

	<div id="choose_character_content">
		<h1 id="prebox_title">AuctionNow</h1>
		<p>Choisissez le personnage sur lequel vous souhaitez recevoir votre item et être débité.</p>
		<p class="little_text">Les personnages n'ayant pas un niveau suffisant pour accéder à l'hotel des ventes ne sont pas affichés.</p>
		
		<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
		<?php endif; ?>
			
				

		<div id="choose_character_list">

			<?php if($characters != NULL): foreach($characters as $character): ?>
				<div class="choose_character_item">
					<a href="<?php echo site_url('account/choose_character/'.$character['guid']); ?>">
						<p><?php echo $character['name']; ?> (Homme démoniste)<br>
						Niveau <?php echo $character['level']; ?><br>
						<?php echo $CI->wow->money_to_po($character['money'], TRUE); ?></p>
					</a>
				</div>
			<?php endforeach; else: ?>
				<p>Vous n'avez aucun personnage n'ayant le niveau suffisant pour utiliser l'hôtel des ventes.</p>
			<?php endif; ?>
			
			<div class="choose_character_item">
				<a href="#">
					<p>Ures (Elfe de la nuit voleur)<br>
					Niveau 70<br>
					429496PO 72PA 95PCPC</p>
				</a>
			</div>
			<div class="choose_character_item">
				<a href="#">
					<p>FreePostPas (Humain Chevalier de la Mort)<br>
					Niveau 55<br>
					429496PO 72PA 95PCPC</p>
				</a>
			</div>
		</div>
	</div>
</div>
<?php include("include/footer.php"); ?>
