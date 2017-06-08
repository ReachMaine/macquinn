<?php
/**** add meta-box for disable post thumbnail ****/
// things we need: meta-key (custom field name) here:  reach_disable_thumb
// unique id of meta box.

/* Also in content.php need to replace

if( !empty( $url )  ) {
	if (!is_single()  || (get_post_meta(get_the_ID(), 'reach_disable_thumb', true)  != 'yes')  )  {

?> */

add_action( 'load-post.php', 'reach_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'reach_post_meta_boxes_setup' );
/* Meta box setup function. */
function reach_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'reach_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'reach_save_post_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function reach_add_post_meta_boxes() {

  add_meta_box(
    'reach-disable-thumb',      // Unique ID
    esc_html__( 'Hide Featured Image', 'reach' ),    // Title of meta box
    'reach_post_meta_box',   // Callback function
    'post',         // Admin page (or post type)
    'side',         // Context (normal, advanced & side)
    'default'         // Priority
  );
}

/* Display the post meta box. */
function reach_post_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'reach_post_disable_thumb_nonce' );


    $reach_stored_meta = get_post_meta( $object->ID );
    //echo "all meta <pre>"; var_dump($reach_stored_meta); echo "</pre>";
    $meta_key = "reach_disable_thumb";
    $meta_value = get_post_meta( $object->ID, $meta_key, true );
    //echo "Meta value<pre>"; var_dump($reach_stored_meta); echo "</pre>";
?>

  <p>

    <input type="checkbox" name="reach-disable-thumb" id="reach-disable-thumb" value="yes" <?php if ( isset ( $reach_stored_meta['reach_disable_thumb'] ) ) checked( $reach_stored_meta['reach_disable_thumb'][0], 'yes' ); ?> />
    <label for="meta-checkbox">
        <?php _e( 'Hide featured image on single post', 'reach' )?>
    </label>
  </p>

<?php } // end of reach_post_class_meta function

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'reach_save_post_meta', 10, 2 );
/* Meta box setup function. */
function reach_save_post_meta( $post_id, $post ) {

   /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['reach_post_disable_thumb_nonce'] ) || !wp_verify_nonce( $_POST['reach_post_disable_thumb_nonce'], basename( __FILE__ ) ) )
    return $post_id;

    /* Get the post type object. */
      $post_type = get_post_type_object( $post->post_type );
    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['reach-disable-thumb'] ) ? sanitize_html_class( $_POST['reach-disable-thumb'] ) : '' );

    /* Get the meta key. */
     $meta_key = 'reach_disable_thumb';

     /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
}
/**** end of meta box. ****/
