<?php
/*
Plugin Name: Unidress
*/

function wpt_event_post_type() {
	$labels = array(
		'name'               => __( 'Campaigns' ),
		'singular_name'      => __( 'Event' ),
		'add_new'            => __( 'Add New Event' ),
		'add_new_item'       => __( 'Add New Event' ),
		'edit_item'          => __( 'Edit Event' ),
		'new_item'           => __( 'Add New Event' ),
		'view_item'          => __( 'View Event' ),
		'search_items'       => __( 'Search Event' ),
		'not_found'          => __( 'No events found' ),
		'not_found_in_trash' => __( 'No events found in trash' )
	);
	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'comments',
		'revisions',
	);
	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'events' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-images-alt2',
		'register_meta_box_cb' => function(){
			add_meta_box(
				'wpt_events_location',
				'Event Location',
				function(){uni_output_list($labels['name']);}, /* the function */
				$labels['name'],
				'side',
				'default'
				);
			}
	);
	register_post_type( $labels['name'], $args );
	// branch
	$labels = array(
		'name'               => __( 'Branches' ),
		'singular_name'      => __( 'Branch' ),
		'add_new'            => __( 'Add New Branch' ),
		'add_new_item'       => __( 'Add New Branch' ),
		'edit_item'          => __( 'Edit Branch' ),
		'new_item'           => __( 'Add New Branch' ),
		'view_item'          => __( 'View Branch' ),
		'search_items'       => __( 'Search Branch' ),
		'not_found'          => __( 'No branches found' ),
		'not_found_in_trash' => __( 'No branches found in trash' )
	);
	$args['labels']= $labels;
	$args['menu_icon']='dashicons-external';
	$args['register_meta_box_cb']= function(){
			add_meta_box(
				'wpt_events_location',
				'Project of branch',
				function(){uni_output_list('Projectes');}, /* the function */
				$labels['name'],
				'side',
				'default'
				);
			};
	register_post_type( $labels['name'], $args );
	
	// project
	$labels = array(
		'name'               => __( 'Projectes' ),
		'singular_name'      => __( 'Project' ),
		'add_new'            => __( 'Add New Project' ),
		'add_new_item'       => __( 'Add New Project' ),
		'edit_item'          => __( 'Edit Project' ),
		'new_item'           => __( 'Add New Project' ),
		'view_item'          => __( 'View Project' ),
		'search_items'       => __( 'Search Project' ),
		'not_found'          => __( 'No Projectes found' ),
		'not_found_in_trash' => __( 'No Projectes found in trash' )
	);
	$args['labels']= $labels;
	$args['menu_icon']='dashicons-welcome-widgets-menus';
	$args['register_meta_box_cb']= function(){
			add_meta_box(
				'wpt_events_location2',
				'Customer\'s project',
				function(){uni_output_list('Customers');}, /* the function */
				$labels['name'],
				'side',
				'default'
				);
			};
	register_post_type( $labels['name'], $args );
	
	// customer
	$labels = array(
		'name'               => __( 'Customers' ),
		'singular_name'      => __( 'Customer' ),
		'add_new'            => __( 'Add New Customer' ),
		'add_new_item'       => __( 'Add New Customer' ),
		'edit_item'          => __( 'Edit Customer' ),
		'new_item'           => __( 'Add New Customer' ),
		'view_item'          => __( 'View Customer' ),
		'search_items'       => __( 'Search Customer' ),
		'not_found'          => __( 'No Customeres found' ),
		'not_found_in_trash' => __( 'No Customeres found in trash' )
	);
	$args['labels']= $labels;
	$args['menu_icon']='dashicons-format-image';
	$args['register_meta_box_cb']= function(){
			add_meta_box(
				'wpt_events_location',
				'Priority Customer Number',
				function(){echo '<input type="text" name="location" value="' . esc_textarea( $location )  . '" class="widefat">';}, /* the function */
				$labels['name'],
				'side',
				'default'
				);
			};
	register_post_type( $labels['name'], $args );
}
add_action( 'init', 'wpt_event_post_type' );



/**
 * Output the HTML for the metabox.
 */
function uni_output_list($test) {
	global $post;
	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'event_fields' );
	// Get the location data if it's already been entered
	$location = get_post_meta( $post->ID, 'location', true );
	// Output the field
	//echo '<input type="text" name="location" value="' . esc_textarea( $location )  . '" class="widefat">';
	//echo '<select name="location" class="widefat">  <option value="" selected disabled hidden>'. esc_textarea( $location )  .'</option>  <option value="volvo">Volvo</option>  <option value="saab">Saab</option>  <option value="opel">Opel</option>  <option value="audi">Audi</option></select>';
	
    
	$list = '<select name="location" class="widefat">  <option value="" selected disabled hidden>'. esc_textarea( get_the_title($location) )  .'</option>';
	
	$loop = new WP_Query( array( 'post_type' => $test, 'posts_per_page' => 10 ) ); 
	while ( $loop->have_posts() ) : $loop->the_post(); 

    $titles .= '<option value="'.get_the_ID().'">'.get_the_title().'</option>';

    
	endwhile; 
	
	$list .= $titles;
	$list .= '</select>';
	
	echo $list;
}
/**
 * Save the metabox data
 */
function uni_save_events_meta( $post_id, $post ) {
	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}
	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	
	$meta = get_post_meta( $post_id );
	
	if ( ! isset( $_POST['location'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename(__FILE__) ) ) {
	//	return $post_id;
	}
	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $events_meta.
	$events_meta['location'] = esc_textarea( $_POST['location'] );
	// Cycle through the $events_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
	
	
	foreach ( $events_meta as $key => $value ) :
		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}
		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}
		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}
	endforeach;
}
add_action( 'save_post', 'uni_save_events_meta', 1, 2 );
