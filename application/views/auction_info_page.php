<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
	$CI =& get_instance();
	$CI->load->library('wow'); //To get a name before wowhead
	include("include/header.php");
?>
	<h1>Enchère n°<?php echo $auction['0']; ?></h1>
	<?php if($this->session->flashdata('flash') != NULL): ?>
		<div class="error">
			<?php echo $this->session->flashdata('flash'); ?>
		</div>
	<?php endif; ?>

	<div id="body">
		<p>Cette page est sensé être la page d'enchère plus détaillé avec notamment la possibilité d'enchérir ou d'acheter.</p>
		
		<ul>
			<li><a href="http://www.wowhead.com/item=<?php echo $auction['1']; ?>"><?php echo $CI->wow->get_item_name_by_id($auction['1']); ?></a></li>
			<li>Quantitée : x<?php echo $auction['2']; ?></li>
			<li>Vendu par <?php echo $auction['5']; ?></li>
			<li>Encore <?php echo $auction['6']; ?> secondes avant la fin de la vente</li>
			<li>Prix en achat immédiat : <?php echo $auction['4']; ?></li>
			<li>Dernière enchère : <?php echo $auction['3']; ?></li>
		</ul>
		<p>
			<a href="<?php echo site_url('auction/buy/'.$auction['0']); ?>">Achat immédiat</a> (<strong><?php echo $auction['4']; ?></strong>, attention, l'achat n'est pas annulable)<br>
			<a href="<?php echo site_url('auction/bid/'.$auction['0']); ?>">Enchérir</a>
		</p>
		

		<p><a href="<?php echo site_url(); ?>">Retour à l'accueil</a></p>
	</div>

<?php include("include/footer.php"); ?>