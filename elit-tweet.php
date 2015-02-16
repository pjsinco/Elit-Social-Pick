<?php  

/**
 * Elit Tweet
 */
class Elit_Tweet
{

  public $tweet;
  public $screen_name;
  public $profile_image_url;
  public $text;
  public $date;
  
  /**
   * 
   */
  public function __construct( $json_str )
  {
    $json_obj = json_decode( $json_str );
    
    $this->tweet = json_decode( $json_str );
    $this->screen_name = $this->tweet->user->screen_name;
    $this->profile_image_url = $this->tweet->user->profile_image_url;
    $this->text = $this->tweet->text;
    $this->date = $this->tweet->created_at;
  }

  public function format_tweet() {

    
  }
}
