<?php  

/**
 * Elit Tweet
 *
 *
 * help from:
 * http://blog.jacobemerick.com/web-development/
 *   parsing-twitter-feeds-with-php/
 */
class Elit_Tweet
{
  private $tweet;
  private $post_id;
  private $screen_name;
  private $profile_image_url;
  private $text;
  private $date;
  private $id;
  private $entity_holder;
  private $user_mentions;
  private $hashtags;
  private $urls;
  
  const HASHTAG_LINK_PATTERN = 
    '<a href="http://twitter.com/search?q=%%23%s&src=hash" rel="nofollow" target="_blank">#%s</a>';

  const URL_LINK_PATTERN =
    '<a href="%s" rel="nofollow" target="_blank" title="%s">%s</a>';

  const USER_MENTION_LINK_PATTERN =
    '<a href="http://twitter.com/%s" rel="nofollow" target="_blank" title="%s">@%s</a>';
  
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
    
    $this->screen_name = $tweet->user->screen_name;
    $this->profile_image_url = $tweet->user->profile_image_url;
    $this->text = $tweet->text;
    $this->date = $tweet->created_at;
    $this->id = $tweet->id_str;
    $this->entity_holder = array();

    $this->hashtags = $tweet->entities->hashtags;
    $this->user_mentions = $tweet->entities->user_mentions;
    $this->urls = $tweet->entities->urls;
    $this->format_body();
    $this->download_image();
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
   * Retrieve properties of the Elit Tweet object
   *
   * @param $prop - a string representing the property to access
   */
  public function __get( $prop ) {
    if ( property_exists( $this, $prop ) ) {
      return $this->$prop;
    }
  }

//  private function download_image() {
//    // some code here from the wp codex
//    // http://codex.wordpress.org/Function_Reference/wp_handle_sideload
//    require_once( ABSPATH . 'wp-admin/includes/file.php' );
//
//    $timeout_seconds = 5;
//    $temp_file = download_url( $this->profile_image_url, $timeout_seconds );
//
//    if ( !is_wp_error( $temp_file ) ) {
//      
//      $file = array(
//        'name' => basename( $this->profile_image_url ),
//        'type' => 'image/jpg',
//        'tmp_name' => $temp_file,
//        'error' => 0,
//        'size' => filesize( $temp_file )
//      );
//      
//      $overrides = array(
//
//        // here, we're telling WP not to look for the POST form fields
//        // that would normally be present; we downloaded the file from
//        // a remote server, so there are no form fields
//        'test_form' => false,
//
//        // we don't want wp to allow empty files
//        'test_size' => true,
//
//        'test_upload' => true,
//  
//      );
//
//      $results = wp_handle_sideload( $file, $overrides );
//
//      if ( !empty( $results['error'] ) ) {
//        
//      } else {
//        $filename = $results['file']; // full path to file
//        $local_url = $results['url']; // url to the file in the uploads dir
//        $type = $results['type']; // MIME type of the file
//      }
//
//      echo '<pre>'; var_dump( $results ); echo '</pre>'; die(  );
//    }
//  }

  private function download_image() {

    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $image = media_sideload_image( 
      $this->profile_image_url, 
      $this->post_id,
      'Twitter profile image for ' . $this->screen_name
    );

echo '<pre>'; var_dump( $image ); echo '</pre>'; die(  );
  }
}
  
