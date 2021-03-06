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
  private $created_at;
  private $date;
  private $id;
  private $entity_holder;
  private $user_mentions;
  private $hashtags;
  private $media;
  private $urls;
  
  const HASHTAG_LINK_PATTERN = 
    '<a href="http://twitter.com/search?q=%%23%s&src=hash" class="social-pick-red__link" rel="nofollow" target="_blank">#%s</a>';

  const URL_LINK_PATTERN =
    '<a href="%s" class="social-pick-red__link" rel="nofollow" target="_blank" title="%s">%s</a>';

  const USER_MENTION_LINK_PATTERN =
    '<a href="http://twitter.com/%s" class="social-pick-red__link" rel="nofollow" target="_blank" title="%s">@%s</a>';
  
  const MEDIA_LINK_PATTERN =
    '<a href="%s" class="social-pick-red__link" rel="nofollow" target="_blank" title="Attached media">@%s</a>';
  
  /**
   * Build our Elit Tweet object.
   *
   * @param $json_str - a string of JSON, representing a tweet
   *            from the Twitter 1.1 REST API
   */
  public function __construct( $json_str )
  {
    $this->tweet = json_decode( $json_str );
    $this->entity_holder = array();
  }

  public function init($post_id) {
    $this->set_post_id( $post_id );
    $this->set_screen_name( $this->tweet->user->screen_name );
    $this->set_profile_image_url( $this->tweet->user->profile_image_url );
    $this->set_profile_image_name( 
        basename( $this->profile_image_url )
    );

    // Reflect Twitter's new extended mode 2016-10-20
    //$this->set_text( $this->tweet->text );
    $this->set_text( $this->tweet->full_text ); 

    $this->set_created_at( $this->tweet->created_at );
    $this->set_date( 
        $this->format_date( 
            $this->tweet->created_at 
        )
    );
    $this->set_id( $this->tweet->id_str );
    $this->set_hashtags( $this->tweet->entities->hashtags );
    $this->set_user_mentions( $this->tweet->entities->user_mentions );
    $this->set_urls( $this->tweet->entities->urls );
    $this->set_media( $this->tweet->entities->media );

    $this->format_body();
    $this->setup_attachment();
  }

  /**
   * Set the ID of the Elit Social Pick custom post type
   *
   * @param $post_id - the ID of the Elit Social Pick post the tweet
                belongs go
   * @return void
   * @author PJ
   */
  public function set_post_id( $post_id ) {
    $this->post_id = $post_id;
  }

  public function set_screen_name( $screen_name ) {
    $this->screen_name = $screen_name;
  }

  public function set_profile_image_url( $profile_image_url ) {
    $this->profile_image_url = 
      $this->format_profile_image_url( $profile_image_url );
  }

  public function set_profile_image_name( $profile_image_name ) {
    $this->profile_image_name = $profile_image_name;
  }

  // be sure to replace any &nbsp; entities that may be in the tweet.
  // Ex. 626125868247552000
  // See chudadie's comment in the accepted answer:
  // http://stackoverflow.com/questions/6275380/
  //       does-html-entity-decode-replaces-nbsp-also-if-not-how-to-replace-it
  public function set_text( $text ) {
    //$text_raw = html_entity_decode($text);
    //$this->text = str_replace("\xC2\xA0", ' ', $text_raw);
    $this->text = str_replace("\xC2\xA0", ' ', $text);
  }

  public function set_created_at( $created_at ) {
    $this->created_at = $created_at;
  }

  public function set_date( $date ) {
    $this->date = $date;
  }

  public function set_id( $id ) {
    $this->id = $id;
  }

  public function set_hashtags( $hashtags ) {
    $this->hashtags = $hashtags;
  }

  public function set_user_mentions( $user_mentions ) {
    $this->user_mentions = $user_mentions;
  }

  public function set_urls( $urls ) {
    $this->urls = $urls;
  }

  public function set_media( $media ) {
    $this->media = $media;
  }


  /**
   * Mark up the text of the tweet, with links for hashtags, urls and user mentions
   *
   */
  private function format_body() {
    $this->format_hashtags( $this->hashtags );
    $this->format_urls();
    $this->format_mentions();
    $this->format_media();

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
  public function format_hashtags( $hashtags ) {
    foreach ( $hashtags as $hashtag ) {
      $entity = $this->format_hashtag( $hashtag );
      $this->entity_holder[$entity->start] = $entity;
    }
  }

  public function format_hashtag( $hashtag ) {
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
      return $entity;
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
   * Add HTML markup for the tweet's media attachments
   *
   */
  private function format_media() {
    foreach ( $this->media as $media ) {
      $entity = new stdClass();
      $entity->start = $media->indices[0];
      $entity->end = $media->indices[1];
      $entity->length = $media->indices[1] - $media->indices[0];
      $entity->replace = sprintf(
        self::MEDIA_LINK_PATTERN,
        $media->url,
        $media->display_url
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
    $formatted_url = $info['dirname'] . '/' .
      str_replace( '_normal', '_bigger', $info['basename'] );
    return $formatted_url;
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

    //if ( !file_exists( $image_path ) ) {
      $image = media_sideload_image( 
        $this->profile_image_url, 
        $this->post_id,
        'Twitter profile image for ' . $this->screen_name,
        'src'
      );
    //}

    //$image_id = $this->elit_get_attachment_id_by_url( $image_url );
    $image_id = $this->elit_get_attachment_id_by_url( $image );
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
      date( 'j', $str_date ) 
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
  
