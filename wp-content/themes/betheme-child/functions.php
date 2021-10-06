<?php
/*  Adicionar css e js do tema filho
 * -----------------------------------------------------------------------
 */
function wppop_theme_enqueue_scripts() {
	wp_enqueue_style('betheme-style');
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', array(), 20141119 );
	// wp_enqueue_style( 'slick', get_stylesheet_directory_uri().'/css/slick.css', array(), 1);
	// wp_enqueue_style( 'slick', get_stylesheet_directory_uri().'/css/slick-theme.css', array(), 1);
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri().'/css/custom-style.css', array(), 1);
	// wp_enqueue_style( 'checkbox', get_stylesheet_directory_uri().'/css/checkbox.scss', array(), 1);
	// wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '20120206', true );
	wp_enqueue_script( 'popper', get_stylesheet_directory_uri() . '/js/popper.min.js', null, null, true );
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '20120206', true );
}
add_action( 'wp_enqueue_scripts', 'wppop_theme_enqueue_scripts' );
/*  Mudar Logo da página inicial
* -----------------------------------------------------------------------
*/
function wppop_login_logo() {  ?>
	<style type="text/css">
	body.login div#login h1 a,
	.login h1 a {
		background-image: url(<?php echo site_url();?>/wp-content/uploads/2020/08/logo-rosalux.png) !important;
		height: auto;
		width: 100%;
		background-size: contain;
		background-repeat: no-repeat;
		padding-bottom: 90px;
	}
	body.login{
		background: #fcfcfc;
	}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'wppop_login_logo' );
/* Recuperando As Imagens De Um Post Do Wordpress
 * ------------------------------------------------------------------------------------------------------
 */
function post_get_images($ind=NULL){
	global $post;
	$list = array();
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image/jpeg',
		'numberposts' => -1,
		'post_status' => null,
		'orderby' => 'menu_order',
		'post_parent' => $post->ID
	);
	$attachments = get_posts($args);
	//jpg
	foreach($attachments as $at){
		$list[$at->ID] = $at->guid;
	}
	//bmp
	$args['post_mime_type'] = 'image/bmp';
	$attachments = get_posts($args);
	foreach($attachments as $at){
		$list[$at->ID] = $at->guid;
	}
	//png
	$args['post_mime_type'] = 'image/png';
	$attachments = get_posts($args);
	foreach($attachments as $at){
		$list[$at->ID] = $at->guid;
	}
	//gif
	$args['post_mime_type'] = 'image/gif';
	$attachments = get_posts($args);
	foreach($attachments as $at){
		$list[$at->ID] = $at->guid;
	}
	if(sizeof($list)){
		$a = 0;
		$images = array();
		foreach($list as $k => $v){
			$images[$a]	= $v;
			$a++;
		}
		unset($list);
		if(!is_null($ind)){
			if(is_null($images[$ind])){
				return false;
			}else{
				return $images[$ind];
			}
		}else{
			return $images;
		}
	}else{
		return false;
	}
}
/* Pegar o diretório raiz do Wordpress
 * ------------------------------------------------------------------------------------------------------
 */
function p_wproot($mod = 'e'){
	if ($mod == 'e') { //echo
		// imprime direto no local da inserção
		bloginfo('template_directory');
	}
	if ($mod == 'v') { //value 
		// retorna como valor o diretório raiz do wp (usar dentro de outras funções)
		$value = get_bloginfo('template_directory');
		return $value;
	}
}
/* Pegar o diretório raiz do tema
 * ------------------------------------------------------------------------------------------------------
 */
function p_wpurl($mod = 'e'){
	if ($mod == 'e') { //echo
		// imprime direto no local da inserção
		bloginfo('url');
	}
	if ($mod == 'v') { //value 
		// retorna como valor o diretório raiz do wp (usar dentro de outras funções)
		$value = get_bloginfo('url');
		return $value;
	}
}
/* Pegar o Diretório de Imagem
 * ------------------------------------------------------------------------------------------------------
 */
function p_img($subfolder = '',$mod = 'e'){ 
	// se vazio entende-se que é a raiz da pasta
	if (!$subfolder) { $subfolder = '';} else { $subfolder = $subfolder.'/'; }
	if ($mod == 'e') { //echo
		echo get_stylesheet_directory_uri().'/images/'.$subfolder;
	}
	if ($mod == 'v') { //value 
		// retorna como valor o diretório raiz do wp (usar dentro de outras funções)
		$value = get_bloginfo('template_directory').'/images/'.$subfolder;
		return $value;
	}
}
/* Chamar o Timthumb e aplicar as configurações
 * -----------------------------------------------------------------------
 */
function p_timthumb($w = 147, $h = 104, $a = 't', $q = 100, $modo = 't', $view = 'i',$images = null,$title = null,$this_ID = null,$html_begin = '',$html_end = '',$html_cond = null,$cond = null,$class = null) {
		/*--> Início Timthumb 
		$w => '260', //width
		$h => '137', //height
		$a => 't', //align (t)op (c)enter (b)ottom (r)ight (l)eft, tr tl br bl
		$q => '85'   //quality
		$modo => 't' //(t)thumb (g)gallery
		$view => 'i' //(i)img (l)link (v)value
		$images => null //post_get_images()
		$title => título do post
		$this_ID => id do post
		$html_begin => html inicial
		$html_end => html final
		$html_cond => html condicional (entra no lugar do html inicial)
		$cond => condição para a $html_cond aparcer (int)
		$class => Foi adicionado esse parametro devido as especificidades do banner
		*/
		// tamanho ampliado padrão para o template
		
		$apl_w = 934;
		//$apl_h = 660;
		$timthumb_ampliado = '&amp;w='.$apl_w.'&amp;q='.$q.'&amp;a='.$a;
		
		$timthumb_src = '/timthumb.php?src=';
		$timthumb_config = '&amp;h='.$h.'&amp;w='.$w.'&amp;q='.$q.'&amp;a='.$a.'"';
		
		if($this_ID == null){
			$this_ID = get_post_thumbnail_id();
		}
		
		if($modo == 'v'){
			if($images){
				return get_stylesheet_directory_uri().$timthumb_src.$images.$timthumb_config;
			}else{
				$thumb_id = get_post_thumbnail_id($this_ID); 
				$url = wp_get_attachment_image_src($thumb_id, 'full');
				$value = get_stylesheet_directory_uri().$timthumb_src.$url[0].$timthumb_config;
			}
			return $value;
		}
		if ($modo == 't') { // thumb
			// Pegar a url da img original do thumbnail e alterar seu tamanho
			$thumb_id = get_post_thumbnail_id($this_ID); 
			$url = wp_get_attachment_image_src($thumb_id, 'full');
			// verifica a $cond se existe e aplica o html
			if ($cond != null) {echo $html_cond;}
			else {echo $html_begin;}
			echo '<img src="'.
			get_stylesheet_directory_uri().
			$timthumb_src.$url[0].$timthumb_config.
			' class="attachment-post wp-post-image '.
			$class.'" alt="';
			echo $title;
			echo '" id="'.$this_ID.'"/>'."\n";
			echo $html_end;
		}
		if ($modo == 'g') { // gallery
			// Pegando as Imagens do post
			$images = post_get_images();
			$n = 1; // Contador de imagens
			// mostrar os links das imagens
			if ($view == 'l') {
				if($images){
					//echo count($images);
					foreach ($images as $url) {
						//if($n != 1) {//pular a primeira imagem (thumbnail)
						$img[$n] = $url;
						// verifica a $cond se existe e aplica o html
						if ($cond != null and $cond == $n) {echo $html_cond;}
						else {
							if($n == 1){
								echo $html_begin;
							}
						}
						echo p_wproot('v').$timthumb_src.$img[$n].$timthumb_config;
						$n++;
					}
				}
			}
			if ($view == 'i') {
				if($images){
					foreach ($images as $url) {
						if($n != 1) {//pular a primeira imagem (thumbnail)
							$img[$n] = $url;
							//verifica a $cond se existe e aplica o html rel="Gallery['.$this_ID.']"
							if ($cond != null and $cond == $n) {echo $html_cond;}
							else {echo $html_begin;}
							echo '<img src="'.
							p_wproot('v').
							$timthumb_src.$url.$timthumb_config.
							' class="attachment-post wp-post-image" alt="';
							echo $title;
							echo '" />'."\n";
							echo $html_end;
						}
						$n++;
					}
				}
			}
		}
}//--> fim Timhumb
// Get featured image
function wppop_ST4_get_FB_image($post_ID) {
	$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
	if ($post_thumbnail_id) {
		$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'fb-preview');
		return $post_thumbnail_img[0];
	}
}
// Get post excerpt
function wppop_ST4_get_FB_description($post) {
	if ($post->post_excerpt) {
		return $post->post_excerpt;
	}
	else {
		// Post excerpt is not set, so we take first 55 words from post content
		$excerpt_length = 55;
		// Clean post content
		$text = str_replace("\r\n"," ", strip_tags(strip_shortcodes($post->post_content)));
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			$excerpt = implode(' ', $words);
			return $excerpt;
		}
	}
}
//Exclude pages from WordPress Search
/*
if (!is_admin()) {
	function wpb_search_filter($query) {
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}
			return $query;
	}
	add_filter('pre_get_posts','wpb_search_filter');
}
*/
// modificado por Sergio Dobke
function post_types_search( $query ) {
	//if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
	if ( $query->is_search() && ! is_admin() ) {
		$query->set( 'post_type', array( 'product', 'post', 'tribe_events' ) );
		$query->set( 'tax_query', '');
/*		[tax_query] => Array
				(
					[0] => Array
						(
							[taxonomy] => language
							[field] => term_taxonomy_id
							[terms] => 384
							[operator] => IN
						)
				)
*/
				/*
		echo '<div style"clear:both"><pre>';
		print_r($query);
		echo '</pre></div>';
		*/
	}
}
add_action( 'pre_get_posts', 'post_types_search' );
// Adicionar meta dados ao site para Facebook
function wppop_ST4FB_header() {
	if(is_single()){
		global $post;
		$post_description = wppop_ST4_get_FB_description($post);
		$post_featured_image = wppop_ST4_get_FB_image($post->ID);
		if ( (is_single()) AND ($post_featured_image) AND ($post_description) ) { ?>
			<meta name="title" content="<?php echo $post->post_title; ?>" />
			<meta name="description" content="<?php echo $post_description; ?>" />
			<link rel="image_src" href="<?php echo $post_featured_image; ?>" />
			<?php
		}
	}
}
add_action('wp_head', 'wppop_ST4FB_header');
// The custom function MUST be hooked to the init action hook

