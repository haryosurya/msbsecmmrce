<?php

/**
 * woocommerce-page.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 * 
 */

?>

<?php get_header(); ?>


<div class="container">

	<?php woocommerce_breadcrumb(); ?>	

	<?php if( get_theme_mod( 'bacola_shop_layout' ) == 'full-width' || bacola_get_option() == 'full-width') { ?>
		<div class="row content-wrapper">
			<div class="col-12 col-md-12 col-lg-12 content-primary">
				<?php get_template_part( 'includes/woocommerce/banner' ); ?>
			
				<?php woocommerce_content(); ?>
			</div>
		</div>
	<?php } elseif( get_theme_mod( 'bacola_shop_layout' ) == 'right-sidebar' || bacola_get_option() == 'right-sidebar') { ?>
		<div class="row content-wrapper sidebar-right">
			<div class="col-12 col-md-12 col-lg-9 content-primary">
				<?php get_template_part( 'includes/woocommerce/banner' ); ?>
			
				<?php woocommerce_content(); ?>
			</div>
			
			<div id="sidebar" class="col-12 col-md-3 col-lg-3 content-secondary site-sidebar">
				<div class="site-scroll">
					<div class="sidebar-inner">

						<div class="sidebar-mobile-header">
							<h3 class="entry-title"><?php esc_html_e('Filter Products','bacola'); ?></h3>

							<div class="close-sidebar">
								<i class="klbth-icon-x"></i>
							</div>
						</div>

						<?php if ( is_active_sidebar( 'shop-sidebar' ) ) { ?>
							<?php dynamic_sidebar( 'shop-sidebar' ); ?>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<?php if ( is_active_sidebar( 'shop-sidebar' ) ) { ?>
			<div class="row content-wrapper sidebar-left">
				<div class="col-12 col-md-12 col-lg-9 content-primary">
					<?php get_template_part( 'includes/woocommerce/banner' ); ?>
				
					<?php woocommerce_content(); ?>
				</div>
				
				<div id="sidebar" class="col-12 col-md-3 col-lg-3 content-secondary site-sidebar">
					<div class="site-scroll">
						<div class="sidebar-inner">

							<div class="sidebar-mobile-header">
								<h3 class="entry-title"><?php esc_html_e('Filter Products','bacola'); ?></h3>

								<div class="close-sidebar">
									<i class="klbth-icon-x"></i>
								</div>
							</div>

							<?php if ( is_active_sidebar( 'shop-sidebar' ) ) { ?>
								<?php dynamic_sidebar( 'shop-sidebar' ); ?>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="row content-wrapper">
				<div class="col-12 col-md-12 col-lg-12 content-primary">
					<?php get_template_part( 'includes/woocommerce/banner' ); ?>
				
					<?php woocommerce_content(); ?>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
</div>


<?php get_footer(); ?>