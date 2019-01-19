<?php
/**
 * Shortcodes
 *
 * 
 *
 * @package drossel_devana
 */



/*
==================================================
PRODUCTS
==================================================
 */
function drossel_products( $atts, $content = null ) {
	
	//get the attributes
	$atts = shortcode_atts(
		array(
			'category' => ''
		),
		$atts,
		'products'
	);
	
	//return HTML
	ob_start();
	// include 'templates/newsletter.php';

	$args = array(
		'post_type' => 'products',
		'posts_per_page' => 5, 
		'post_status' => 'publish',
		'product_category' => $atts['category']
		// 'meta_key' => 'category',
		// 'meta_value' => 'svadobne',
	);

	// Get current page and append to custom query parameters array------------
	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$loop = new WP_Query($args);

	// Pagination fix-----------
	$temp_query = $wp_query;
	$wp_query   = NULL;
	$wp_query   = $loop;

	if ($loop->have_posts()):
		
	?>
	  <div>
		<?php
	while ($loop->have_posts()):
		$loop->the_post();
	
		if (has_post_thumbnail(get_the_ID())): $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
			?>
			<div class="img__wrap">
				<img class="img__img" src="<?php echo $image[0]; ?>">
				<div class="img__description_layer"> 
					<p class="img__description">
						<strong><?php echo get_the_title(); ?></strong><br>
						<?php echo get_post_meta( get_the_ID(), 'description', true ); ?><br>
						Veľkosť: <strong><?php echo get_post_meta( get_the_ID(), 'size', true ); ?></strong><br>
						Dostupnosť: <?php echo (get_post_meta( get_the_ID(), 'availability', true ) == 'yes' )? 'áno' : 'vypožičané';  ?><br>
						Výpožičné: <?php echo get_post_meta( get_the_ID(), 'price', true ); ?> €
					</p>
					
				</div>
			</div>
			<?php
		endif;
	
	endwhile;

	wp_reset_postdata();
	?>
	<nav class="pagination">
        <?php pagination_bar( $loop ); ?>
    </nav>
	<?php
	// Reset main query object---------------
	$wp_query = NULL;
	$wp_query = $temp_query;
	
	?>
	  </div>
	<?php
	else:
		esc_html_e('Nenašli sa žiadne produkty', 'text-domain');
	endif;
	

	return ob_get_clean();
	
}
add_shortcode( 'products', 'drossel_products' );

// --------------- shortcode products-----------------