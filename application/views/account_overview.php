<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php include("include/header.php"); ?>
<?php 
	$CI =& get_instance(); 
	$CI->load->library('wow');
?>
	<h1>Profil du compte</h1>

	<div id="body">
		<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
		<?php endif; ?>

		<p>
			<?php if($this->session->userdata('character') == NULL): ?>
				Vous n'avez pas encore choisi le personnage qui accède à l'hotel des ventes. C'est celui-ci qui recevra l'item en cas de succès de la vente, c'est aussi sur lui que sera prélever la somme (en PO) si vous gagnez l'enchère.
			<?php else: ?>
				Si vous désirez changer de personnage, selectionner le dans la liste suivante :
			<?php endif;  ?>
			
			<ul>
				<?php if($characters != NULL): foreach($characters as $character): ?>
					<li><a href="<?php echo site_url('account/choose_character/'.$character['guid']); ?>"><?php echo $character['name']. ' (Niveau '.$character['level']. ' avec '.$CI->wow->money_to_po($character['money'], TRUE).'PC)'; ?></a></li>
				<?php endforeach; else: ?>
					<li>Vous n'avez aucun personnage n'ayant le niveau suffisant pour utiliser l'hôtel des ventes.</li>
				<?php endif; ?>
			</ul>
		</p>

		
		<p><a href="<?php echo site_url(); ?>">Retour à l'accueil</a></p>
	</div>

<?php include("include/footer.php"); ?>