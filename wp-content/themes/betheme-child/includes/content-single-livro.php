<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// prev & next post -------------------
$single_post_nav = array(
	'hide-header'	=> false,	
	'hide-sticky'	=> false,	
	'in-same-term' => false,	
);

$opts_single_post_nav = mfn_opts_get( 'prev-next-nav' );
if( is_array( $opts_single_post_nav ) ){
	
	if( isset( $opts_single_post_nav['hide-header'] ) ){
		$single_post_nav['hide-header'] = true;
	}
	if( isset( $opts_single_post_nav['hide-sticky'] ) ){
		$single_post_nav['hide-sticky'] = true;
	}
	if( isset( $opts_single_post_nav['in-same-term'] ) ){
		$single_post_nav['in-same-term'] = true;
	}
	
}

$post_prev = get_adjacent_post( $single_post_nav['in-same-term'], '', true );
$post_next = get_adjacent_post( $single_post_nav['in-same-term'], '', false );
$blog_page_id = get_option('page_for_posts');


// post classes -----------------------
$classes = array();
if( ! mfn_post_thumbnail( get_the_ID() ) ) $classes[] = 'no-img';
if( get_post_meta(get_the_ID(), 'mfn-post-hide-image', true) ) $classes[] = 'no-img';
if( post_password_required() ) $classes[] = 'no-img';
if( ! mfn_opts_get( 'blog-title' ) ) $classes[] = 'no-title';

if( mfn_opts_get( 'share' ) == 'hide-mobile' ){
	$classes[] = 'no-share-mobile';
} elseif( ! mfn_opts_get( 'share' ) ) {
	$classes[] = 'no-share';
}


$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
$translate['tags'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-tags','Tags') : __('Tags','betheme');
$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
$translate['all'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
$translate['related'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-related','Related posts') : __('Related posts','betheme');
$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
?>

<?php
$sidebars = mfn_opts_get( 'sidebars' );

// sidebar 1 --------------------------------------------------------
if( get_post_type() == 'page' && mfn_opts_get('single-page-sidebar') ){
	// Theme Options | Single - Page
	$sidebar = trim( mfn_opts_get('single-page-sidebar') );
	
} elseif( get_post_type() == 'post' && is_single() && mfn_opts_get('single-sidebar') ){
	// Theme Options | Single - Post
	$sidebar = trim( mfn_opts_get('single-sidebar') );
} elseif( get_post_type() == 'portfolio' && is_single() && mfn_opts_get('single-portfolio-sidebar') ){
	// Theme Options | Single - Portfolio
	$sidebar = trim( mfn_opts_get('single-portfolio-sidebar') );
} else {
	// Post Meta
	$sidebar = get_post_meta( mfn_ID(), 'mfn-post-sidebar', true);
	if( $sidebar || $sidebar === '0' ) $sidebar = $sidebars[$sidebar];
}

if( $_GET && key_exists('mfn-s', $_GET) ){
	$sidebar = esc_html( $_GET['mfn-s'] ); // demo
}

// sidebar 2 --------------------------------------------------------
if( get_post_type() == 'page' && mfn_opts_get('single-page-sidebar2') ){
	// Theme Options | Single - Page
	$sidebar2 = trim( mfn_opts_get('single-page-sidebar2') );
} elseif( get_post_type() == 'post' && is_single() && mfn_opts_get('single-sidebar2') ){
	// Theme Options | Single - Post
	$sidebar2 = trim( mfn_opts_get('single-sidebar2') );
} elseif( get_post_type() == 'portfolio' && is_single() && mfn_opts_get('single-portfolio-sidebar2') ){
	// Theme Options | Single - Portfolio
	$sidebar2 = trim( mfn_opts_get('single-portfolio-sidebar2') );
} else {
	// Post Meta
	$sidebar2 = get_post_meta( mfn_ID(), 'mfn-post-sidebar2', true);
	if( $sidebar2 || $sidebar2 === '0' ) $sidebar2 = $sidebars[$sidebar2];
}

if( $_GET && key_exists('mfn-s2', $_GET) ){
	$sidebar2 = esc_html( $_GET['mfn-s2'] ); // demo
}
?>
<div id="fb-root"></div>
<script async defer src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.2"></script>
<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php 
		// single post navigation | sticky
	if( ! $single_post_nav['hide-sticky'] ){
		echo mfn_post_navigation_sticky( $post_prev, 'prev', 'icon-left-open-big' ); 
		echo mfn_post_navigation_sticky( $post_next, 'next', 'icon-right-open-big' );
	}
	?>
	
	<div class="post-wrapper-content">
		<div class="the_content_wrapper">
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
				<?php echo $echo_ano.$echo_editora; ?>
			</div>
		</div>
	</div>

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

	</div>


