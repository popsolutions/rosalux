<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php

	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );
	
	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
	
	
	// prev & next post -------------------
	$single_post_nav = array(
		'hide-header'	=> false,
		'hide-sticky'	=> false,
	);
	
	$opts_single_post_nav = mfn_opts_get( 'prev-next-nav' );
	if( is_array( $opts_single_post_nav ) ){

		if( isset( $opts_single_post_nav['hide-header'] ) ){
			$single_post_nav['hide-header'] = true;
		}
		if( isset( $opts_single_post_nav['hide-sticky'] ) ){
			$single_post_nav['hide-sticky'] = true;
		}

	}
	
	$post_prev = get_adjacent_post( false, '', true );
	$post_next = get_adjacent_post( false, '', false );
	
	// WC < 2.7 backward compatibility
	if( version_compare( WC_VERSION, '2.7', '<' ) ){
		$shop_page_id = woocommerce_get_page_id( 'shop' );
	} else {
		$shop_page_id = wc_get_page_id( 'shop' );
	}

	
	// post classes -----------------------
	$classes = array();
	
	if( mfn_opts_get( 'share' ) == 'hide-mobile' ){
		$classes[] = 'no-share-mobile';
	} elseif( ! mfn_opts_get( 'share' ) ) {
		$classes[] = 'no-share';
	}
	
	$single_product_style = mfn_opts_get( 'shop-product-style' );
	$classes[] = $single_product_style;

	
	// translate
	$translate['all'] = mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
	
	
	// WC < 2.7 backward compatibility
	if( version_compare( WC_VERSION, '2.7', '<' ) ){		
		$product_schema = 'itemscope itemtype="'. woocommerce_get_product_schema() .'"';
	} else {
		$product_schema = '';
	}
	?>

	<div <?php echo $product_schema; ?> id="product-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

		<?php 
		// single post navigation | sticky
		if( ! $single_post_nav['hide-sticky'] ){
			echo mfn_post_navigation_sticky( $post_prev, 'prev', 'icon-left-open-big' ); 
			echo mfn_post_navigation_sticky( $post_next, 'next', 'icon-right-open-big' );
		} 
		?>
		
		<?php 
		// single post navigation | header
		if( ! $single_post_nav['hide-header'] ){
			echo mfn_post_navigation_header( $post_prev, $post_next, $shop_page_id, $translate );
		}
		?>




		<div class="post-wrapper-content">
			<div class="post-header">
				<?php
				$id = get_the_ID();
				$author = get_post_meta($id, 'tp_livro_autor');
				$echo_author = $author ? '<h3>'.$author[0].'</h3>' : '';

				$editora = get_post_meta($id, 'tp_livro_editora');
				$echo_editora = $editora ? '<h4> - '.$editora[0].'</h4>' : '';

				$ano = get_post_meta($id, 'tp_livro_ano');
				$echo_ano = $ano ? '<h4>'.$ano[0].'</h4>' : '';

				$file = get_post_meta($id, 'tp_livro_file');
				$echo_file = $file ? '<a class="button float-right btn-sm p-2 px-4" href="'.$file[0].'" target="_blank">BAIXAR</a>' : '';

				$thumb = get_the_post_thumbnail_url($id, 'full');

				$thumb2 = get_the_post_thumbnail_url($id, array(395, 222));


				?>
				<?php //echo $echo_author; ?>
				<h1><?php the_title(); ?></h1>
				<?php echo $echo_author.$echo_ano.$echo_editora; ?>
			</div>
		</div>
		<?php
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		?>

		<div class="post-wrapper-content single_content">

			<div class="post_content">
				<div class="d-flex mb-4">
					<?php 
					if($thumb){ 
						?>
						<div class="book-thumb">
							<img src="<?php echo $thumb; ?>">
							<img src="<?php echo $thumb2; ?>" style="display: none">
						</div>
						<?php  
					}
					
					?>
					<div class="pl-3 book-desc">
						<?php 
						the_content(); 
						echo $echo_file;
						?>
						<?php
						global $product;
    				if ( $product->managing_stock() && $product->is_in_stock() && $product->get_stock_quantity() > 0 ){
    					?>
    					<p class="cart">
    						<a class="button btn-sm p-2 px-4" href="?add-to-cart=<?php echo $id;?>" rel="nofollow">SOLICITAR LIVRO <i class="icon-forward"></i></a>
							</p>
						<?php }?>
					</div>
				</div>
			</div>

		<!-- <?php //if( mfn_opts_get( 'share' ) && ( get_post_meta( get_the_ID(), 'mfn-post-template', true ) == 'intro' ) ): ?>
			<div class="section section-post-intro-share">
				<div class="section_wrapper clearfix">
					<div class="column one">

						<div class="share_wrapper clearfix">
							<span class='st_facebook_vcount' displayText='Facebook'></span>
							<span class='st_twitter_vcount' displayText='Tweet'></span>
							<span class='st_pinterest_vcount' displayText='Pinterest'></span>						
							
							<script src="http<?php //mfn_ssl(1); ?>://w<?php //mfn_ssl(1); ?>.sharethis.com/button/buttons.js"></script>
							<script>stLight.options({publisher: "1390eb48-c3c3-409a-903a-ca202d50de91", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
						</div>

					</div>
				</div>
			</div>
			<?php //endif; ?> -->
		</div>

		<?php if( mfn_opts_get( 'blog-comments' ) ): ?>
			<div class="section section-post-comments">
				<div class="section_wrapper clearfix">

					<div class="column one comments">
						<?php comments_template( '', true ); ?>
					</div>

				</div>
			</div>
		<?php endif; ?>

		<?php 
		woocommerce_upsell_display();
		if( mfn_opts_get( 'shop-related' ) ) woocommerce_output_related_products(); 
		?>


		<?php if( version_compare( WC_VERSION, '2.7', '<' ) ): ?>
			<meta itemprop="url" content="<?php the_permalink(); ?>" />
		<?php endif; ?>


	</div><!-- #product-<?php the_ID(); ?> -->

	<?php do_action( 'woocommerce_after_single_product' ); ?>