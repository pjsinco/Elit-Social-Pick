<?php 
require_once( 'elit-tweet.php' );
/**
 * Plugin Name: Elit Social Pick
 * Description: Display a hand-picked tweet
 * Version: 0.0.1
 * Author: Patrick Sinco
 * 
 * Note: Keep twitter authentication settings in a separate file
 * in the same directory as this pluging. 
 * 
 */

add_action( 'init' , 'elit_social_pick_cpt' );

function elit_social_pick_cpt() {
  /**
   * SOCIAL PICK custom post type
   * 
   * For displaying the big image on the home page
   */
  $labels = array(
    'name'               => 'Social Pick',
    'singular_name'      => 'Social Pick',
    'menu_name'          => 'Social Pick',
    'name_admin_bar'     => 'Social Pick',
    'add_new'            => 'Add new Social Pick',
    'add_new_item'       => 'Add new Social Pick',
    'edit_item'          => 'Edit Social Pick',
    'view_item'          => 'View Social Pick',
    'all_items'          => 'All Social Picks',
    'search_items'       => 'Search Social Picks',
    'not_found'          => 'No Social Picks found',
    'not_found_in_trash' => 'No Social Picks found in trash.',
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 5,
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'social-pick'),
    'supports' => array( 'revision', 'thumbnail', 'custom_fields' ),
  );
  
  register_post_type( 'elit_social_pick', $args );
}

/**
 * SOCIAL PICK tweet id meta box
 *
 * Specify the background color for the Super's overlay
 */
add_action( 'load-post.php' , 'elit_social_pick_id_meta_box_setup' );
add_action( 'load-post-new.php' , 'elit_social_pick_id_meta_box_setup' );

function elit_social_pick_id_meta_box_setup() {
  add_action( 'add_meta_boxes' , 'elit_add_social_pick_id_meta_box' );
  add_action( 'save_post' , 'elit_save_social_pick_id_meta', 10, 2 );
}

function elit_add_social_pick_id_meta_box() {
  add_meta_box(
    'elit-social-pick-id',
    esc_html( 'The Twitter ID of the tweet' ),
    'elit_social_pick_id_meta_box',
    'elit_social_pick',
    'normal',
    'default'
  );
}

function elit_social_pick_id_meta_box( $object, $box ) {
  wp_nonce_field( basename(__FILE__), 'elit_social_pick_id_nonce' );
  $color = get_post_meta( $object->ID, 'elit_social_pick_id', true );
  ?>
  <p>
    <label for="widefat">Ex.: The ID is the big number at the end of the tweet link: 565911262505472000, for example.</label> 
    <br />
    <br />
    <input class="widefat" type="text" name="elit-social-pick-id" id="elit-social-pick-id" value="<?php echo esc_attr( get_post_meta( $object->ID, 'elit_social_pick_id', true ) ); ?>" />
  </p>
  <?php 
}