//add_action( 'init', 'lc_register_movie_post_type' );

// A custom function that calls register_post_type
function lc_register_movie_post_type() {
  // Set various pieces of text, $labels is used inside the $args array
	$labels = array(
		'name' => _x( 'Livros', 'post type general name' ),
		'singular_name' => _x( 'Livro', 'post type singular name' ),
	);
  // Set various pieces of information about the post type
	$args = array(
		'labels' => $labels,
		'description' => 'Livros',
		'public' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
		'menu_position' => 5,
		'taxonomies' => array('post_tag','category'),
		'menu_icon' => 'dashicons-book-alt'
	);
  // Register the movie post type with all the information contained in the $arguments array
	register_post_type( 'livro', $args );
}
/* --------------------------------------------------
* Script do Facebook
* --------------------------------------------------- */
add_action('wp_head', 'wppop_facebook_script');
function wppop_facebook_script(){ ?>
	<div id="fb-root"></div>
	<script async defer src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.2"></script>
	<?php  
}
/* --------------------------------------------------
* Documentação
* --------------------------------------------------- */
add_action( 'admin_menu', 'wppop_manual_link' );
function wppop_manual_link() {
	add_menu_page( 'Manual', 'Manual Rosa Luxemburgo', 'read', 'manual-do-site', 'wppop_manual_func', 'dashicons-layout', 1);
}
function wppop_manual_func(){ 
	include('manual/manual.html');
}
/*-------------- Metaboxes ----------------------------*/
 /**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */
/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */
if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function custom_fields_metabox() {
	$prefix = '';
	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_livro = new_cmb2_box( array(
		'id'            => 'post_type_livro_metabox',
		'title'         => esc_html__( 'Dados do Livro', 'cmb2' ),
	'object_types'  => array( 'product', ), // Post type
) );
	$cmb_livro->add_field( array(
		'name'             => 'Autor do livro',
		'desc'             => 'Autor(es) do Livro',
		'id'               => 'tp_livro_autor',
		'type'             => 'text'
	));
	$cmb_livro->add_field( array(
		'name'             => 'Ano',
		'desc'             => '',
		'id'               => 'tp_livro_ano',
		'type'             => 'text'
	));
	$cmb_livro->add_field( array(
		'name'             => 'Editora',
		'desc'             => '',
		'id'               => 'tp_livro_editora',
		'type'             => 'text'
	));
	$cmb_livro->add_field( array(
		'name'             => 'Arquivo',
		'desc'             => 'Arquivo ou URL',
		'id'               => 'tp_livro_file',
		'type'             => 'file',
	// Optional:
		'options' => array(
	  'url' => false, // Hide the text input for the url
	),
		'text'    => array(
	  'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
	),
	// query_args are passed to wp.media's library query.
		'query_args' => array(
	  'type' => 'application/pdf', // Make library only display PDFs.
	  // Or only allow gif, jpg, or png images
	  // 'type' => array(
	  // 	'image/gif',
	  // 	'image/jpeg',
	  // 	'image/png',
	  // ),
	),
	'preview_size' => 'large', // Image size to use when previewing in the admin.
));
	$cmb_livro->add_field( array(
		'name'             => 'Formato',
		'desc'             => '',
		'id'               => 'tp_livro_formato',
		'type'             => 'multicheck',
		// 'multiple' => true, // Store values in individual rows
		'options' => array(
			'digital' => esc_html__( 'Digital', 'cmb2' ),
			'fisico' => esc_html__( 'Físico', 'cmb2' ),
		),
	));
	$cmb_chapeu = new_cmb2_box( array(
		'id'            => 'chapeu_metabox',
		'title'         => esc_html__( 'Dados Personalizados', 'cmb2' ),
	'object_types'  => array( 'post', ), // Post type
) );
	$cmb_chapeu->add_field( array(
		'name'             => 'Chapeu',
		'desc'             => '',
		'id'               => 'chapeu',
		'type'             => 'text'
	));
	$cmb_chapeu->add_field( array(
		'name'             => 'Autor',
		'desc'             => '',
		'id'               => 'autor',
		'type'             => 'text'
	));
}
add_action( 'cmb2_admin_init', 'custom_fields_metabox' );
/********************
 * BANNER HOME - slider
 ********************/
function pops_main_content($atts){
	$quant = isset($atts['quant']) ? $atts['quant'] : 3;
	$out = '';
	//$categories = array();
	/*
	if(isset($atts['especiais'])){
	  $especiais = array_slice(explode(',',$atts['especiais']), 0, $quant);
	  $quant = $quant - count($especiais);
	  foreach ($especiais as $e){
		$category = get_category_by_slug($e);
		if($category){
		  $cat_obj = [];
		  $cat_obj['post_type'] = 'category';
		  $cat_obj['post_title'] = $category->name;
		  $cat_obj['link'] = get_category_link($category->term_id);
		  $cat_obj['excerpt'] = $category->description;
		  if (function_exists('z_taxonomy_image_url')){
			$cat_obj['img'] = z_taxonomy_image_url($category->term_id);
		  }
		  $categories[] =  (object) $cat_obj;
		}
	  }
	}*/
	if($quant > 0){
		if( isset( $atts['category'] ) ){
			$cat = get_category_by_slug( $atts['category'] );
			if( $cat ){
				$args['cat'] = $cat->term_id;
			}
			//$args['category_name'] = $atts['category'];
		}
		//slider1
		//print_r($cat);
		//echo '<br>Cat: '.$cat->term_id;
		if( isset( $atts['category__not_in'] ) ){
			$cat_not_in = $atts['category__not_in'];
			$slugs = explode( "," , $cat_not_in);
			$cat_not_in_ids = array();
			foreach ( $slugs as $slug ) {
				$cat = get_category_by_slug($slug);
				if($cat){
					$cat_not_in_ids[] = $cat->term_id;
				}
			}
			$args['category__not_in'] = $cat_not_in_ids;
		}
		$args = array(
			'posts_per_page' => $quant,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => array('livro','post','evento'),
			'post_status' => 'publish'
		);
		switch($cat->term_id){
			case 21053: // PT
			$lang_id = 384;
			break;
			case 21055: // ES
			$lang_id = 380;
			break;
		}
		
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'category',
				'terms' => array($cat->term_id, $lang_id),
				'field' => 'term_taxonomy_id',
				'operator' => 'IN',
			),
		);
		/*
			  echo '<pre>';
			  print_r($args);
			  echo '</pre>';
		*/
			  $posts = get_posts($args);
		//$testing = new WP_Query($args);
		//echo $testing->request;
			  $recent_posts = $posts;
		//$recent_posts = array_merge($posts, $categories);
			}else{
				$recent_posts = $categories;
			}
			if(count($recent_posts) > 0){
				if(isset($atts['id'])){
					$id = $atts['id'];
				}else{
					$id = '';
				}
				$out .= '<div class="home">';
				$out .= '<div id="'.$id.'" class="carousel slide" data-ride="carousel">';
				$out .= '<ol class="carousel-indicators">';
				for($i = 0; $i < count($recent_posts); $i++){
					$out .= '<li data-target="#'.$id.'" data-slide-to="'.$i.'" class="active"></li>';
				}
				$out .= '</ol>';
				$out .= '<div class="carousel-inner">';
				$i = 0;
				foreach($recent_posts as $post){
					$class = $i == 0 ? 'active' : '';
					if($post->post_type == "post" || $post->post_type == "livro" || $post->post_type == "evento"){
						$title = $post->post_title;
						$link = get_permalink( $post->ID );
						$img = p_timthumb(715, 375, 'c', 100, 'v', null, null, null, $post->ID);
						$excerpt = $post->post_excerpt;
					}
					else if($post->post_type == "category"){
						$title = $post->post_title;
						$link = $post->link;
						$img = p_timthumb(715, 375, 'c', 100, 'v', null, $post->img);
						$excerpt = $post->excerpt;
					}
					$out .= '<div class="carousel-item '.$class.'">';
					$out .= '<a href="'.$link.'">';
					$out .= '<img class="d-block w-100" src="'.$img.'">';
					$out .= '</a><div class="carousel-caption">';
					$out .= '<h2>'.$title.'</h2>';
					if(isset($atts['show_excerpt']) and $atts['show_excerpt'] == true){
						$out .= '<p>'.$excerpt.'</p>';
					}
					$out .= '</div></div>';
					$i = 1;
				}
				$out .= '</div>';
				$out .= '<a class="carousel-control-prev" href="#'.$id.'" role="button" data-slide="prev">';
				$out .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
				$out .= '<span class="sr-only">Previous</span></a>';
				$out .= '<a class="carousel-control-next" href="#'.$id.'" role="button" data-slide="next">';
				$out .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
				$out .= '<span class="sr-only">Next</span></a>';
				$out .= '</div></div>';
			}
			return $out;
		}
		add_shortcode('slider-home','pops_main_content');
		function format_banner_output(){
			$out = '';
			$out .= '<div class="carousel-item '.$class.'">';
			$out .= '<a href="'.$link.'">';
			$out .= '<img class="d-block w-100" src="'.p_timthumb(715, 375, 'c', 100, 'v', null, null, null, $post->ID).'">';
			$out .= '</a><div class="carousel-caption">';
			$out .= '<h2>'.$post->post_title.'</h2>';
			if(isset($atts['show_excerpt']) and $atts['show_excerpt'] == true){
				$out .= '<p>'.$post->post_excerpt.'</p>';
			}
			$out .= '</div></div>';
			return $out;
		}
