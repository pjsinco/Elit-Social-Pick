<?php

require_once( 'elit-social-pick.php' );
require_once( 'elit-tweet.php' );

class Elit_Tweet_Tests extends WP_UnitTestCase {

  private $test_tweets = array();
  private $test_tweet;
  private $tweet;

  function setUp() {

            // troublesome tweet 2015-11-06
//           '{
//               "created_at": "Fri Nov 06 00:18:15 +0000 2015", 
//               "id": 662423716471504900, 
//               "id_str": "662423716471504896", 
//               "text": "The geriatrician shortage: The problem isn’t what you think https://t.co/sOLWSrCsy7 https://t.co/VhH2oB442L", 
//               "source": "<a href=\"http://twibble.io\" rel=\"nofollow\">Twibble.io</a>", 
//               "truncated": false, 
//               "in_reply_to_status_id": null, 
//               "in_reply_to_status_id_str": null, 
//               "in_reply_to_user_id": null, 
//               "in_reply_to_user_id_str": null, 
//               "in_reply_to_screen_name": null, 
//               "user": { 
//                   "id": 11274452, 
//                   "id_str": "11274452", 
//                   "name": "Kevin Pho, M.D.", 
//                   "screen_name": "kevinmd", 
//                   "location": "Nashua, NH, north of Boston", 
//                   "description": "Physician, author, keynote speaker, USA TODAY\'s Board of Contributors. Social media's leading physician voice. http://t.co/MlC37Wze and http://t.co/hThNxwTt", 
//                   "url": "http://t.co/lOM5qo8ZrG", 
//                   "entities": { 
//                       "url": { 
//                           "urls": [ 
//                               { 
//                                   "url": "http://t.co/lOM5qo8ZrG", 
//                                   "expanded_url": "http://KevinMD.com", 
//                                   "display_url": "KevinMD.com", 
//                                   "indices": [ 
//                                       0, 
//                                       22 
//                                   ] 
//                               } 
//                           ] 
//                       }, 
//                       "description": { 
//                           "urls": [ 
//                               { 
//                                   "url": "http://t.co/MlC37Wze", 
//                                   "expanded_url": "http://KevinMD.com", 
//                                   "display_url": "KevinMD.com", 
//                                   "indices": [ 
//                                       111, 
//                                       131 
//                                   ] 
//                               }, 
//                               { 
//                                   "url": "http://t.co/hThNxwTt", 
//                                   "expanded_url": "http://KevinMD.com/blog/reputation", 
//                                   "display_url": "KevinMD.com/blog/reputation", 
//                                   "indices": [ 
//                                       136, 
//                                       156 
//                                   ] 
//                               } 
//                           ] 
//                       } 
//                   }, 
//                   "protected": false, 
//                   "followers_count": 130514, 
//                   "friends_count": 20251, 
//                   "listed_count": 5037, 
//                   "created_at": "Tue Dec 18 00:53:29 +0000 2007", 
//                   "favourites_count": 0, 
//                   "utc_offset": -18000, 
//                   "time_zone": "Eastern Time (US & Canada)", 
//                   "geo_enabled": true, 
//                   "verified": false, 
//                   "statuses_count": 33252, 
//                   "lang": "en", 
//                   "contributors_enabled": false, 
//                   "is_translator": false, 
//                   "is_translation_enabled": false, 
//                   "profile_background_color": "FFFFFF", 
//                   "profile_background_image_url": "http://pbs.twimg.com/profile_background_images/731024649/1d071aebac004477c6356d512e435c68.jpeg", 
//                   "profile_background_image_url_https": "https://pbs.twimg.com/profile_background_images/731024649/1d071aebac004477c6356d512e435c68.jpeg", 
//                   "profile_background_tile": false, 
//                   "profile_image_url": "http://pbs.twimg.com/profile_images/1182717255/Kevin2_WEB_normal.jpg", 
//                   "profile_image_url_https": "https://pbs.twimg.com/profile_images/1182717255/Kevin2_WEB_normal.jpg", 
//                   "profile_banner_url": "https://pbs.twimg.com/profile_banners/11274452/1398255994", 
//                   "profile_link_color": "990000", 
//                   "profile_sidebar_border_color": "FFFFFF", 
//                   "profile_sidebar_fill_color": "F3F3F3", 
//                   "profile_text_color": "333333", 
//                   "profile_use_background_image": true, 
//                   "has_extended_profile": false, 
//                   "default_profile": false, 
//                   "default_profile_image": false, 
//                   "following": false, 
//                   "follow_request_sent": false, 
//                   "notifications": false 
//               }, 
//               "geo": null, 
//               "coordinates": null, 
//               "place": null, 
//               "contributors": null, 
//               "is_quote_status": false, 
//               "retweet_count": 3, 
//               "favorite_count": 3, 
//               "entities": { 
//                   "hashtags": [], 
//                   "symbols": [], 
//                   "user_mentions": [], 
//                   "urls": [ 
//                       { 
//                           "url": "https://t.co/sOLWSrCsy7", 
//                           "expanded_url": "http://bit.ly/1StxwbT", 
//                           "display_url": "bit.ly/1StxwbT", 
//                           "indices": [ 
//                               60, 
//                               83 
//                           ] 
//                       } 
//                   ], 
//                   "media": [ 
//                       { 
//                           "id": 662423714600849400, 
//                           "id_str": "662423714600849408", 
//                           "indices": [ 
//                               84, 
//                               107 
//                           ], 
//                           "media_url": "http://pbs.twimg.com/media/CTFm29eUEAAuSrz.jpg", 
//                           "media_url_https": "https://pbs.twimg.com/media/CTFm29eUEAAuSrz.jpg", 
//                           "url": "https://t.co/VhH2oB442L", 
//                           "display_url": "pic.twitter.com/VhH2oB442L", 
//                           "expanded_url": "http://twitter.com/kevinmd/status/662423716471504896/photo/1", 
//                           "type": "photo", 
//                           "sizes": { 
//                               "large": { 
//                                   "w": 1000, 
//                                   "h": 1000, 
//                                   "resize": "fit" 
//                               }, 
//                               "small": { 
//                                   "w": 340, 
//                                   "h": 340, 
//                                   "resize": "fit" 
//                               }, 
//                               "thumb": { 
//                                   "w": 150, 
//                                   "h": 150, 
//                                   "resize": "crop" 
//                               }, 
//                               "medium": { 
//                                   "w": 600, 
//                                   "h": 600, 
//                                   "resize": "fit" 
//                               } 
//                           } 
//                       } 
//                   ] 
//               }, 
//               "extended_entities": { 
//                   "media": [ 
//                       { 
//                           "id": 662423714600849400, 
//                           "id_str": "662423714600849408", 
//                           "indices": [ 
//                               84, 
//                               107 
//                           ], 
//                           "media_url": "http://pbs.twimg.com/media/CTFm29eUEAAuSrz.jpg", 
//                           "media_url_https": "https://pbs.twimg.com/media/CTFm29eUEAAuSrz.jpg", 
//                           "url": "https://t.co/VhH2oB442L", 
//                           "display_url": "pic.twitter.com/VhH2oB442L", 
//                           "expanded_url": "http://twitter.com/kevinmd/status/662423716471504896/photo/1", 
//                           "type": "photo", 
//                           "sizes": { 
//                               "large": { 
//                                   "w": 1000, 
//                                   "h": 1000, 
//                                   "resize": "fit" 
//                               }, 
//                               "small": { 
//                                   "w": 340, 
//                                   "h": 340, 
//                                   "resize": "fit" 
//                               }, 
//                               "thumb": { 
//                                   "w": 150, 
//                                   "h": 150, 
//                                   "resize": "crop" 
//                               }, 
//                               "medium": { 
//                                   "w": 600, 
//                                   "h": 600, 
//                                   "resize": "fit" 
//                               } 
//                           } 
//                       } 
//                   ] 
//               }, 
//               "favorited": false, 
//               "retweeted": false, 
//               "possibly_sensitive": false, 
//               "possibly_sensitive_appealable": false, 
//               "lang": "en" 
//            
//        }';

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
    
    $this->tweet = new Elit_Tweet( $this->test_tweet );
    $this->tweet->set_post_id(3);
  }

