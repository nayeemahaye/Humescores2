<?php

define( 'calafate_pci_iplhd', get_template_directory_uri() . '/images/blank-product-mini.jpg' );

add_action('admin_init', 'calafate_pci_init');
function calafate_pci_init() {
	$calafate_pci_taxonomies = get_taxonomies( array( 'name' => 'portfolio_category' ) );
	if (is_array($calafate_pci_taxonomies)) {
	    foreach ($calafate_pci_taxonomies as $calafate_pci_taxonomy) {
      add_action($calafate_pci_taxonomy.'_add_form_fields', 'calafate_pci_add_texonomy_field');
			add_action($calafate_pci_taxonomy.'_edit_form_fields', 'calafate_pci_edit_texonomy_field');
			add_filter( 'manage_edit-' . $calafate_pci_taxonomy . '_columns', 'calafate_pci_taxonomy_columns' );
			add_filter( 'manage_' . $calafate_pci_taxonomy . '_custom_column', 'calafate_pci_taxonomy_column', 10, 3 );
    }
	}
}

function calafate_pci_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}

// add image field in add form
function calafate_pci_add_texonomy_field() {

	wp_enqueue_media();
	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . esc_html__('Image', 'calafate') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="calafate_pci_upload_image_button button">' . esc_html__('Upload/Add image', 'calafate') . '</button>
	</div>'.calafate_pci_script();
}

// add image field in edit form
function calafate_pci_edit_texonomy_field($taxonomy) {

	wp_enqueue_media();
	
	if (calafate_pci_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == calafate_pci_iplhd) 
		$image_url = "";
	else
		$image_url = calafate_pci_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . esc_html__('Image', 'calafate') . '</label></th>
		<td><img class="taxonomy-image" src="' . calafate_pci_taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '" style="max-width: 200px;" /><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_url.'" /><br />
		<button class="calafate_pci_upload_image_button button">' . esc_html__('Upload/Add image', 'calafate') . '</button>
		<button class="calafate_pci_remove_image_button button">' . esc_html__('Remove image', 'calafate') . '</button>
		</td>
	</tr>'.calafate_pci_script();
}

// upload using wordpress upload
function calafate_pci_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".calafate_pci_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".calafate_pci_remove_image_button").click(function() {
				$(".taxonomy-image").attr("src", "'.calafate_pci_iplhd.'");
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . calafate_pci_iplhd . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

				if (thumb != "' . calafate_pci_iplhd . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb);
			});
	    });
	</script>';
}

// save our taxonomy image while edit or save term
add_action('edit_term','calafate_pci_save_taxonomy_image');
add_action('create_term','calafate_pci_save_taxonomy_image');
function calafate_pci_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        update_option('calafate_pci_taxonomy_image'.$term_id, $_POST['taxonomy_image'], NULL);
}

// get attachment ID by image url
function calafate_pci_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

// get taxonomy image url for the given term_id (Place holder image by default)
function calafate_pci_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    $taxonomy_image_url = get_option('calafate_pci_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = calafate_pci_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id)) {
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : calafate_pci_iplhd;
	else
		return $taxonomy_image_url;
}

function calafate_pci_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="calafate_pci_upload_image_button button">' . esc_html__('Upload/Add image', 'calafate') . '</button>
					<button class="calafate_pci_remove_image_button button">' . esc_html__('Remove image', 'calafate') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

/**
 * Thumbnail column added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function calafate_pci_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = esc_html__('Image', 'calafate');

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}

/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function calafate_pci_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . calafate_pci_taxonomy_image_url($id, 'thumbnail', TRUE) . '" alt="' . esc_html__('Thumbnail', 'calafate') . '" class="wp-post-image" /></span>';
	
	return $columns;
}

// Change 'insert into post' to 'use this image'
function calafate_pci_change_insert_button_text($safe_text, $text) {
    return str_replace("Insert into Post", "Use this image", $text);
}

// Style the image in category list
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action( 'admin_head', 'calafate_pci_add_style' );
	add_action('quick_edit_custom_box', 'calafate_pci_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "calafate_pci_change_insert_button_text", 10, 2);
}

// Register plugin settings
function calafate_pci_register_settings() {
	register_setting('zci_options', 'zci_options', 'calafate_pci_options_validate');
	add_settings_section('zci_settings', esc_html__('Categories Images settings', 'calafate'), 'calafate_pci_section_text', 'zci-options');
	add_settings_field('calafate_pci_excluded_taxonomies', esc_html__('Excluded Taxonomies', 'calafate'), 'calafate_pci_excluded_taxonomies', 'zci-options', 'zci_settings');
}

// Settings section description
function calafate_pci_section_text() {
	echo '<p>'.esc_html__('Please select the taxonomies you want to exclude it from Categories Images plugin', 'calafate').'</p>';
}

// Excluded taxonomies checkboxs
function calafate_pci_excluded_taxonomies() {
	$options = get_option('zci_options');
	$disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
	foreach (get_taxonomies() as $tax) : if (in_array($tax, $disabled_taxonomies)) continue; ?>
		<input type="checkbox" name="zci_options[excluded_taxonomies][<?php echo $tax ?>]" value="<?php echo $tax ?>" <?php checked(isset($options['excluded_taxonomies'][$tax])); ?> /> <?php echo $tax ;?><br />
	<?php endforeach;
}

// Validating options
function calafate_pci_options_validate($input) {
	return $input;
}

// Plugin option page
function zci_options() {
	if (!current_user_can('manage_options'))
		wp_die(esc_html__( 'You do not have sufficient permissions to access this page.', 'calafate'));
		$options = get_option('zci_options');
	?>
	<div class="wrap">
		<h2><?php esc_html_e('Categories Images', 'calafate'); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('zci_options'); ?>
			<?php do_settings_sections('zci-options'); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

// display taxonomy image for the given term_id
function calafate_pci_taxonomy_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    $taxonomy_image_url = get_option('calafate_pci_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = calafate_pci_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id))
	    	$taxonomy_image = wp_get_attachment_image($attachment_id, $size, FALSE, $attr);
	    else {
	    	$image_attr = '';
	    	if(is_array($attr)) {
	    		if(!empty($attr['class']))
	    			$image_attr .= ' class="'.$attr['class'].'" ';
	    		if(!empty($attr['alt']))
	    			$image_attr .= ' alt="'.$attr['alt'].'" ';
	    		if(!empty($attr['width']))
	    			$image_attr .= ' width="'.$attr['width'].'" ';
	    		if(!empty($attr['height']))
	    			$image_attr .= ' height="'.$attr['height'].'" ';
	    		if(!empty($attr['title']))
	    			$image_attr .= ' title="'.$attr['title'].'" ';
	    	}
	    	$taxonomy_image = '<img src="'.$taxonomy_image_url.'" '.$image_attr.'/>';
	    }
	}

	if ($echo)
		echo $taxonomy_image;
	else
		return $taxonomy_image;

}