// Posts retangulares (home, 4 posts abaixo do banner no layout inicial) destaque
		function pops_square_post($atts){
			$cat_id = 0;
			$args = array(
				'numberposts' =>  $atts['quant'],
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_status' => 'publish',
				'post_type' => array('livro','post')
			);
			if(isset($atts['rows'])){
				$rows = $atts['rows'];
			}else{
				$rows = '';
			}
			if(isset($atts['categoria'])){
				$cat = get_category_by_slug($atts['categoria']);
				if ($cat) {
					$args['category'] = intval($cat->term_id);
				}
			}
/*
  if(isset($atts['tipo'])){
	$args['post_type'] = $tipo;
  }
*/
  if(isset($atts['ids'])){
  	$args['include'] = explode($atts['ids']);
  }
  if(isset($atts['category__not_in'])){
  	$cat_not_in = $atts['category__not_in'];
  	$slugs = explode(",", $cat_not_in);
  	$cat_not_in_ids = array();
  	foreach ($slugs as $slug) {
  		$cat = get_category_by_slug($slug);
  		if($cat){
  			$cat_not_in_ids[] = $cat->term_id;
  		}
  	}
  	$args['category__not_in'] = $cat_not_in_ids;
  }
  $recent_posts = get_posts($args);
  $out = '';
  $class = $atts['classe'] ? join(" ", explode(",",$atts['classe'])) : '';
  $out_classe = $atts['out_classe'] ? join(" ", explode(",",$atts['out_classe'])) : '';
  $n = 0;
  $i = 0;
  $length = count($recent_posts);
  foreach($recent_posts as $post){
  	$link = get_permalink( $post->ID );
  	if($n == 0){
  		$out .= '<div class="'.$out_classe.'">';
  	}
  	$post_tags = get_the_tags($post->ID);
  	if($post_tags){
  		$first_tag = $post_tags[0]->name;
  		$first_tag_link = get_tag_link( $post_tags[0]->term_id );
  	}
  	$meta = get_post_meta($post->ID);
  	$chapeu = get_post_meta($post->ID, 'chapeu');
  	$out .= '<div class="'.$class.'">'; 
  	$out .= '<a href="'.$link.'">';
  	$out .= '<img class="d-block" src="'.p_timthumb($atts['width'], $atts['height'], 'c', 100, 'v', null, null, null, $post->ID).'"></a>';
	// $out .= (isset($atts['show_cat']) and $post_tags) ? '<a href="'.$link.'"><h3>'.$first_tag.'</h3></a>' : '';
  	$out .= $chapeu ? '<h3>'.$chapeu[0].'</h3>' : '';
  	$out .= (isset($atts['show_title']) and $atts['show_title'] == true) ? '<a href="'.$link.'"><h2>'.$post->post_title.'</h2></a>' : '';
  	$out .= (isset($atts['show_excerpt']) and $atts['show_excerpt'] == true) ? '<div><p>'.$post->post_excerpt.'</p></div>' : '';
  	$out .= '</div>';
  	$n++;
  	$i++;
  	if($n == 2 || $i == $length){
  		$out .= '</div>';
  		$n = 0;
  	}
  }
  return $out;
}
add_shortcode('show-posts-rlx','pops_square_post');
// Slider de conteúdos, com slicker.js
function pops_rosa_slider($atts){  
	if(isset($atts['quant'])){
		$quant = $atts['quant'];
	}else{
		$quant = 4;
	}
	$args = array(
		'posts_per_page' => $quant,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_status' => 'publish'
	);
  //print_r($atts);
  /*
  if(isset($atts['categoria'])){
	//$cate = get_term_by( 'slug', $atts['categoria'], 'category' );
	$cat = get_category_by_slug($atts['categoria']);
	if ( $cat ) {
	  $args['category'] = $cat->term_id;
	}
  }
  */
  if( isset( $atts['categoria'] ) ){
  	$cat = get_category_by_slug( $atts['categoria'] );
  	if( $cat ){
		//$args['cat'] = $cat->term_id;
  	}
	//echo '<br>cat ('.$atts['categoria'].'): '.$cat->term_id;
  	switch($cat->term_id){
			case 21323: // audios PT
			case 25: // videos
			$lang_id = 384;
			break;
			case 21431: // audios ES
			case 98: // videos
			$lang_id = 380;
			break;
		}
		
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'category',
				'terms' => array($cat->term_id, $lang_id),
				'field' => 'term_taxonomy_id',
				'operator' => 'IN',
			),
		);
	//$args['category_name'] = $atts['categoria'];
	}
// slider2
	if(isset($atts['tipo'])){
		$args['post_type'] = $atts['tipo'];
	}
	if(isset($atts['ids'])){
		$args['post__in'] = explode(',', $atts['ids']);
		$args['orderby'] = 'post__in';
	}
	if(isset($atts['category__not_in'])){
		$cat_not_in = $atts['category__not_in'];
		$slugs = explode(",", $cat_not_in);
		$cat_not_in_ids = array();
		foreach ($slugs as $slug) {
			$cat = get_category_by_slug($slug);
			if($cat){
				$cat_not_in_ids[] = $cat->term_id;
			}
		}
		$args['category__not_in'] = $cat_not_in_ids;
	}
	$out = '';
	$slider_query = new WP_Query($args);
  /*
  echo '<br><pre>';
  print_r($args);
  echo '</pre>';
  echo $slider_query->request;
  */
  if($slider_query->found_posts > 0) {
  	$recent_posts = get_posts($args);
  	$class = isset($atts['item_class']) ? join(" ", explode(",",$atts['item_class'])) : '';
  	$outher_class = isset($atts['outher_class']) ? join(" ", explode(",",$atts['outher_class'])) : '';
  	$img_class = isset($atts['img_class']) ? join(" ", explode(",",$atts['img_class'])) : '';
  	$out .= '<div class="'.$outher_class.'"';
  	if(isset($atts['data-slick'])){
  		$out .= " data-slick='".$data_slick."'";
  	}
  	$out .= '>';
  	foreach($recent_posts as $post){
  		$link = get_permalink( $post->ID );
  		$post_tags = get_the_tags($post->ID);
  		if($post_tags){
  			$first_tag = $post_tags[0]->name;
  		}
  		$out .= '<div class="'.$class.'">';
  		$out .= '<a href="'.$link.'">';
  		$out .= '<img class="'.$img_class.'" src="'.p_timthumb($atts['width'], $atts['height'], 'c', 100, 'v', null, null, null, $post->ID).'">';
  		$out .= '<div>';
  		$out .= (isset($atts['show_title']) and $atts['show_title'] == true) ? '<h2>'.$post->post_title.'</h2>' : '';
		// $out .= (isset($atts['show_author']) and $atts['show_author'] == true) ? '<h3>'.$author.'</h3>' : '';
  		if(isset($atts['show_author']) and $atts['show_author'] == true){
  			if(isset($atts['tipo']) && $atts['tipo'] == "product"){
  				$author = get_post_meta($post->ID, 'tp_livro_autor');
  			}else{
  				$author_id = $post->post_author;
  				$author = get_the_author_meta( 'user_nicename' , $author_id );
  			}
  			$out .= '<h2>'.$post->post_title.'</h2>';
  		}
  		$out .= '</div></a></div>'; 
  	}
  	$out .= '</div>';
  }
  return $out;
}
add_shortcode('rosa-slider','pops_rosa_slider');
/**************************
 *  Renderizar javascript
 *
 **************************/