  function test_set_post_id() {
    $this->tweet->set_post_id(3);
    $this->assertEquals(3, $this->tweet->post_id);
  }

  function test_set_screen_name() {
    $this->tweet->set_screen_name($this->tweet->tweet->user->screen_name);
    $this->assertSame("AndrewBuelt", $this->tweet->screen_name);
  }

  function test_set_profile_image_url() {
    $this->markTestIncomplete();

  }

  function test_tweet_setup() {
    $this->markTestIncomplete();
    $this->assertStringStartsWith( '{', $this->test_tweet );
  }

  function test_object_instantiation() {
    $this->markTestIncomplete();
    $this->assertNotNull( $this->tweet );
  }

  function test_screen_name() {
    $this->markTestIncomplete();
    $this->assertEquals( $this->tweet->screen_name, 'AndrewBuelt' );
  }

  function test_profile_image_url() {
    $this->markTestIncomplete();
    $this->assertEquals( 
      $this->tweet->profile_image_url, 
      'http://pbs.twimg.com/profile_images/2578361273/imwu27z9whc1oebqgmgn_normal.jpeg' 
    );
  }

  function test_profile_text() {
    $this->markTestIncomplete();
    $this->assertEquals( 
      $this->tweet->text, 
      'Newest evidence on type 1 DM @AOA_GOAL @AOAforDOs @TheDOmagazine @FloridaDOs  https://t.co/voTh1agn7k http://t.co/1dz91SQnHF' 
    );
  }

  function test_date() {
    $this->markTestIncomplete();
    $this->assertEquals( 
      $this->tweet->date, 
      'Fri Feb 13 14:56:15 +0000 2015' 
    );
  }

  function test_find_entities() {
    $this->markTestIncomplete();
    $this->assertNotNull( $this->tweet->entities );
  }

  function test_find_user_mentions() {
    $this->markTestIncomplete();
    $this->assertNotNull( $this->tweet->entities->user_mentions );
  }
  
}