function elit_save_social_pick_id_meta( $post_id, $post ) {
  // verify the nonce
  if ( !isset( $_POST['elit_social_pick_id_nonce'] ) || 
    !wp_verify_nonce( $_POST['elit_social_pick_id_nonce'], basename( __FILE__ ) )
  ) {
      // instead of just returning, we return the $post_id
      // so other hooks can continue to use it
      return $post_id;
  }

  // get post type object
  $post_type = get_post_type_object( $post->post_type );

  // if the user has permission to edit the post
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
    return $post_id;
  }

  // get the posted data and sanitize it
  $new_meta_value = 
    ( isset($_POST['elit-social-pick-id'] ) ? $_POST['elit-social-pick-id'] : '' );

  // set the meta key
  $meta_key = 'elit_social_pick_id';

  // get the meta value as a string
  $meta_value = get_post_meta( $post_id, $meta_key, true);

  // if a new meta value was added and there was no previous value, add it
  if ( $new_meta_value && $meta_value == '' ) {
    //add_post_meta( $post_id, 'elit_foo', 'bar');
    add_post_meta( $post_id, $meta_key, $new_meta_value, true);

    // get our tweet to add;
    // make into a function?
    $json_tweet = get_tweet( $new_meta_value );
    if ( is_tweet( $json_tweet ) ) {
      $tweet = new Elit_Tweet( $json_tweet, $post_id );
      add_post_meta( 
        $post_id, 
        'elit_social_pick_tweet', 
        $tweet->text, 
        true
      );
      add_post_meta( 
        $post_id, 
        'elit_social_pick_screen_name', 
        $tweet->screen_name, 
        true
      );
      add_post_meta( 
        $post_id, 
        'elit_social_pick_date', 
        $tweet->date, 
        true
      );
      add_post_meta( 
        $post_id, 
        'elit_social_pick_profile_image_url', 
        $tweet->profile_image_url, 
        true
      );
    
      // see note about potential for infinite loop when calling from
      // a function that hooks into save_posts, which we are doing
      // http://codex.wordpress.org/Function_Reference/wp_update_post
      if ( !wp_is_post_revision( $post_id ) ) {
        // unhook this function so it doesn't loop infinitely
        remove_action( 'save_post', 'elit_save_social_pick_id_meta' );
  
        // now we can update the post with wp_update_post(), which 
        // also calls 'save_post'
        elit_social_pick_update_post_title( 
          $post_id, 
          $tweet->date, 
          $tweet->screen_name 
        );

        // rewire up 'save_post'
        add_action( 'save_post', 'elit_save_social_pick_id_meta' );
      }
      //elit_social_pick_update_post_title( 
        //$post_id, $tweet->date, $tweet->screen_name 
      //);
    }
    
  } elseif ($new_meta_value && $new_meta_value != $meta_value ) {
    // so the new meta value doesn't match the old one, so we're updating
    update_post_meta( $post_id, $meta_key, $new_meta_value );
    // get our tweet to add;
    // make into a function?
    $json_tweet = get_tweet( $new_meta_value );
    if ( is_tweet( $json_tweet ) ) {
      $tweet = new Elit_Tweet( $json_tweet, $post_id );
      update_post_meta( 
        $post_id, 
        'elit_social_pick_tweet', 
        $tweet->text
      );
      update_post_meta( 
        $post_id, 
        'elit_social_pick_screen_name', 
        $tweet->screen_name
      );
      update_post_meta( 
        $post_id, 
        'elit_social_pick_date', 
        $tweet->date
      );
      update_post_meta( 
        $post_id, 
        'elit_social_pick_profile_image_url', 
        $tweet->profile_image_url
      );

      // see note about potential for infinite loop when calling from
      // a function that hooks into save_posts, which we are doing
      // http://codex.wordpress.org/Function_Reference/wp_update_post
      if ( !wp_is_post_revision( $post_id ) ) {
        // unhook this function so it doesn't loop infinitely
        remove_action( 'save_post', 'elit_save_social_pick_id_meta' );
  
        // now we can update the post with wp_update_post(), which 
        // also calls 'save_post'
        elit_social_pick_update_post_title( $post_id, $tweet->date, $tweet->screen_name );

        // rewire up 'save_post'
        add_action( 'save_post', 'elit_save_social_pick_id_meta' );
      }
    }
  } elseif ( $new_meta_value == '' && $meta_value) {
    // if there is no new meta value but an old value exists, delete it
    delete_post_meta( $post_id, $meta_key, $meta_value );
    delete_post_meta( $post_id, 'elit_social_pick_id' );
    delete_post_meta( $post_id, 'elit_social_pick_tweet' );
    delete_post_meta( $post_id, 'elit_social_pick_screen_name' );
    delete_post_meta( $post_id, 'elit_social_pick_date' );
    delete_post_meta( $post_id, 'elit_social_pick_profile_image_url' );

    if ( !wp_is_post_revision( $post_id ) ) {
      remove_action( 'save_post', 'elit_save_social_pick_id_meta' );
      $args = array(
        'ID' => $post_id,
        'post_title' => 'Untitled',
      );
      wp_update_post( $args );
      add_action( 'save_post', 'elit_save_social_pick_id_meta' );
    }
        
  }
}

function get_tweet( $id ) {
  require_once( 'TwitterAPIExchange.php' );

  // get our authentication info
  require( dirname( __FILE__ ) . '/twitter-auth.php' );
  $url = 'https://api.twitter.com/1.1/statuses/show.json';
  $request_method = 'GET';
  $get_field = '?id=' . $id;
  
  $tweet = new TwitterAPIExchange( $settings );
  $tweet = $tweet->setGetField( $get_field );
  $tweet = $tweet->buildOauth( $url, $request_method );
  $tweet = $tweet->performRequest();

  return $tweet;
}

/**
 * Verifies whether we've got a tweet in hand
 *
 */
function is_tweet( $json_tweet ) {
  $tweet = json_decode( $json_tweet );
  return !(isset( $tweet->errors ) ); 
}

function parse_time($date) {
  date_default_timezone_set("GMT");

  $d = ( ( int ) gmdate( 'U' ) - strtotime( $date ) );

  $time_since = ( time( ) - strtotime( $date ) );

  $month = array( 
    "", "Jan.", "Feb.", "March", "April", "May", "June", "July", 
    "Aug.", "Sept.", "Oct.", "Nov.", "Dec." 
  );

  if ($d > 172800) {
    $d = 'Posted ' . $month[date("n", strtotime($date))] . ' ' . date("j", strtotime($date));
  } elseif ($d <= 172800 && $d > 86400) {
    $d = "Yesterday";
  } else if ($d > 7200) {
    $d = date("G", $time_since) . " hours ago";
  } else if ($d > 3600) {
    $d = date("G", $time_since) . " hour ago";
  } else if ($d > 120) {
    $d = date("i",  $time_since) . " minutes ago";
    // replaces '03' with '3'
    $d = str_replace('0', '', date("i", $time_since)) . " minutes ago";
  } else if ($d > 60) {
    $d = date("i", $time_since) . " minute ago";
  } else if ($d == 1) {
    $d = date("s", $time_since) . " second ago";
  } else {
    $d = date("s", $time_since) . " seconds ago";
  }
  return $d;
}

function elit_social_pick_update_post_title( $post_id, $post_date, $name ) {
    // we also need to to add the post title
    $date = date( 'l, jS', strtotime( $post_date ) );
    $args = array(
      'ID' => $post_id,
      'post_title' => sprintf( '@%1$s\'s tweet from %2$s', $name, $date ),
    );
    wp_update_post( $args );
}

