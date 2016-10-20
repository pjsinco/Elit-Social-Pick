#####Fri Sep  4 15:14:15 2015 CDT
* Troublesome text:
    ```"Learn about leadership changes at @Kcumb, @PCOMeducation &amp; @WesternUNews from Inside OME: http://t.co/mYbYAMJKiI #medstudents #osteopathic"```

    ```json
    { 
        "created_at": "Thu Sep 03 18:51:29 +0000 2015", 
        "id": 639511046697431000, 
        "id_str": "639511046697431040", 
        "text": "Learn about leadership changes at @Kcumb, @PCOMeducation &amp; @WesternUNews from Inside OME: http://t.co/mYbYAMJKiI #medstudents #osteopathic", 
        "source": "<a href=\"http://twitter.com\" rel=\"nofollow\">Twitter Web Client</a>", 
        "truncated": false, 
        "in_reply_to_status_id": null, 
        "in_reply_to_status_id_str": null, 
        "in_reply_to_user_id": null, 
        "in_reply_to_user_id_str": null, 
        "in_reply_to_screen_name": null, 
        "user": { 
            "id": 900404894, 
            "id_str": "900404894", 
            "name": "AACOM", 
            "screen_name": "AACOMmunities", 
            "location": "Chevy Chase, MD", 
            "description": "The American Association of Colleges of Osteopathic Medicine (AACOM) represents the administration, faculty and students of all US osteopathic medical colleges.", 
            "url": "http://t.co/yTqZ9HGNMA", 
            "entities": { 
                "url": { 
                    "urls": [ 
                        { 
                            "url": "http://t.co/yTqZ9HGNMA", 
                            "expanded_url": "http://www.aacom.org", 
                            "display_url": "aacom.org", 
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
            "followers_count": 3692, 
            "friends_count": 4059, 
            "listed_count": 53, 
            "created_at": "Tue Oct 23 19:04:11 +0000 2012", 
            "favourites_count": 660, 
            "utc_offset": -10800, 
            "time_zone": "Atlantic Time (Canada)", 
            "geo_enabled": false, 
            "verified": false, 
            "statuses_count": 5644, 
            "lang": "en", 
            "contributors_enabled": false, 
            "is_translator": false, 
            "is_translation_enabled": false, 
            "profile_background_color": "C0DEED", 
            "profile_background_image_url": "http://abs.twimg.com/images/themes/theme1/bg.png", 
            "profile_background_image_url_https": "https://abs.twimg.com/images/themes/theme1/bg.png", 
            "profile_background_tile": false, 
            "profile_image_url": "http://pbs.twimg.com/profile_images/2755673114/f2e0837db55c9eaad9fac4da972e962f_normal.gif", 
            "profile_image_url_https": "https://pbs.twimg.com/profile_images/2755673114/f2e0837db55c9eaad9fac4da972e962f_normal.gif", 
            "profile_banner_url": "https://pbs.twimg.com/profile_banners/900404894/1399490537", 
            "profile_link_color": "0084B4", 
            "profile_sidebar_border_color": "C0DEED", 
            "profile_sidebar_fill_color": "DDEEF6", 
            "profile_text_color": "333333", 
            "profile_use_background_image": true, 
            "has_extended_profile": false, 
            "default_profile": true, 
            "default_profile_image": false, 
            "following": true, 
            "follow_request_sent": false, 
            "notifications": false 
        }, 
        "geo": null, 
        "coordinates": null, 
        "place": null, 
        "contributors": null, 
        "is_quote_status": false, 
        "retweet_count": 1, 
        "favorite_count": 0, 
        "entities": { 
            "hashtags": [ 
                { 
                    "text": "medstudents", 
                    "indices": [ 
                        117, 
                        129 
                    ] 
                }, 
                { 
                    "text": "osteopathic", 
                    "indices": [ 
                        130, 
                        142 
                    ] 
                } 
            ], 
            "symbols": [], 
            "user_mentions": [ 
                { 
                    "screen_name": "Kcumb", 
                    "name": "KCUMB", 
                    "id": 53110981, 
                    "id_str": "53110981", 
                    "indices": [ 
                        34, 
                        40 
                    ] 
                }, 
                { 
                    "screen_name": "PCOMeducation", 
                    "name": "PCOM", 
                    "id": 205349150, 
                    "id_str": "205349150", 
                    "indices": [ 
                        42, 
                        56 
                    ] 
                }, 
                { 
                    "screen_name": "WesternUNews", 
                    "name": "WesternU", 
                    "id": 549076236, 
                    "id_str": "549076236", 
                    "indices": [ 
                        63, 
                        76 
                    ] 
                } 
            ], 
            "urls": [ 
                { 
                    "url": "http://t.co/mYbYAMJKiI", 
                    "expanded_url": "http://bit.ly/1QbpfIx", 
                    "display_url": "bit.ly/1QbpfIx", 
                    "indices": [ 
                        94, 
                        116 
                    ] 
                } 
            ] 
        }, 
        "favorited": false, 
        "retweeted": false, 
        "possibly_sensitive": false, 
        "possibly_sensitive_appealable": false, 
        "lang": "en" 
    }
    ```

#####Sat Sep  5 20:54:43 2015 CDT
* Troublesome tweets that Rose has pointed out:
    * 639511046697431040
    * 626125868247552000

#####Mon Sep 14 18:07:39 2015 CDT
* Troublesome tweet Rose pointed out:
    * 642433335269134300
        * The image for this tweet wasn't showing up.
        ```js
        "profile_image_url": "http://pbs.twimg.com/profile_images/622922297662435328/_tektL88_normal.jpg", 
        ```  
        * The title begins with an underscore.
            * It appears the underscore was getting lopped off somewhere during WP's media_sideload_image() call
            * That function can return a url string ready to use as in an <img src="">
                * That string has the correct title, i.e., without the leading "_"
                * So we use that returned string in further processing, as opposed to the original $profile_image_url

#####Thu 20 Oct 2016 02:40:22 PM CDT CDT
* Twitter now has an extended mode, which changed the names of some fields. 
When 'truncated' is true, the tweet's text is in 'text.' When 'truncated'
is false, the text is in 'full_text'.