add_action('wp_head', 'wppop_map_script');
function wppop_map_script(){ ?>
	<script>
		jQuery(document).ready(function($) {
			setTimeout(function(){
				jQuery("#turnkey-credit").nextAll().remove();
				let times = 0;
				jQuery(".responsive-menu-toggle").on('click', function(e){
			// console.log('click');
			e.preventDefault();
			if(times == 0){
				let social_menu = jQuery('#Side_slide .menu_wrapper').children().clone();
				let menu_items = jQuery("#menu-social-menu li");
				jQuery('#Side_slide .menu_wrapper').children().remove();
				const itens = jQuery('.secondary-menu').clone();
			  // console.log(itens);
			  let size = menu_items.length;
			  jQuery('#Side_slide .menu_wrapper').append(itens);
			  console.log(menu_items[0]);
			  console.log(menu_items[1]);
			  jQuery('#Side_slide .menu_wrapper .secondary-menu').prepend(menu_items[0]);
			  jQuery('#Side_slide .menu_wrapper .secondary-menu').prepend(menu_items[1]);
			  jQuery('#Side_slide .menu_wrapper .secondary-menu').append(menu_items[size-2]);
			  jQuery('#Side_slide .menu_wrapper .secondary-menu').append(menu_items[size-1]);
			  jQuery('.menu-item-has-children.submenu').on('click', function(){
				// console.log('click2');
				jQuery(this).find('.sub-menu').toggle()
			})
			  jQuery("#search").on('submit', function(e){
			  	e.preventDefault();
			  	var search = jQuery("#search-input").val();
			  	if(search.length > 0){
			  		window.location.href = '<?php echo site_url(); ?>' + '/?s=' + search;
			  	}
			  })
			  times = 1;
			}
		})
			}, 1000)
			jQuery("#search_icon_rslx").parents('a').on('click', function(e){
		  // console.log('click');
		  e.preventDefault();
		  jQuery("#search-input").toggleClass("open-search");
		})
			jQuery("#search").on('submit', function(e){
				e.preventDefault();
				var search = jQuery("#search-input").val();
				if(search.length > 0){
					window.location.href = '<?php echo site_url(); ?>' + '/?s=' + search;
				}
			})
		});
	</script>
	<?php
	if(is_front_page()){ ?>
		<link href='<?php echo get_stylesheet_directory_uri(); ?>/fullcalendar/main.min.css' rel='stylesheet' />
		<script src='<?php echo get_stylesheet_directory_uri(); ?>/fullcalendar/main.min.js'></script>
		<script src='<?php echo get_stylesheet_directory_uri(); ?>/fullcalendar/locale/es.js'></script>
		<script src='<?php echo get_stylesheet_directory_uri(); ?>/fullcalendar/locale/pt-br.js'></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/checkbox-x.min.js"></script>
		<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/checkbox-x.min.css" rel="stylesheet">
		<script>
			jQuery(function($){
				$(document).ready(function(){
					const prevArrow = '<div class="slick-prev"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/left-arrow.png"></div>';
					const nextArrow = '<div class="slick-next"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/right-arrow.png"></div>';
					const responsive_settings = [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true,
							dots: true
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
					];
					setTimeout(function(){
						$('.rosa-slider').slick(
						{
							infinite: true,
							slidesToShow: 4,
							slidesToScroll: 4,
							prevArrow: prevArrow,
							nextArrow: nextArrow,
							responsive: responsive_settings
						}
						);
						$('.rosa-slider-videos').slick(
						{
							infinite: true,
							slidesToShow: 3,
							slidesToScroll: 3,
							prevArrow:  prevArrow,
							nextArrow: nextArrow,
							responsive: responsive_settings
						}
						);
						$('.rosa-slider-audios').slick(
						{
							infinite: true,
							slidesToShow: 4,
							slidesToScroll: 4,
							prevArrow: prevArrow,
							nextArrow: nextArrow,
							responsive: responsive_settings
						}
						);
					}, 1000);
				});
			});
			var evt_list = <?php echo json_encode(pops_get_month_events()); ?>;
			console.log(evt_list);
			document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('calendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'dayGridMonth',
					locale: 'pt-br',
					events: evt_list,
					headerToolbar: false,
					fixedWeekCount: false,
					dayHeaderFormat: {
						weekday: 'narrow'
					}
				});
				calendar.render();
				jQuery(calendarEl).append('<div id="evt-list"></div>');
				evt_list.slice(0, 2).forEach(function(i){
					var d = new Date(i.start.replace(/-/g, "/"));
					var m = d.getMonth() + 1;
					var el_evt = '<div><a href="'+ i.guid +'"><h3>'+ d.getDate() + '/' + m + '/' + d.getFullYear() + i.term +'</h3><p>'+ i.title + '</p></a></div>';
					jQuery("#evt-list").append(el_evt);
				});
				let button = '<a class="button  button_size_1 button_js" href="/eventos" 0="">';
				button += '<span class="button_label">MAIS EVENTOS</span></a>';
				jQuery("#evt-list").append(button);
				jQuery('.fc-bg-event').hover(function(){
					console.log(jQuery(this));
					var title = jQuery(this).find('.fc-event-title').text();
					let event = evt_list.filter(function(i){
						return i.title == title;
					})
					const options = {
						content: '<div>' + event[0].thumbnail + ' ' + event[0].content + '</div>',
						title: '<h3>' + title + '</h3>',
						container: 'body',
						html: true
					};
					jQuery(this).popover(options);
					jQuery(this).popover('toggle');
				})
			});
		// });
	</script>
	<?php
}
else if(is_page('sobre-nos')){ ?>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/d3.v3.min.js"></script>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/topojson.v0.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var width = parseInt(jQuery("#mapa").css("width")),
			height = 500;
			var projection = d3.geo.mercator()
			.center([0, 5])
			.scale(200)
			.rotate([-180,0]);
			var svg = d3.select("#mapa .mcb-section-inner").append("svg")
			.attr("width", width)
			.attr("height", height);
			var path = d3.geo.path()
			.projection(projection);
			var g = svg.append("g");
				// load and display the World
				d3.json("<?php echo get_stylesheet_directory_uri(); ?>/js/world-110m2.json", function(error, topology) {
				// load and display the cities
				d3.csv("<?php echo get_stylesheet_directory_uri(); ?>/data/cities.csv", function(error, data) {
					g.selectAll("circle")
					.data(data)
					.enter()
					.append("circle")
					.attr("cx", function(d) {
						return projection([d.lon, d.lat])[0];
					})
					.attr("cy", function(d) {
						return projection([d.lon, d.lat])[1];
					})
					.attr("r", 5)
					.style("fill", "red");
				});
				g.selectAll("path")
				.data(topojson.object(topology, topology.objects.countries)
					.geometries)
				.enter()
				.append("path")
				.attr("d", path)
			});
				// zoom and pan
				var zoom = d3.behavior.zoom()
				.on("zoom",function() {
					g.attr("transform","translate("+ 
						d3.event.translate.join(",")+")scale("+d3.event.scale+")");
					g.selectAll("circle")
					.attr("d", path.projection(projection));
					g.selectAll("path")  
					.attr("d", path.projection(projection));
				});
				svg.call(zoom)
			});
		</script>
		<?php  
	}
	else if(is_page('biblioteca') || is_page('biblioteca-es') || is_page('audiovisual') || is_page('audiovisual-es')){ ?>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/checkbox-x.min.js"></script>
		<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/checkbox-x.min.css" rel="stylesheet">
		<script>
			var abc = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
			function print_result_accordions(response){
				var results = jQuery('#results');
				response.forEach(function(i){
					var div = '<div class="card"><div class="card-header" id="heading-'+ i.ID +'"><h5 class="mb-0">';
					div += '<button class="btn btn-link collapsed p-2" type="button" data-toggle="collapse" data-target="#collapse-'+ i.ID +'" ';
					div += ' aria-expanded="false" aria-controls="collapse-'+ i.ID +'">' + i.post_title + ' </button></h5></div>';
					div += '<div id="collapse-'+ i.ID +'" class="collapse" aria-labelledby="heading-' + i.ID + '"';
					div += ' data-parent="#results"><div class="card-body">';
					if(i.thumb){
						div += '<div class="card-img"><img src="'+ i.thumb +'"></div>';
					}
				div += '<div class="card-content">'; //<div class="' + i.tp_livro_formato +'"></div>';
				div += '<p>' + i.tp_livro_autor + '</p>';
				div += '<p>' + i.tp_livro_editora + '</p>';
				div += '<p>' + i.tp_livro_ano + '</p>';
				if(i.stock > 0){
					//div += '<a href="?add-to-cart='+i.ID+'"" rel="nofollow">SOLICITAR LIVRO</a>';
					div += '<p><a href="'+ i.permalink +'" target="_blank">SOLICITAR LIVRO  GRÁTIS</a></p>';
				}
				div += '<p><a href="'+ i.permalink +'" target="_blank">Baixar</a></p>';
				div += '<div class="content">' + i.post_excerpt +'</div>';
				div += '</div></div></div></div>';
				results.append(div);
			});
			}
			function busca_titulo(letra){
				var dados_envio = {
					'mais_noticias_nonce': js_global.mais_noticias_nonce,
					'paged': 1,
					'action': 'mais_noticias',
					'search': letra,
					'type': 'first_letter',
					'page': 'biblioteca'
				}
				var results = jQuery('#results');
				jQuery.ajax({
					url: js_global.xhr_url,
					type: 'POST',
					data: dados_envio,
					dataType: 'JSON',
					success: function(response) {
						console.log(response);
						if (response == '401'){
							console.log('Requisição inválida')
						}
						else if (response == 402) {
							results.append('<p class="text-center lead">Não há resultados.</p>');
						} else {
							if(response.results.length > 0){
								jQuery('#results').children().remove();
								print_result_accordions(response.results);
							}
						}
					}
				});
			}
			function print_results_midiateca(response, search, params, div){
				console.log(response);
				console.log(search);
				jQuery(div > '.results').children().remove();
				var results = jQuery('#results');
				let div_titles = [
				['videos','Vídeos'],
				['audios','Podcasts']
				];
				if(search != ""){
					results.append('<div class="results"><h1>Resultados da Busca por "' + search + '"</h1></div>');
				}
				div_titles.forEach(function(i){
					if(jQuery("#" + i[0]).length == 0){
						results.append('<div id="' + i[0] + '"><h2>' + i[1] + '</h2></div>');
						jQuery("#" + i[0]).append('<div class="results"></div>');
					}
			  // <div class="img-area"><img src="' + i[2]+ '"/></div></div>');
			})
				response.results.forEach(function(i){
					var div = '<div class="result">';
					div += '<div class="picture">';
					div += '<a href="' + i.link + '"><img src="' + i.thumb + '"></a></div>';
					div += '<div class="text">';
					div += '<span class="chapeu">' + i.chapeu + '</span>';
					div += '<a href="' + i.link + '"><h3>' + i.title + '</h3></a>';
					div += '<div class="excerpt">' + i.excerpt + '</div>';
					div += '</div>';
					div += '</div>';
					if(i.categories.indexOf("Videos") > -1){
						jQuery("#videos .results").append(div);
					}
					else if(i.categories.indexOf("Áudios") > -1){
						jQuery("#audios .results").append(div)
					}
					else{
						console.log(i);
					}
				});
				if(jQuery("#videos .pager").length == 0){
					jQuery('#videos').append('<div class="pager"><div class="pages pages-videos"></div></div>');
					for(var i=1; i <= Math.ceil(parseInt(response.count_videos/6)); i++){
						jQuery('.pages-videos').append('<a href="#" class="page page-number" data-number="'+ i +'">'+ i +'</a>');
					}
				}
				if(jQuery("#audios .pager").length == 0){
					jQuery('#audios').append('<div class="pager"><div class="pages pages-podcasts"></div></div>');
					for(var i=1; i <= Math.ceil(parseInt(response.count_audios/6)); i++){
						jQuery('.pages-podcasts').append('<a href="#" class="page page-number" data-number="'+ i +'">'+ i +'</a>');
					}
				}
				jQuery('.page.page-number').on('click', function(e){
					e.preventDefault();
					jQuery('.page.page-number').removeClass('active');
					const page = parseInt(jQuery(this).attr("data-number"));
					const div = jQuery(this).parents('.pager').parent().attr('id');
					jQuery(this).addClass('active');
					sendForm(search, 'AUDIOVISUAL', div, page, "#" + div + ' .results');
					document.querySelector("#" + div + ' .results').scrollIntoView({ behavior: 'smooth' })
				});
			}
			function sendForm(search, page, type = 'general', page_number = 1, div = "#results"){
				var results = jQuery(div);
				results.children().remove();
				var search_filters = [];
				jQuery('.tipo .item input[type="checkbox"]').each(function(i){
					if(jQuery(this).val() == ""){
						search_filters.push(jQuery(this).attr('name'));
					}
				});
				var dados_envio = {
					'mais_noticias_nonce': js_global.mais_noticias_nonce,
					'page_number': page_number,
					'action': 'mais_noticias',
					'search': search,
					'type': type,
					'params': search_filters,
					'page': page
				}
				console.log('dados_envio');
				console.log(dados_envio);
				jQuery.ajax({
					url: js_global.xhr_url,
					type: 'POST',
					data: dados_envio,
					dataType: 'JSON',
					success: function(response) {
						console.log('response');
						console.log(response);
						if (response == '401'){
							console.log('Requisição inválida')
						}
						else if (response == 402) {
							results.append('<p class="text-center lead">Não há resultados.</p>');
						} else {
							if(response.results.length > 0){
								if(page == "BIBLIOTECA"){
									print_result_accordions(response.results);
								}
								else if(page == "AUDIOVISUAL"){
									print_results_midiateca(response, search, dados_envio, div);
								}
							}else{
								results.append('<p>Nenhum resultado encontrado.</p>');
							}
						}
					}
				});
			}
			jQuery(document).ready(function($) {
				var page = '<?php the_title(); ?>';
				if(page == "BIBLIOTECA" || page === "BIBLIOTECA ES"){
					jQuery('#search-button').on('click', function(e) {
						e.preventDefault();
						var search = jQuery('#pesquisa').val();
						if(search != ""){
							jQuery('#results').children().remove();
							sendForm(search, page);
						}
					});
					jQuery('.letters').children().remove();
					abc.forEach(function(i){
						jQuery('.letters').append('<div class="letter">'+ i +'</div>');
					});
					jQuery('.letter').on('click', function(){
						jQuery('#results').children().remove();
						jQuery('.letter').removeClass('active');
						jQuery(this).addClass('active');
						busca_titulo(jQuery(this).text());
					});
					const responsive_settings = [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true,
							dots: true
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
					];
					jQuery('.rosa-slider').slick(
					{
						infinite: true,
						slidesToShow: 4,
						slidesToScroll: 4,
						prevArrow: '<div class="slick-prev"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/left-arrow.png"></div>',
						nextArrow: '<div class="slick-next"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/right-arrow.png"></div>',
						responsive: responsive_settings
					}
					);
				}
				if(page == "AUDIOVISUAL" || page === "AUDIOVISUAL ES"){
					sendForm("", page, 'both');
					jQuery("#search-button").on('click', function(e) {
						e.preventDefault();
						var search = jQuery('#pesquisa').val();
						if(search != ""){
							jQuery('#results').children().remove();
							sendForm(search, page, 'both');
						}
					});
				}
			})
		</script>
		<?php
	}
	else if ( is_post_type_archive('tribe_events') || is_singular( 'tribe_events' ) ) { ?>
		<script>
			function update_events(){
				jQuery(".tribe-event-date-start").each(function(i){
					var text = jQuery(this).text().trim();
					var split_date = text.split(' ');
					var today = new Date();
					var date = split_date[0].split('/');
					console.log(text);
					console.log(date);
					today.setDate(date[0]);
					today.setMonth(parseInt(date[1]) - 1);
					console.log(parseInt(date[1]) - 1);
					console.log(today);
					var mes = today.toLocaleString('default', { month: 'long' });
					var weekday = today.toLocaleString('default', { weekday: 'long'});
					console.log(mes);
					console.log(split_date);
					console.log(date);
					console.log(weekday);
					let new_format = '<span class="tribe-event-date-start">';
					new_format += '<h6>' + weekday +'</h6><span>' + date[0] + '</span>';
					new_format += '<p>' + mes + '</p></span>';
					var parent = jQuery(this).parent();
					parent.children().remove();
					parent.html(new_format);
				});
				jQuery(".type-tribe_events").each(function(i){
					const height = jQuery(this).find('.tribe-events-list-event-title').css('height');
					jQuery(this).find('.tribe-events-list-event-description').css('padding-top', height);
				});
				jQuery('.tribe-events-past, .tribe-events-nav-next').on('click', function(){
					setTimeout(function(){
						update_events()
					},3000)
				})
			}
			jQuery(document).ready(function($){
				update_events();          
			});
		</script>
		<?php
	}
	elseif(is_category()){
		$cat = get_queried_object();
		if(substr($cat->slug, 0, 9) == "especial-"){ 
			$livros = get_posts(array(
				'category_name' => $cat->slug,
				'post_type' => 'product',
				'posts_per_page'=>-1));
			if($livros){
				$count_slides = count($livros) >= 4 ? 4 : count($livros);
				?>
				<script>
					jQuery(function($){
						$(document).ready(function(){
							const prevArrow = '<div class="slick-prev"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/left-arrow.png"></div>';
							const nextArrow = '<div class="slick-next"><img src="<?php echo site_url(); ?>/wp-content/uploads/2020/11/right-arrow.png"></div>';
							setTimeout(function(){
								$('.rosa-slider').slick(
								{
									infinite: true,
									slidesToShow: <?php echo $count_slides; ?>,
									slidesToScroll: <?php echo $count_slides; ?>,
									prevArrow: prevArrow,
									nextArrow: nextArrow
								}
								);
							}, 1000);
						});
					});
				</script>
				<?php
			}
		}
	}
}
//Adiciona um script para o WordPress
add_action( 'wp_enqueue_scripts', 'secure_enqueue_script' );
function secure_enqueue_script() {
	wp_register_script( 'secure-ajax-access', esc_url( add_query_arg( array( 'js_global' => 1 ), site_url() ) ) );
	wp_enqueue_script( 'secure-ajax-access' );
}
// //Joga o nonce e a url para as requisições para dentro do Javascript criado acima
add_action( 'template_redirect', 'javascript_variaveis' );
function javascript_variaveis() {
	if ( !isset( $_GET[ 'js_global' ] ) ) return;
	$nonce = wp_create_nonce('mais_noticias_nonce');
	$variaveis_javascript = array(
	'mais_noticias_nonce' => $nonce, //Esta função cria um nonce para nossa requisição para buscar mais notícias, por exemplo.
	'xhr_url'             => admin_url('admin-ajax.php') // Forma para pegar a url para as consultas dinamicamente.
);
	$new_array = array();
	foreach( $variaveis_javascript as $var => $value ) $new_array[] = esc_js( $var ) . " : '" . esc_js( $value ) . "'";
	header("Content-type: application/x-javascript");
	printf('var %s = {%s};', 'js_global', implode( ',', $new_array ) );
	exit;
}
/**************
 * 
 * 
 */
