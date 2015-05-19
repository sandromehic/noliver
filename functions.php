<?php
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
	function theme_enqueue_styles() {
  	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	}

	add_action( 'init', 'create_post_type' );

	function create_post_type() {
		register_post_type( 'isf_event',
			array(
				'labels' => array(
					'name' => __( 'Events' ),
					'singular_name' => __( 'Event' ),
					'add_new'            => _x( 'Add New', 'event' ),
					'add_new_item'       => __( 'Add New Event' ),
					'edit_item'          => __( 'Edit Event' ),
					'new_item'           => __( 'New Event' ),
					'all_items'          => __( 'All Events' ),
					'view_item'          => __( 'View Event' ),
					'search_items'       => __( 'Search Events' ),
					'not_found'          => __( 'No event found' ),
					'not_found_in_trash' => __( 'No events found in the Trash' ),
					'parent_item_colon'  => '',
					'menu_name'          => 'Events'
				),
				'public' => true,
				'taxonomies' => array('category'),
				'has_archive' => true,
				'menu_position' => 5,
				'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			)
		);
	}

	add_action( 'add_meta_boxes', 'event_date_box' );

	function event_date_box() {
    add_meta_box(
        'event_date_box',
        __( 'Event date', 'myplugin_textdomain' ),
        'event_date_box_content',
        'isf_event',
        'side',
        'high'
    );
	}

	function event_date_box_content( $post ) {
		wp_nonce_field( 'event_date_box_content', 'event_date_box_content_nonce' );

			/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value_date = get_post_meta( $post->ID, '_event_date_meta_value_key', true );
		echo '<label for="event_date">La data::</label>';
		echo '<input type="text" id="event_date" name="event_date" value="' . esc_attr( $value_date ) . '" />';
		$value_time = get_post_meta( $post->ID, '_event_time_meta_value_key', true );
		echo '<label for="event_time">Orario:</label>';
		echo '<input type="text" id="event_time" name="event_time" value="' . esc_attr( $value_time ) . '" />';
		$value_place = get_post_meta( $post->ID, '_event_place_meta_value_key', true );
		echo '<label for="event_place">Indirizzo:</label>';
		echo '<input type="text" id="event_place" name="event_place" value="' . esc_attr( $value_place ) . '" />';
	}

	function event_date_save_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['event_date_box_content_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['event_date_box_content_nonce'], 'event_date_box_content' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Make sure that it is set.
		if ( ! isset( $_POST['event_date'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data_date = sanitize_text_field( $_POST['event_date'] );
		$my_data_time = sanitize_text_field( $_POST['event_time'] );
		$my_data_place = sanitize_text_field( $_POST['event_place'] );

		$date = DateTime::createFromFormat('j/m/Y', $my_data_date);
		$date_stamp = date_format($date, 'U');

		// Update the meta field in the database.
		update_post_meta( $post_id, '_event_date_meta_value_key', $my_data_date );
		update_post_meta( $post_id, '_event_time_meta_value_key', $my_data_time );
		update_post_meta( $post_id, '_event_place_meta_value_key', $my_data_place );
		update_post_meta( $post_id, '_event_datestamp_meta_value_key', $date_stamp );
	}
	add_action( 'save_post', 'event_date_save_meta_box_data' );

	function isf_admin_styles_scripts() {
		wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'wp-jquery-date-picker', get_stylesheet_directory_uri() . '/js/admin.js' );
	}
	add_action('admin_enqueue_scripts', 'isf_admin_styles_scripts');
/*
function hkdc_admin_styles() {
    wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'hkdc_admin_styles');
function hkdc_admin_scripts() {
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'wp-jquery-date-picker', get_stylesheet_directory_uri() . '/js/admin.js' );
}
add_action('admin_enqueue_scripts', 'hkdc_admin_scripts');
*/
