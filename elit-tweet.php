<?php  

/**
 * Elit Tweet
 *
 *
 * help from:
 * http://blog.jacobemerick.com/web-development/parsing-twitter-feeds-with-php/
 */
class Elit_Tweet
{
  private $tweet;
  private $post_id;
  private $screen_name;
  private $profile_image_url;
  private $profile_image_name;
  private $text;
  private $date;
  private $id;
  private $entity_holder;
  private $user_mentions;
  private $hashtags;
  private $urls;
  
  const HASHTAG_LINK_PATTERN = 
    '<a href="http://twitter.com/search?q=%%23%s&src=hash" class="social-pick-red__link" rel="nofollow" target="_blank">#%s</a>';

  const URL_LINK_PATTERN =
    '<a href="%s" class="social-pick-red__link" rel="nofollow" target="_blank" title="%s">%s</a>';

  const USER_MENTION_LINK_PATTERN =
    '<a href="http://twitter.com/%s" class="social-pick-red__link" rel="nofollow" target="_blank" title="%s">@%s</a>';
  
  /**
   * Build our Elit Tweet object.
   *
   * @param $json_str - a string of JSON, representing a tweet
   *            from the Twitter 1.1 REST API
   * @param $post_id - the ID of the Elit Social Pick post the tweet
                belongs go
   */
  public function __construct( $json_str, $post_id )
  {
    $tweet = json_decode( $json_str );
    
    $this->post_id = $post_id;
    $this->screen_name = $tweet->user->screen_name;
    $this->profile_image_url = 
      $this->format_profile_image_url( $tweet->user->profile_image_url );
    $this->profile_image_name = basename( $this->profile_image_url );
    $this->text = $tweet->text;
    $this->date = $this->format_date( $tweet->created_at );
    $this->id = $tweet->id_str;
    $this->entity_holder = array();

    $this->hashtags = $tweet->entities->hashtags;
    $this->user_mentions = $tweet->entities->user_mentions;
    $this->urls = $tweet->entities->urls;
    $this->format_body();
    $this->setup_attachment();
  }

  /**
   * Mark up the text of the tweet, with links for hashtags, urls and user mentions
   *
   */
  private function format_body() {
    $this->format_hashtags();
    $this->format_urls();
    $this->format_mentions();

    // we have to be sure to add the links starting from the rear 
    // and proceeding to the front
    krsort( $this->entity_holder );
    
    foreach ( $this->entity_holder as $entity ) {
      $this->text = substr_replace( 
        $this->text, 
        $entity->replace,
        $entity->start,
        $entity->length
      );
    }
  }

  /**
   * Add HTML markup for the tweet's hashtags
   *
   */
  private function format_hashtags() {
    foreach ( $this->hashtags as $hashtag ) {
      $entity = new stdClass();
      $entity->start = $hashtag->indices[0];
      $entity->end = $hashtag->indices[1];
      $entity->length = $hashtag->indices[1] - $hashtag->indices[0];
      $entity->replace = 
        sprintf( 
          self::HASHTAG_LINK_PATTERN,  
          strtolower( $hashtag->text ),
          $hashtag->text
        );

      $this->entity_holder[$entity->start] = $entity;
    }
  }

  /**
   * Add HTML markup for the tweet's urls
   *
   */
  private function format_urls() {
    foreach ( $this->urls as $url ) {
      $entity = new stdClass();
      $entity->start = $url->indices[0];
      $entity->end = $url->indices[1];
      $entity->length = $url->indices[1] - $url->indices[0];
      $entity->replace = sprintf(
        self::URL_LINK_PATTERN,
        $url->url,
        $url->expanded_url,
        $url->display_url
      );

      $this->entity_holder[$entity->start] = $entity;
    }
  }

  /**
   * Add HTML markup for the tweet's user mentions
   *
   */
  private function format_mentions() {
    foreach ( $this->user_mentions as $user_mention ) {
      $entity = new stdClass();
      $entity->start = $user_mention->indices[0];
      $entity->end = $user_mention->indices[1];
      $entity->length = $user_mention->indices[1] - $user_mention->indices[0];
      $entity->replace = sprintf(
        self::USER_MENTION_LINK_PATTERN,
        $user_mention->screen_name,
        $user_mention->name,
        $user_mention->screen_name
      );
      $this->entity_holder[$entity->start] = $entity;
    }
  }

  /**
   * Format the name of the profile image url, changing
   * _normal to _bigger in the filename so we can grab 
   * a bigger profile image
   *
   */
  private function format_profile_image_url( $url ) {
    $info = pathinfo( $url );
    return $info['dirname'] . '/' .
      str_replace( '_normal', '_bigger', $info['basename'] );
  }
  
  
  /**
   * Retrieve properties of the Elit Tweet object
   *
   * @param $prop - a string representing the property to access
   */
  public function __get( $prop ) {
    if ( property_exists( $this, $prop ) ) {
      return $this->$prop;
    }
  }

  /**
   * Download profile image, if necessary, and set the profile
   * image as the thumbnail of the elit_social_pick post
   * 
   */
  private function setup_attachment() {
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $upload_dir = wp_upload_dir();
    $image_path = $upload_dir['path'] . '/' . $this->profile_image_name;
    $image_url = $upload_dir['url'] . '/' . $this->profile_image_name;

    // make sure we don't already have the file before downloading it
    if ( !file_exists( $image_path ) ) {
      $image = media_sideload_image( 
        $this->profile_image_url, 
        $this->post_id,
        'Twitter profile image for ' . $this->screen_name
      );
    }

    $image_id = $this->elit_get_attachment_id_by_url( $image_url );
    set_post_thumbnail( $this->post_id, $image_id );
  }

  private function format_date( $date ) {
    // our AP stylified months
    $months = array( 
      "", "Jan.", "Feb.", "March", "April", "May", "June", "July", 
      "Aug.", "Sept.", "Oct.", "Nov.", "Dec." 
    );

    $str_date = strtotime( $date );
    $month_num = date( 'n', $str_date );
    return sprintf( 
      '%1$s %2$s', 
      $months[$month_num], 
      date( 'j' ) 
    );
  }

  // code slavishly copied from fjarret:
  // https://gist.github.com/fjarrett/5544469
  /**
   * Return the ID of an attachment by serach the db with the file URL
   *
   * code slavishly copied from fjarret:
   * https://gist.github.com/fjarrett/5544469
   * @param string $url the url of the image
   * @return int | null  - returns an attachment ID or null 
   */
  private function elit_get_attachment_id_by_url( $url ) {
    
    // split the $url into 2 parts, 
    // the first being the url of the site
    // the second being '/uploads/<year>/<month>/<filename>
    $parsed_url = 
      explode ( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

    if ( !isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || 
      ( $this_host != $file_host) ) {
      return;
    }

    // search the db for any attachment guid with a partial path match
    global $wpdb;

    $attachment = $wpdb->get_col(
      $wpdb->prepare(
       "SELECT ID
        FROM {$wpdb->prefix}posts
        WHERE guid RLIKE %s;
       ", $parsed_url[1] 
      )
    );

    return $attachment[0];
  }
}
  
