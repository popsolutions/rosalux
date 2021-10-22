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
	'in-same-term'	=> false,	
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
	
	<div class="section_wrapper post-wrapper-content">
		<div class="the_content_wrapper">
			<div class="post-header">
				<?php
				$post_categories = array();
				$tags = get_the_tags( get_the_id());
				if($tags){
					foreach($tags as $t){
						$post_categories[] = '<a href="'.get_tag_link($t->term_id).'">'.$t->name.'</a>';
					}
				}
				$chapeu = get_post_meta(get_the_id(), 'chapeu');
				if($chapeu){ ?>
					<span class="chapeu"><?php echo $chapeu[0]; ?></span>
					<?php
				}
				?>
				<!-- <div class="single-categories"><?php //echo join(',', $post_categories); ?></div> -->
				<h1><?php the_title(); ?></h1>
				<div class="post-date"><?php the_date('d/m/Y'); ?> 
				<?php 
				$autor = get_post_meta(get_the_id(), 'autor'); 
				if($autor){ ?>
					por <?php echo $autor[0]; 
				} ?>
				<!-- <a href="<?php //echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> -->
					<?php //the_author_meta( 'display_name' ); ?>
					<!-- </a> -->
				</div>
			</div>
		</div>
	</div>

	<div class="post-wrapper-content single_content">
		<?php
		if( mfn_sidebar_classes() ){
			
			echo '<div class="sidebar sidebar-1 sidebar-left col-lg-2 col-md-12">';
			echo '<div class="widget-area clearfix '. mfn_opts_get('sidebar-lines') .'">';
			if( function_exists('is_buddypress') && is_buddypress() && is_active_sidebar( 'buddy' ) ){
				dynamic_sidebar( 'buddy' );
			} elseif( ! dynamic_sidebar( $sidebar ) ){
				mfn_nosidebar();
			}
			echo '</div>';
			echo '</div>';
		}
		?>
		<div class="post_content">
			
			<?php 
			// Content Builder & WordPress Editor Content
			mfn_builder_print( $post->ID );	
			
			?>
		</div>
		<?php
		if( mfn_sidebar_classes( true ) ){
			echo '<div class="sidebar sidebar-2 sidebar-right col-lg-2 col-md-12">';
			echo '<div class="widget-area clearfix '. mfn_opts_get('sidebar-lines') .'">';
			if ( ! dynamic_sidebar( $sidebar2 ) ) mfn_nosidebar();
			echo '</div>';
			echo '</div>';
		}
		?>
		
		<div class="section section-post-footer">
			<div class="section_wrapper clearfix">
				<div class="column one post-pager">
					<?php
						// List of pages
					wp_link_pages(array(
						'before'			=> '<div class="pager-single">',
						'after'				=> '</div>',
						'link_before'		=> '<span>',
						'link_after'		=> '</span>',
						'next_or_number'	=> 'number'
					));
					?>
				</div>
			</div>
		</div>
		
		<?php if( mfn_opts_get( 'share' ) && ( get_post_meta( get_the_ID(), 'mfn-post-template', true ) == 'intro' ) ): ?>
		<div class="section section-post-intro-share">
			<div class="section_wrapper clearfix">
				<div class="column one">

					<div class="share_wrapper clearfix">
						<span class='st_facebook_vcount' displayText='Facebook'></span>
						<span class='st_twitter_vcount' displayText='Tweet'></span>
						<span class='st_pinterest_vcount' displayText='Pinterest'></span>						
						
						<script src="http<?php mfn_ssl(1); ?>://w<?php mfn_ssl(1); ?>.sharethis.com/button/buttons.js"></script>
						<script>stLight.options({publisher: "1390eb48-c3c3-409a-903a-ca202d50de91", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
					</div>

				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
<div class="section_wrapper section section-post-related">
	<div class="section_wrapper clearfix">

		<div class="custom-related">
			<?php echo do_shortcode( '[custom-related-posts]' ); ?>
		</div>

		<?php
				// if( mfn_opts_get( 'blog-related' ) && $aCategories = wp_get_post_categories( get_the_ID() ) ){

				// 	$related_count  = intval( mfn_opts_get( 'blog-related' ) );
				// 	$related_cols 	= 'col-'. absint( mfn_opts_get( 'blog-related-columns', 3 ) );
				// 	$related_style	= mfn_opts_get( 'related-style' );

				// 	$args = array(
				// 		'category__in'			=> $aCategories,
				// 		'ignore_sticky_posts'	=> true,
				// 		'no_found_rows'			=> true,
				// 		'post__not_in'			=> array( get_the_ID() ),
				// 		'posts_per_page'		=> $related_count,
				// 		'post_status'			=> 'publish',
				// 	);

				// 	$query_related_posts = new WP_Query( $args );
				// 	if( $query_related_posts->have_posts() ){

				// 		echo '<div class="section-related-adjustment '. $related_style .'">';
		
				// 			echo '<h4>'. $translate['related'] .'</h4>';
		
				// 			echo '<div class="section-related-ul '. $related_cols .'">';
		
				// 				while ( $query_related_posts->have_posts() ){
				// 					$query_related_posts->the_post();
		
				// 					$related_class = '';
				// 					if( ! mfn_post_thumbnail( get_the_ID() ) ){
				// 						$related_class .= 'no-img';
				// 					}
		
				// 					$post_format = mfn_post_thumbnail_type( get_the_ID() );
				// 					if( mfn_opts_get( 'blog-related-images' ) ){
				// 						$post_format = mfn_opts_get( 'blog-related-images' );
				// 					}
		
				// 					echo '<div class="column post-related '. implode( ' ', get_post_class( $related_class ) ).'">';	
		
				// 						if( get_post_format() == 'quote'){
		
				// 							echo '<blockquote>';
				// 								echo '<a href="'. get_permalink() .'">';
				// 									the_title();
				// 								echo '</a>';
				// 							echo '</blockquote>';
		
				// 						} else {
		
				// 							echo '<div class="single-photo-wrapper '. $post_format .'">';
				// 								echo '<div class="image_frame scale-with-grid">';
		
				// 									echo '<div class="image_wrapper">';
				// 										echo mfn_post_thumbnail( get_the_ID(), 'related', false, mfn_opts_get( 'blog-related-images' ) );
				// 									echo '</div>';
		
				// 									if( has_post_thumbnail() && $caption = get_post( get_post_thumbnail_id() )->post_excerpt ){
				// 										echo '<p class="wp-caption-text '. mfn_opts_get( 'featured-image-caption' ) .'">'. $caption .'</p>';
				// 									}
		
				// 								echo '</div>';
				// 							echo '</div>';
		
				// 						}
		
				// 						echo '<div class="desc">';
				// 							if( get_post_format() != 'quote') echo '<h4><a href="'. get_permalink() .'">'. get_the_title() .'</a></h4>';
				// 							echo '<hr class="hr_color" />';
				// 						echo the_excerpt();
				// 						// echo the_author_posts_link();
				// 						echo '</div>';
		
				// 					echo '</div>';
				// 				}
		
				// 			echo '</div>';
		
				// 		echo '</div>';
				// 	}
				// 	wp_reset_postdata();
				// }	
		?>
		
	</div>
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


