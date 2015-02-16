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
   * 
   */
  public function __construct( $json_str )
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

  }

  private function format_body() {

    $this->format_hashtags();
    $this->format_urls();
    $this->format_mentions();

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

  
  
  public  function __get( $prop ) {
    if ( property_exists( $this, $prop ) ) {
      return $this->$prop;
    }
  }

  

}