function format_results_midiateca($_args){
	$query = new WP_Query($_args);
	$res = array();
	$res[0] = array();
	$res[1] = array();
	$res[2] = $query->found_posts;
	if ( $query->have_posts() ) :
		$res[3] = $query->post_count; 
		while ( $query->have_posts() ) : $query->the_post();
			$post = array();
			$id = get_the_ID();
			$categories = get_the_category();
			$cats = array();
			if ( ! empty( $categories ) ) {
				foreach( $categories as $category ) {
					$cats[] = $category->name;
				}
			}
			$chapeu = get_post_meta($id, 'chapeu');
			$post['categories'] = $cats;
			$post['title'] = get_the_title();
			$post['thumb'] = p_timthumb(344, 181, 'c', 100, 'v', null, null, null, $id);
			$post['excerpt'] = get_the_excerpt($id);
			$post['link'] = get_permalink();
			$post['date'] = get_the_date();
			$product = wc_get_product( $id );
			$post['chapeu'] = $chapeu ? $chapeu[0] : '';
			$res[0][] = $post;
			$res[1][] = $id;
		endwhile;
		wp_reset_postdata();
	endif;
	return $res;
}
/*********************
 Buscar posts
 **********************/
 function mais_noticias() {
 	if( ! wp_verify_nonce( $_POST['mais_noticias_nonce'], 'mais_noticias_nonce' ) ) {
	echo '401'; // Caso não seja verificado o nonce enviado, a requisição vai retornar 401
	die();
}
global $wpdb;
$arr = array();
$posts = array();
$args = array();
$wild = '%';
$str = '';
$search_term = $_POST['search'];
$params = isset($_POST['params']) ? $_POST['params'] : array();
$arr['params'] = $params;
$args['post_status'] = 'publish';
if(strtolower($_POST['page']) == "biblioteca"){
	if($_POST['type'] == "first_letter"){
		$query_ = "LOWER(post_title) LIKE LOWER(%s)";
		$str .= $wpdb->esc_like($search_term) . $wild;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE $query_ AND post_type='product' ORDER BY post_title", $str);
		$new_query = $wpdb->remove_placeholder_escape($query);
		$posts = $wpdb->get_results($new_query);
		$arr['posts'] = $posts;
	}
	else if($_POST['type'] == 'general'){
		$query_ = "post_title LIKE %s";
		$str .= $wild . $wpdb->esc_like($search_term) . $wild;
		$args['post_type'] = 'product';
		$meta_queries = array();
		if(in_array('autor', $params) || count($params) == 0){
			$_args = $args;
			$meta = array(
				'key' => 'tp_livro_autor',
				'value' => '[[:<:]]'.$search_term.'[[:>:]]',
				'compare' => 'REGEXP');
			$meta_queries[] = $meta;
			$_args['meta_query'] = $meta_queries;
			$arr['autor_results'] = get_posts($_args);
			$arr['autor_query'] = $_args;
			if(is_array($arr['autor_results']) && count($arr['autor_results']) > 0){
				$res = merge_unique($posts, $arr['autor_results']);
				$posts = $res;
			}
		}
		if(in_array('titulo', $params) || count($params) == 0){
			$_args = $args;
			$_args['cc_search_post_title_only'] = $search_term;
			$_args['suppress_filters'] = FALSE;
			$arr['titulo_query'] = $_args;
			add_filter( 'posts_where', 'cc_post_title_filter', 10, 2 );
			$arr['titulo_result'] = get_posts($_args);
			remove_filter( 'posts_where', 'cc_post_title_filter', 10 );
			if($arr['titulo_result'] && count($arr['titulo_result']) > 0){
				$res_arr = merge_unique($posts, $arr['titulo_result']);
				$posts = $res_arr;
			}
		}
		if(in_array('assunto', $params) || count($params) == 0){
			$_args = $args;
			$assuntos = explode(',', $search_term);
			$tags = array();
			foreach ($assuntos as $a) {
				$tags[] = sanitize_title($a);
			}
			$_args['tag'] = $tags; 
			$arr['assunto_result'] = get_posts($_args);
			$arr['assunto_query'] = $_args;
			if($arr['assunto_result'] && count($arr['assunto_result']) > 0){
				$res = merge_unique($posts, $arr['assunto_result']);
				$posts = $res;
			}
		}
		$arr['get_posts'] = $posts;
	}
	$results = array();
	$fields = array('tp_livro_ano', 'tp_livro_autor', 'tp_livro_editora', 'tp_livro_file'); //'tp_livro_formato'
	foreach ($posts as $post) {
		$arr_post = (array) $post;
		$product = wc_get_product( $post->ID );
		$arr_post['thumb'] = get_the_post_thumbnail_url($post->ID);
		$arr_post['permalink'] = get_permalink($post->ID);
		$arr_post['stock'] = $product->get_total_stock();
		foreach($fields as $f){
			$meta = get_post_meta(intval($post->ID), $f);
			if($meta){
				$arr_post[$f] = $meta[0];
			}
		}
		$results[] = $arr_post;
	}
}
  // MIDIATECA
else if(strtolower($_POST['page']) == "audiovisual"){
	$_ids = array();
	$results = array();
	$counts = array();
	$count = 0;
	$per_page = 6;
	$category__in = array();
	$total = 0;
	$queries = array();
	$count_audios = 0;
	$count_videos = 0;
	// $page_audios = $_POST['page_number'];
	// $page_videos = $_POST['page_number'];
	if($_POST['type'] == "audios" || $_POST['type'] == "both"){
		$cat_audios = get_category_by_slug('audios');
		$id_audios = $cat_audios->term_id;
		$category__in[] = $cat_audios->term_id;
		if(count($params) == 0 || in_array('podcast', $params)){
			$_args = $args;
			$_args['s'] = sanitize_title($search_term);
			$_args['category_name'] = 'audios';
			$_args['posts_per_page'] = $per_page;
			$_args['page'] = $_POST['page_number'];
			$_args['paged'] = $_POST['page_number'];
			$queries[] = $_args;
			$res = format_results_midiateca($_args);
			$_results = array_merge($res[0], $results);
			$results = $_results;
			$post_ids = array_merge($res[1], $_ids);
			$_ids = $post_ids;
			$count += $res[2];
			$counts[] = $res[2];
			$total += $res[3];
			$count_audios += $res[2];
		}
	}
	if($_POST['type'] == "videos" || $_POST['type'] == "both"){
		$cat_videos = get_category_by_slug('videos');
		$id_videos = $cat_videos->term_id;
		$category__in[] = $cat_videos->term_id;
		if(count($params) == 0 || in_array('video', $params)){
			$_args = $args;
			$_args['s'] = sanitize_title($search_term);
			$_args['category_name'] = 'videos';
			$_args['posts_per_page'] = $per_page;
			$_args['page'] = $_POST['page_number'];
			$_args['paged'] = $_POST['page_number'];
			$queries[] = $_args;
			$res = format_results_midiateca($_args);
			$_results = array_merge($res[0], $results);
			$results = $_results;
			$post_ids = array_merge($res[1], $_ids);
			$_ids = $post_ids;
			$count += $res[2];
			$counts[] = $res[2];
			$total += $res[3];
			$count_videos += $res[2];
		}
	}
	if(in_array('assunto', $params)){
		$_args = $args;
		$_args['tag'] = $search_term;
		$_args['post__not_in'] = $_ids;
		$_args['posts_per_page'] = $per_page;
		$_args['category__in'] = $category__in;
		$queries[] = $_args;
		$res = format_results_midiateca($_args);
		foreach($res[0] as $r){
			$r['tag'] = $search_term; 
		}
		$_res = merge_unique($results, $res[0]);
		$results = $_res;
		$post_ids = array_merge($res[1], $_ids);
		$_ids = $post_ids;
		$count += $res[2];
		$counts[] = $res[2];
		$total += $res[3];
	}
	if(in_array('ano', $params)){
		if(strlen($search_term) == 4 && is_numeric($search_term)){
			$not_in = array();
			$_args = $args;
			$_args['year'] = intval($search_term);
		// $_args['posts_per_page'] = $per_page;
			$_args['category__in'] = $category__in;
		// $_args['page'] = isset($params['page']) ? $params['page'] : 1;
			$queries[] = $_args;
			$res = format_results_midiateca($_args);
			$_res = merge_unique($results, $res[0]);
			$results = $_res;
			$post_ids = array_merge($res[1], $_ids);
			$_ids = $post_ids;
			$count += $res[2];
			$counts[] = $res[2];
			$total += $res[3];
		}
	}
	$arr['counts'] = $count;
	$arr['pages'] = $count/$per_page;
	$arr['queries'] = $queries;
	foreach ($posts as $post) {
		$arr_post = (array) $post;
		$results[] = $arr_post;
	}
}
$arr['results'] = $results;
$arr['total'] = $total;
$arr['count_audios'] = $count_audios;
$arr['count_videos'] = $count_videos;
echo json_encode($arr);
  // } else {
  //   echo 402;
  // }
exit;
}
add_action('wp_ajax_nopriv_mais_noticias', 'mais_noticias');
add_action('wp_ajax_mais_noticias', 'mais_noticias');
// modificado por Sergio Dobke
// Filtrar eventos do mes atual para mostrar na home
function pops_get_month_events(){
	$date = date('Y-m-d');
	$args = array(
		'post_type'   => 'tribe_events',
		'post_status' => 'publish',
		'orderby'   => '_EventStartDate',
		'order' => 'ASC',
		'meta_query'  => array(
			array(
				'key'     => '_EventStartDate',
				'value'   => $date,
				'compare' => '>='
			)
		),
		'lang' => 'pt-br'
	);
	$events = get_posts($args);
  // $events = tribe_get_events( [
  //   'posts_per_page' => -1,
  //   // 'meta_query'  => array(
  //   //     array(
  //   //       'key'     => '_EventStartDate',
  //   //       'value'   => $date,
  //   //       'compare' => 'LIKE'
  //   //     )
  //   //   )
  // ]);
	$out = array();
	if($events){
		foreach ($events as $e) {
			$e_date = get_post_meta($e->ID, '_EventStartDate');
			$terms = get_the_terms($e->ID, 'tribe_events_cat');
			$term = $terms ? ' - '.$terms[0]->name : '';
			$content = tribe_events_get_the_excerpt($e->ID, wp_kses_allowed_html( 'post' ) );
			$thumb_img = get_the_post_thumbnail($e->ID);
			$event = array(
				'id' => $e->ID,
				'title' => $e->post_title,
				'start' => $e_date[0],
				'term' => $term,
				'guid' => $e->guid,
				'display' => 'background',
				'allDay' => true,
				'backgroundColor' => '#ff2600',
				'content' => $content,
				'thumbnail' => $thumb_img
			);
			$out[] = $event;
		}
	}else{
		$out['query'] = $args;
		$out['events'] = $events;
	}
	return $out;
}
function cc_post_title_filter($where, &$wp_query) {
	global $wpdb;
	if ($search_term = $wp_query->get( 'cc_search_post_title_only' )){
		$where .= ' AND '.  $wpdb->posts . '.post_type=\'product\' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $search_term ) . '%\'';
	}
	else{
		if ( $search_term = $wp_query->get( 'cc_search_post_title' ) ) {
			$where .= ' OR ('.  $wpdb->posts . '.post_type=\'product\' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $search_term ) . '%\')';
		}
	}
	return $where;
}
// Merge array of objects and return unique only
function merge_unique($array1,$array2){
	$merged_keyed = array_column(array_merge($array1,$array2), NULL, 'id');
	ksort($merged_keyed);
	return array_values($merged_keyed);
}
// get attachment by title
/*
function get_attachment_url_by_title( $title = '' ) {
  global $wpdb;
  $return = array();
  
  $wild = '%';
  $str = '';
  // image/jpeg, image/pjpeg
  // image/png
  // image/gif
  $mime_type = " AND post_mime_type IN ('image/png', 'image/gif', 'image/jpeg', 'image/pjpeg')";
  if($title != ''){
	$query_ = "post_title LIKE %s AND";
	$str = $wild . $wpdb->esc_like($title) . $wild;
	$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE $query_ post_type='attachment' $mime_type ORDER BY post_date DESC", $str);
  }else{
	$query_ = "";
	$str = "";
	$query = "SELECT * FROM {$wpdb->prefix}posts WHERE $query_ post_type='attachment' $mime_type ORDER BY post_date DESC";
  }
  
  $the_query = $wpdb->remove_placeholder_escape($query);
  $attachments = $wpdb->get_results($the_query);
  $return['query'] = $the_query;
  $attachments_url = array();
  if ( $attachments ){    
	  foreach($attachments as $a){
		$post = (array) $a;
		$post['categories'] = 'fotos'; 
		$post['thumb'] = wp_get_attachment_image_src($a->ID);
		$attachments_url[] = $post;
	  }
  }
  $return['attachments'] = $attachments_url;
  $return['count_attachments'] = count($attachments);
  return $return;
}
*/
function filter_the_title( $title ) {
	if( is_page('noticias') && is_singular() && in_the_loop() || is_page('noticias-es') && is_singular() && in_the_loop()  ){
		global $post;
		$chapeu = get_post_meta($post->ID, 'chapeu');
		$custom_title = '';
		if($chapeu){
			$custom_title .= '<span class="chapeu">'.$chapeu[0].'</span>';
		}
		$custom_title .= $title;
		$title = $custom_title;
	}
	return $title;
}
add_filter( 'the_title' , 'filter_the_title' , 10);
function get_event_meta(){ ?>
	<script>
		let post_ids = [];
		jQuery('.tribe-events-loop .type-tribe_events').each(function(){
			if(jQuery(this).attr('id').split('post-')){
				post_ids.push(jQuery(this).attr('id').split('post-')[1])
			}
		})
		console.log(post_ids)
	</script>
	<?php
}
// Changes past event views to reverse chronological order
function tribe_past_reverse_chronological ($post_object) {
	$past_ajax = (defined( 'DOING_AJAX' ) && DOING_AJAX && $_REQUEST['tribe_event_display'] === 'past') ? true : false;
	if(tribe_is_past() || $past_ajax) {
		$post_object = array_reverse($post_object);
	}
	return $post_object;
}
add_filter('the_posts', 'tribe_past_reverse_chronological', 100);
/* Allow additional file type uploads */
function pn_filter_mime_types( $mime_types ) {
	//Add Additional Custom File Types
	$mime_types['mobi'] = 'application/x-mobipocket-ebook'; // Adding .svg file type extension
	$mime_types['epub'] = 'application/epub+zip'; // Adding .json file type extension
	//Remove Default File Types
	//unset( $mime_types['3gp'] );  // Remove .3gp file type extension
	//unset( $mime_types['3g2'] ); // Remove .3g2 file type extension
	//Return Filtered Mime Types
	return $mime_types;
}
add_action( 'upload_mimes', 'pn_filter_mime_types' );

