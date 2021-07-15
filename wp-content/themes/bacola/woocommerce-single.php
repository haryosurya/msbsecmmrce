<?php
/**
 * woocommerce-single.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 * 
 */
?>

<?php get_header(); ?>

	<?php if(get_theme_mod('bacola_single_type') == 'type2' || get_theme_mod('bacola_single_type') == 'type4' || bacola_get_option() == 'type2' || bacola_get_option() == 'type4'){ ?>
		<?php $single_type = 'no-bg'; ?>
	<?php } elseif(get_theme_mod('bacola_single_type') == 'type3' || bacola_get_option() == 'type3'){ ?>	
		<?php $single_type = 'single-gray single-type3'; ?>
	<?php } else { ?>
		<?php $single_type = 'single-gray'; ?>
	<?php } ?>

	<div class="shop-content single-content <?php echo esc_attr($single_type); ?>">
		<div class="container">
			<?php woocommerce_breadcrumb(); ?>	
			
			<div class="single-wrapper">
				<?php woocommerce_content(); ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>