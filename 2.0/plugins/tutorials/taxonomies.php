<?php
/**
 * Register taxonomies for use with the tutorials post type.
 */

add_action( 'init', 'tutorials_register_taxonomies', 11 );
function tutorials_register_taxonomies() {
	register_taxonomy( 'tutorial_tag', 'tutorials', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                  => __( 'Tags' ),
			'singular_name'         => __( 'Tag' ),
			'all_items'             => __( 'All Tags' ),
			'edit_item'             => __( 'Edit Tag' ),
			'update_item'           => __( 'Update Tag' ),
			'add_new_item'          => __( 'Add New Tag' ),
			'new_item_name'         => __( 'New Tag Name' ),
			'parent_item'           => __( 'Parent Tag' ),
			'parent_item_colon'     => __( 'Parent Tag:' ),
			'search_items'          => __( 'Search Tags' ),
			'popular_items'         => __( 'Popular Tags' ),
			'choose_from_most_used' => __( 'Choose from most-used tags' ),
		),
		'rewrite'           => array(
			'slug'         => 'tag',
			'hierarchical' => false,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );

	register_taxonomy( 'tool', 'tutorials', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Tools' ),
			'singular_name'         => __( 'Tool' ),
			'all_items'             => __( 'All Tools' ),
			'edit_item'             => __( 'Edit Tool' ),
			'update_item'           => __( 'Update Tool' ),
			'add_new_item'          => __( 'Add New Tool' ),
			'new_item_name'         => __( 'New Tool Name' ),
			'parent_item'           => __( 'Parent Tool' ),
			'parent_item_colon'     => __( 'Parent Tool:' ),
			'search_items'          => __( 'Search Tools' ),
			'popular_items'         => __( 'Popular Tools' ),
			'choose_from_most_used' => __( 'Choose from most-used tools' ),
		),
		'rewrite'           => array(
			'slug'         => 'tool',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );

	register_taxonomy( 'difficulty', 'tutorials', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Difficulties' ),
			'singular_name'         => __( 'Difficulty' ),
			'all_items'             => __( 'All Difficulties' ),
			'edit_item'             => __( 'Edit Difficulty' ),
			'update_item'           => __( 'Update Difficulty' ),
			'add_new_item'          => __( 'Add New Difficulty' ),
			'new_item_name'         => __( 'New Difficulty Name' ),
			'parent_item'           => __( 'Parent Difficulty' ),
			'parent_item_colon'     => __( 'Parent Difficulty:' ),
			'search_items'          => __( 'Search Difficulties' ),
			'popular_items'         => __( 'Popular Difficulties' ),
			'choose_from_most_used' => __( 'Choose from most-used difficulties' ),
		),
		'rewrite'           => array(
			'slug'         => 'difficulty',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );
	
	// Register Term Meta
	register_meta( 'term', 'icon', 'esc_url' );
}

// Term meta
add_action( 'admin_enqueue_scripts', 'anndl_tool_icon_script' );
function anndl_tool_icon_script() {
	// if ( add/edit tool page ) {
		wp_enqueue_media();
		wp_enqueue_script( 'tool-icons', plugins_url( '/user-image-select.js', __FILE__ ), array( 'jquery', 'media-views' ) );
	// }
}

add_action( 'tool_add_form_fields', 'anndl_tool_icon_field' );
function anndl_tool_icon_field() {
	wp_enqueue_media();
	wp_nonce_field( basename( __FILE__ ), 'anndl_tool_icon_nonce' ); ?>
	<div class="form-field jt-term-color-wrap">
		<label for="tool_icon"><?php _e( 'Tool Icon' ); ?></label>
		<button id="dl-tool-icon-btn" class="button">Select Image</button><br/>
		<img src="" id="dl-tool-icon-preview" style="cursor: pointer; max-width: 384px; margin-top: 18px;"/>
		<input type="hidden" name="tool_icon" id="dl-tool-icon-img" value=""/>
	</div>
<?php }

add_action( 'tool_edit_form_fields', 'anndl_tool_icon_field_edit' );
function anndl_tool_icon_field_edit( $term ) {
	wp_enqueue_media();
	wp_nonce_field( basename( __FILE__ ), 'anndl_tool_icon_nonce' ); 
	$img = esc_url( get_term_meta( $term->term_id, 'tool_icon', true ) );
	?>
	<tr class="form-field jt-term-color-wrap">
		<th scope="row"><label for="tool_icon"><?php _e( 'Tool Icon' ); ?></label></th>
		<td>
			<button id="dl-tool-icon-btn" class="button">Select Image</button><br/>
			<img src="<?php echo $img; ?>" id="dl-tool-icon-preview" style="cursor: pointer; max-width: 384px; margin-top: 18px;"/>
			<input type="hidden" name="tool_icon" id="dl-tool-icon-img" value="<?php echo $img; ?>"/>
		</td>
	</tr>
<?php }

add_action( 'edit_tool',   'anndl_save_tool_icon' );
add_action( 'create_tool', 'anndl_save_tool_icon' );

function anndl_save_tool_icon( $term_id ) {

	if ( ! isset( $_POST['anndl_tool_icon_nonce'] ) || ! wp_verify_nonce( $_POST['anndl_tool_icon_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	$new_icon = isset( $_POST['tool_icon'] ) ? esc_url( $_POST['tool_icon'] ) : '';
	update_term_meta( $term_id, 'tool_icon', $new_icon );
}

add_filter( 'manage_edit-tool_columns', 'anndl_edit_tool_columns' );
function anndl_edit_tool_columns( $columns ) {

    $columns['icon'] = __( 'Icon' );

    return $columns;
}

add_filter( 'manage_tool_custom_column', 'anndl_manage_tool_custom_column', 10, 3 );
function anndl_manage_tool_custom_column( $out, $column, $term_id ) {
	if ( 'icon' === $column ) {
		$icon = get_term_meta( $term_id, 'tool_icon', true );
		if ( ! $icon ) {
			$out = '';
		} else {
			$out = sprintf( '<img src="%s" height="48"/>', esc_url( $icon ) );
		}
	}
	return $out;
}