//adding new fields to the checkout form
add_action('woocommerce_after_order_notes', 'custom_checkout_field');
function custom_checkout_field($checkout)
{
	woocommerce_form_field('interesse', array(
		'type' => 'text',
		'required' => 'true',
		'class' => array(	'my-field-class form-row-wide') ,
		'label' => __('Qual seu interesse no tema do livro? (Conhecimento pessoal, acadêmico,
			formação em grupo...)') ,
		'placeholder' => __('Seu interesse') ,
	) ,	$checkout->get_value('interesse'));

	echo '<div id="custom_checkout_field"><h2>' . __('Organizações') . '</h2>';
	woocommerce_form_field('organizacao', array(
		'type' => 'radio',
		'required' => 'true',
		'options' => array( 'sim' => 'Sim', 'nao' => 'Não'),
		'required' => 'true',
		'class' => array(	'form-livro-radio form-row-wide') ,
		'label' => __('Você faz parte de alguma organização da sociedade civil?') ,
	) ,	$checkout->get_value('organizacao'));

	woocommerce_form_field('qual_organizacao', array(
		'type' => 'text',
		'class' => array(	'form-qual-organizacao form-hidden form-row-wide') ,
		'label' => __('Qual organização?') ,
		'placeholder' => __('organização') ,
	) ,	$checkout->get_value('qual_organizacao'));

	woocommerce_form_field('escola', array(
		'type' => 'radio',
		'required' => 'true',
		'options' => array( 'sim' => 'Sim', 'nao' => 'Não'),
		'required' => 'true',
		'class' => array(	'form-livro-radio form-row-wide') ,
		'label' => __('Você faz parte de escolas ou bibliotecas comunitárias?') ,
	), $checkout->get_value('escola'));

	woocommerce_form_field('qual_escola', array(
		'type' => 'text',
		'class' => array(	'form-qual-escola form-hidden form-row-wide') ,
		'label' => __('Qual escola ou biblioteca?') ,
		'placeholder' => __('escola ou biblioteca') ,
	) ,	$checkout->get_value('qual_escola'));

	woocommerce_form_field('jornalista', array(
		'type' => 'radio',
		'options' => array( 'sim' => 'Sim', 'nao' => 'Não'),
		'required' => 'true',
		'class' => array(	'form-livro-radio form-row-wide') ,
		'label' => __('Você faz é jornalista?') ,
	) ,	$checkout->get_value('jornalista'));

	woocommerce_form_field('veiculo', array(
		'type' => 'text',
		'class' => array(	'form-qual-jornalista form-hidden form-row-wide') ,
		'label' => __('Qual veículo?') ,
		'placeholder' => __('Veículo') ,
	) ,	$checkout->get_value('veiculo'));
	
	echo '</div>';
}
add_action('wp_footer', 'popsolutions_add_script_wp_footer');
function popsolutions_add_script_wp_footer() {
	?>
	<script>
		jQuery(document).ready(function($) {
			//$('input:radio[name="organizacao"]').change(function(){
				$('.form-livro-radio input:radio').click(function(){
				//alert( $(this).is(':checked')+ ' ' +$(this).val()+' '+$(this).attr('name') );
				var origen = $(this).attr('name');
				if ($(this).is(':checked') && $(this).val() == 'sim' ) {
					$('.form-qual-'+origen).slideDown();
				}else{
					$('.form-qual-'+origen).slideUp();
				}
			});
			});

		</script>
		<?php
	}

