<?php

require_once( 'elit-social-pick.php' );
require_once( 'elit-tweet.php' );

class Elit_Tweet_Tests extends WP_UnitTestCase {

  private $test_tweets;
  private $test_tweet;

  function setUp() {
    $this->hiya = 'hiya';

    $this->test_tweet =
      '{ 
          "created_at": "Fri Feb 13 14:56:15 +0000 2015", 
          "id": 566249499656343550, 
          "id_str": "566249499656343553", 
          "text": "Newest evidence on type 1 DM @AOA_GOAL @AOAforDOs @TheDOmagazine @FloridaDOs  https://t.co/voTh1agn7k http://t.co/1dz91SQnHF", 
          "source": "<a href=\"http://www.apple.com\" rel=\"nofollow\">iOS</a>", 
          "truncated": false, 
          "in_reply_to_status_id": null, 
          "in_reply_to_status_id_str": null, 
          "in_reply_to_user_id": null, 
          "in_reply_to_user_id_str": null, 
          "in_reply_to_screen_name": null, 
          "user": { 
              "id": 718452762, 
              "id_str": "718452762", 
              "name": "Andrew Buelt DO", 
              "screen_name": "AndrewBuelt", 
              "location": "St. Petersburg, Fl.", 
              "profile_location": null, 
              "description": "medicine and motivation, stay healthy, stay hungry. Co-host of Questioning Medicine podcast. Believer in FOAM & better medical care with pt. objectives in mind", 
              "url": "http://t.co/MM4CXatxIe", 
              "entities": { 
                  "url": { 
                      "urls": [ 
                          { 
                              "url": "http://t.co/MM4CXatxIe", 
                              "expanded_url": "http://www.questioningmedicine.com", 
                              "display_url": "questioningmedicine.com", 
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
              "followers_count": 269, 
              "friends_count": 285, 
              "listed_count": 13, 
              "created_at": "Thu Jul 26 17:58:05 +0000 2012", 
              "favourites_count": 806, 
              "utc_offset": null, 
              "time_zone": null, 
              "geo_enabled": false, 
              "verified": false, 
              "statuses_count": 1404, 
              "lang": "en", 
              "contributors_enabled": false, 
              "is_translator": false, 
              "is_translation_enabled": false, 
              "profile_background_color": "C0DEED", 
              "profile_background_image_url": "http://abs.twimg.com/images/themes/theme1/bg.png", 
              "profile_background_image_url_https": "https://abs.twimg.com/images/themes/theme1/bg.png", 
              "profile_background_tile": false, 
              "profile_image_url": "http://pbs.twimg.com/profile_images/2578361273/imwu27z9whc1oebqgmgn_normal.jpeg", 
              "profile_image_url_https": "https://pbs.twimg.com/profile_images/2578361273/imwu27z9whc1oebqgmgn_normal.jpeg", 
              "profile_banner_url": "https://pbs.twimg.com/profile_banners/718452762/1402670169", 
              "profile_link_color": "0084B4", 
              "profile_sidebar_border_color": "C0DEED", 
              "profile_sidebar_fill_color": "DDEEF6", 
              "profile_text_color": "333333", 
              "profile_use_background_image": true, 
              "default_profile": true, 
              "default_profile_image": false, 
              "following": false, 
              "follow_request_sent": false, 
              "notifications": false 
          }, 
          "geo": null, 
          "coordinates": null, 
          "place": null, 
          "contributors": null, 
          "retweet_count": 2, 
          "favorite_count": 0, 
          "entities": { 
              "hashtags": [], 
              "symbols": [], 
              "user_mentions": [ 
                  { 
                      "screen_name": "AOA_GOAL", 
                      "name": "AOA_GOAL", 
                      "id": 18056743, 
                      "id_str": "18056743", 
                      "indices": [ 
                          29, 
                          38 
                      ] 
                  }, 
                  { 
                      "screen_name": "AOAforDOs", 
                      "name": "AOA", 
                      "id": 273614983, 
                      "id_str": "273614983", 
                      "indices": [ 
                          39, 
                          49 
                      ] 
                  }, 
                  { 
                      "screen_name": "TheDOmagazine", 
                      "name": "TheDOmagazine", 
                      "id": 19262807, 
                      "id_str": "19262807", 
                      "indices": [ 
                          50, 
                          64 
                      ] 
                  }, 
                  { 
                      "screen_name": "FloridaDOs", 
                      "name": "Florida Osteopathic", 
                      "id": 555161390, 
                      "id_str": "555161390", 
                      "indices": [ 
                          65, 
                          76 
                      ] 
                  } 
              ], 
              "urls": [ 
                  { 
                      "url": "https://t.co/voTh1agn7k", 
                      "expanded_url": "https://itun.es/us/koezZ.c", 
                      "display_url": "itun.es/us/koezZ.c", 
                      "indices": [ 
                          78, 
                          101 
                      ] 
                  } 
              ], 
              "media": [ 
                  { 
                      "id": 566249499597623300, 
                      "id_str": "566249499597623296", 
                      "indices": [ 
                          102, 
                          124 
                      ], 
                      "media_url": "http://pbs.twimg.com/media/B9u463XCUAAZ_X6.jpg", 
                      "media_url_https": "https://pbs.twimg.com/media/B9u463XCUAAZ_X6.jpg", 
                      "url": "http://t.co/1dz91SQnHF", 
                      "display_url": "pic.twitter.com/1dz91SQnHF", 
                      "expanded_url": "http://twitter.com/AndrewBuelt/status/566249499656343553/photo/1", 
                      "type": "photo", 
                      "sizes": { 
                          "small": { 
                              "w": 340, 
                              "h": 340, 
                              "resize": "fit" 
                          }, 
                          "thumb": { 
                              "w": 150, 
                              "h": 150, 
                              "resize": "crop" 
                          }, 
                          "medium": { 
                              "w": 600, 
                              "h": 600, 
                              "resize": "fit" 
                          }, 
                          "large": { 
                              "w": 600, 
                              "h": 600, 
                              "resize": "fit" 
                          } 
                      } 
                  } 
              ] 
          }, 
          "extended_entities": { 
              "media": [ 
                  { 
                      "id": 566249499597623300, 
                      "id_str": "566249499597623296", 
                      "indices": [ 
                          102, 
                          124 
                      ], 
                      "media_url": "http://pbs.twimg.com/media/B9u463XCUAAZ_X6.jpg", 
                      "media_url_https": "https://pbs.twimg.com/media/B9u463XCUAAZ_X6.jpg", 
                      "url": "http://t.co/1dz91SQnHF", 
                      "display_url": "pic.twitter.com/1dz91SQnHF", 
                      "expanded_url": "http://twitter.com/AndrewBuelt/status/566249499656343553/photo/1", 
                      "type": "photo", 
                      "sizes": { 
                          "small": { 
                              "w": 340, 
                              "h": 340, 
                              "resize": "fit" 
                          }, 
                          "thumb": { 
                              "w": 150, 
                              "h": 150, 
                              "resize": "crop" 
                          }, 
                          "medium": { 
                              "w": 600, 
                              "h": 600, 
                              "resize": "fit" 
                          }, 
                          "large": { 
                              "w": 600, 
                              "h": 600, 
                              "resize": "fit" 
                          } 
                      } 
                  } 
              ] 
          }, 
          "favorited": false, 
          "retweeted": true, 
          "possibly_sensitive": false, 
          "lang": "en" 
       }';
    
  }

  function test_tweet_setup() {
    $this->assertStringStartsWith( '{', $this->test_tweet );
  }

  function test_object_instantiation() {
    $tweet = new Elit_Tweet( $this->test_tweet );
    $this->assertNotNull( $tweet );
  }
}

