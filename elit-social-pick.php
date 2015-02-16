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





  $json_str = 
    '{ 
        "created_at": "Fri Feb 13 22:03:26 +0000 2015", 
        "id": 566357002251939840, 
        "id_str": "566357002251939840", 
        "text": "LUCOM celebrates National Heart Awareness Month, hosts “Wear Red” event. #NationalSOMA @AOAforDOs #LU http://t.co/LW49uuHgbL", 
        "source": "<a href=\"http://twitter.com\" rel=\"nofollow\">Twitter Web Client</a>", 
        "truncated": false, 
        "in_reply_to_status_id": null, 
        "in_reply_to_status_id_str": null, 
        "in_reply_to_user_id": null, 
        "in_reply_to_user_id_str": null, 
        "in_reply_to_screen_name": null, 
        "user": { 
            "id": 1702687202, 
            "id_str": "1702687202", 
            "name": "LUCOM", 
            "screen_name": "LibertyMedicine", 
            "location": "Lynchburg, VA", 
            "profile_location": null, 
            "description": "Liberty University College of Osteopathic Medicine (LUCOM) - Producing skilled physicians that are committed to Christian service in a secular world.", 
            "url": "http://t.co/7bup0pTlZb", 
            "entities            "url": { 
                    "urls": [ 
                        { 
                            "url": "http://t.co/7bup0pTlZb", 
                            "expanded_url": "http://www.Liberty.edu/LUCOM", 
                            "display_url": "Liberty.edu/LUCOM", 
                            "indices": [ 
                                0, 
                                22 
                            ] 
                        } 
                    ] 
                }, 
                "description": { 
                    "urls": [] 
                } 
            }, 
            "protected": false, 
            "followers_count": 130, 
            "friends_count": 22, 
            "listed_count": 5, 
            "created_at": "Mon Aug 26 18:58:51 +0000 2013", 
            "favourites_count": 0, 
            "utc_offset": -14400, 
            "time_zone": "Atlantic Time (Canada)", 
            "geo_enabled": false, 
            "verified": false, 
            "statuses_count": 84, 
            "lang": "en", 
            "contributors_enabled": false, 
            "is_translator": false, 
            "is_translation_enabled": false, 
            "profile_background_color": "C0DEED", 
            "profile_background_image_url": "http://pbs.twimg.com/profile_background_images/378800000060205491/c415fca8ce3deff2a9247ee74362c03a.jpeg", 
            "profile_background_image_url_https": "https://pbs.twimg.com/profile_background_images/378800000060205491/c415fca8ce3deff2a9247ee74362c03a.jpeg", 
            "profile_background_tile": true, 
            "profile_image_url": "http://pbs.twimg.com/profile_images/465835017109651456/-wOpiyb5_normal.jpeg", 
            "profile_image_url_https": "https://pbs.twimg.com/profile_images/465835017109651456/-wOpiyb5_normal.jpeg", 
            "profile_banner_url": "https://pbs.twimg.com/profile_banners/1702687202/1406726913", 
            "profile_link_color": "0084B4", 
            "profile_sidebar_border_color": "FFFFFF", 
            "profile_sidebar_fill_color": "C0DFEC", 
            "profile_text_color": "333333", 
            "profile_use_background_image": true, 
            "default_profile": false, 
            "default_profile_image": false, 
            "following": true, 
            "follow_request_sent": false, 
            "notifications": false 
        }, 
        "geo": null, 
        "coordinates": null, 
        "place": null, 
        "contributors": null, 
        "retweet_count": 1, 
        "favorite_count": 1, 
        "entities": { 
            "hashtags": [ 
                { 
                    "text": "NationalSOMA", 
                    "indices": [ 
                        73, 
                        86 
                    ] 
                }, 
                { 
                    "text": "LU", 
                    "indices": [ 
                        98, 
                        101 
                    ] 
                } 
            ], 
            "symbols": [], 
            "user_mentions": [ 
                { 
                    "screen_name": "AOAforDOs", 
                    "name": "AOA", 
                    "id": 273614983, 
                    "id_str": "273614983", 
                    "indices": [ 
                        87, 
                        97 
                    ] 
                } 
            ], 
            "urls": [ 
                { 
                    "url": "http://t.co/LW49uuHgbL", 
                    "expanded_url": "http://www.liberty.edu/lucom/index.cfm?PID=28248&MID=146842", 
                    "display_url": "liberty.edu/lucom/index.cf…", 
                    "indices": [ 
                        102, 
                        124 
                    ] 
                } 
            ] 
        }, 
        "favorited": false, 
        "retweeted": false, 
        "possibly_sensitive": false, 
        "lang": "en" 
     }';

  $json_tweet = get_tweet( $json_str );
  $tweet = new Elit_Tweet( $json_tweet );
  echo '<pre>'; var_dump( $tweet->screen_name ); echo '</pre>'; die(  );

  if ( is_tweet( $json_tweet ) ) {
    $tweet = format_tweet( $json_tweet );
  }









  // set the meta key
  $meta_key = 'elit_social_pick_id';

  // get the meta value as a string
  $meta_value = get_post_meta( $post_id, $meta_key, true);

  // if a new meta value was added and there was no previous value, add it
  if ( $new_meta_value && $meta_value == '' ) {
    //add_post_meta( $post_id, 'elit_foo', 'bar');
    add_post_meta( $post_id, $meta_key, $new_meta_value, true);

    

  } elseif ($new_meta_value && $new_meta_value != $meta_value ) {
    // so the new meta value doesn't match the old one, so we're updating
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  } elseif ( $new_meta_value == '' && $meta_value) {
    // if there is no new meta value but an old value exists, delete it
    delete_post_meta( $post_id, $meta_key, $meta_value );

  }
}

/**
 * Get the Twitter id off a link to a tweet
 *
 */
function parse_link( $link ) {
  $link_arr = parse_url( $link );
  return array_pop( explode( '/', $link_arr['path'] ) );
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
 * Verifies whether 
 *
 */
function is_tweet( $tweet ) {

  return !isset( $tweet->errors );
  
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

/**
 * Get the tweet ready for display, including HTML markup of 
 * links, mentions, hashtags.
 *
 * @return 
 */

  