/**
 * 2. Process the checkout - We then need to validate the field. If someone does not fill out the field they will get an error message.
 */

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta( $order_id ) {
    // Check if set, if its not set add an error.
	if ( ! $_POST['interesse'] ){
		wc_add_notice( __( 'O campo ainda precisa ser preenchido: Seu interesse' ), 'error' );
	}else{
		update_post_meta( $order_id, 'interesse', esc_attr($_POST['interesse']));
	}
	if ( ! $_POST['organizacao'] ){
		wc_add_notice( __( 'O campo ainda precisa ser preenchido: Você faz parte de alguma organização da sociedade civil?' ), 'error' );
	}else{
		if($_POST['organizacao'] == 'sim' && !$_POST['qual_organizacao']){
			wc_add_notice( __( 'O campo ainda precisa ser preenchido: Qual organização da sociedade civil?' ), 'error' );
		}else{
			update_post_meta( $order_id, 'qual_organizacao', esc_attr($_POST['qual_organizacao']));
		}
		update_post_meta( $order_id, 'organizacao', esc_attr($_POST['organizacao']));
	}
	if ( ! $_POST['escola'] ){
		wc_add_notice( __( 'O campo ainda precisa ser preenchido: Você faz parte de escolas ou bibliotecas comunitárias?' ), 'error' );
	}else{
		if($_POST['escola'] == 'sim' && !$_POST['qual_escola']){
			wc_add_notice( __( 'O campo ainda precisa ser preenchido: Qual escola ou biblioteca comunitaria?' ), 'error' );
		}else{
			update_post_meta( $order_id, 'qual_escola', esc_attr($_POST['qual_escola']));
		}
		update_post_meta( $order_id, 'escola', esc_attr($_POST['escola']));
	}
	if ( ! $_POST['jornalista'] ){
		wc_add_notice( __( 'O campo ainda precisa ser preenchido: Você faz é jornalista?' ), 'error' );
	}else{
		if($_POST['jornalista'] == 'sim' && !$_POST['veiculo']){
			wc_add_notice( __( 'O campo ainda precisa ser preenchido: Qual veiculo?' ), 'error' );
		}else{
			update_post_meta( $order_id, 'veiculo', esc_attr($_POST['veiculo']));
		}
		update_post_meta( $order_id, 'jornalista', esc_attr($_POST['jornalista']));
	}
}

/**
 * 3. Display field value on the order edit page.
 */

function my_custom_checkout_field_display_admin_order_meta($order){
	echo '<p><strong>'.__('Seu interesse').':</strong> ' . get_post_meta( $order->id, 'interesse', true ) . '</p>';
	echo '<p><strong>'.__('Você faz parte de alguma organização da sociedade civil?').':</strong> ' . get_post_meta( $order->id, 'organizacao', true ) . '</p>';
	echo '<p><strong>'.__('Qual organização da sociedade civil?').':</strong> ' . get_post_meta( $order->id, 'qual_organizacao', true ) . '</p>';
	echo '<p><strong>'.__('Você faz parte de escolas ou bibliotecas comunitárias?').':</strong> ' . get_post_meta( $order->id, 'escola', true ) . '</p>';
	echo '<p><strong>'.__('Qual escolas ou bibliotecas comunitárias?').':</strong> ' . get_post_meta( $order->id, 'qual_escola', true ) . '</p>';
	echo '<p><strong>'.__('Você é jornalista?').':</strong> ' . get_post_meta( $order->id, 'jornalista', true ) . '</p>';
	echo '<p><strong>'.__('Qual veículo?').':</strong> ' . get_post_meta( $order->id, 'veiculo', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_plugin_body_class($classes) {
	if ( is_product() ) {
		$classes[] = 'single-livro';
		return $classes;
	}
}

add_filter('body_class', 'my_plugin_body_class');

/* show cart if not empty */
add_filter( 'generate_woocommerce_menu_item_location', 'tu_hide_empty_cart_icon' );
function tu_hide_empty_cart_icon( $location ) {
	if ( class_exists( 'WooCommerce' ) && sizeof( WC()->cart->get_cart() ) > 0 ) {
		return $location;
	}

	return 'none';
}

// Woo Sidecart - hide when empty
add_action( 'wp_footer', function() {
	if ( WC()->cart->is_empty() ) {
		echo '<style type="text/css">.menu-cart{ display: none; }</style>';
	}
});

add_filter( 'woocommerce_get_price_html', 'pop_dobke_remove_price');
function pop_dobke_remove_price($price){     
	return ;
}

add_filter( 'woocommerce_order_button_text', 'pop_dobke_custom_button_text' );

function pop_dobke_custom_button_text( $button_text ) {
   return 'Finalizar pedido'; // new text is here 
 }

 function woocommerce_button_proceed_to_checkout() { ?>
 	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
 		<?php esc_html_e( 'Fazer pedido', 'woocommerce' ); ?>
 	</a>
 	<?php
 }


/**
 * When an item is added to the cart, remove other products
 */

/*
function pop_dobke_change_add_success_text(){
	$message = 'O livro adicionado no seu pedido. <a href="/biblioteca" class="wc-forward">Adicionar outro livro.</a>';
	return $message;
}
function pop_dobke_empty_cart( $valid, $product_id, $quantity ) {

    if( ! empty ( WC()->cart->get_cart() ) && $valid ){
        WC()->cart->empty_cart();
        add_filter('wc_add_to_cart_message_html','pop_dobke_change_add_success_text');
    }

    return $valid;

}
add_filter( 'woocommerce_add_to_cart_validation', 'pop_dobke_empty_cart', 10, 3 );
*/

function pop_dobke_change_add_success_text(){
	$message = 'O livro foi adicionado no seu pedido. <a href="/biblioteca" class="wc-forward">Adicionar outro livro.</a>';
	return $message;
}
add_filter('wc_add_to_cart_message_html','pop_dobke_change_add_success_text');



function cetweb_print_custom_menu_shortcode($atts)
{
      // Normalize 
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	$atts = array_map('sanitize_text_field', $atts);
      // Attributes
	$menu_name = $atts['name'];
	$menu_class = $atts['class'];
	return wp_nav_menu(array(
		'menu' => esc_attr($menu_name),
		'menu_class' => 'menu ' . esc_attr($menu_class),
		'echo' => false));
}
add_shortcode('print-menu', 'cetweb_print_custom_menu_shortcode');
