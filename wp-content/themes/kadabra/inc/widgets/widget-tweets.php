<?php
/*
* Latest tweets with PHP widget
*/
class crum_latest_tweets extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'twitter-widget', // Base ID
            'Cr: Latest Tweets', // Name
            array('description' => __('Displays your latest Tweets', 'dfd'),) // Args
        );
    }

    //widget output
    public function widget($args, $instance) {
        extract($args);

        echo $before_widget;

        if (isset($instance['title'])) {
            $title = $instance['title'];
		} else {
			$title = false;
		}

        if ($title) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        //check settings and die if not set
        if (empty($instance['consumerkey']) || empty($instance['consumersecret']) || empty($instance['accesstoken']) || empty($instance['accesstokensecret']) || empty($instance['cachetime']) || empty($instance['username'])) {
            echo '<strong>Please fill all widget settings!</strong>' . $after_widget;
            return;
        }
		
        //convert links to clickable format
        if (!function_exists('convert_links')) {
            function convert_links($status, $targetBlank = true, $linkMaxLen = 250)
            {

                // the target
                $target = $targetBlank ? " target=\"_blank\" " : "";

                // convert link to url
                $status = preg_replace("/((http:\/\/|https:\/\/)[^ )
]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

                // convert @ to follow
                $status = preg_replace("/(@([_a-z0-9\-]+))/i", "<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $status);

                // convert # to search
                $status = preg_replace("/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>", $status);

                // return the status
                return $status;
            }
        }

        if (!function_exists('relative_time')) { 
            //convert dates to readable format
            function relative_time($a) {
                //get current timestampt
                $b = time();
                //get timestamp when tweet created
				if (is_integer($a)) {
					$c = $a;
				} else {
					$c = strtotime($a);
				}
                //get difference
                $d = $b - $c;
                //calculate different time values
                $minute = 60;
                $hour = $minute * 60;
                $day = $hour * 24;
                $week = $day * 7;

                if (is_numeric($d) && $d > 0) {
                    //if less then 3 seconds
                    if ($d < 3) return "right now";
                    //if less then minute
                    if ($d < $minute) return floor($d) . " seconds ago";
                    //if less then 2 minutes
                    if ($d < $minute * 2) return "about 1 minute ago";
                    //if less then hour
                    if ($d < $hour) return floor($d / $minute) . " minutes ago";
                    //if less then 2 hours
                    if ($d < $hour * 2) return "about 1 hour ago";
                    //if less then day
                    if ($d < $day) return floor($d / $hour) . " hours ago";
                    //if more then day, but less then 2 days
                    if ($d > $day && $d < $day * 2) return "yesterday";
                    //if less then year
                    if ($d < $day * 365) return floor($d / $day) . " days ago";
                    //else return more than a year
                    return "over a year ago";
                }
            }
        }


//        $tp_twitter_plugin_tweets = maybe_unserialize(get_option('tp_twitter_plugin_tweets'));
		require_once locate_template('/inc/lib/twitteroauth.php');
		$twitter = new DFDTwitter();
		$tp_twitter_plugin_tweets = $twitter->getTweets();
        if (!empty($tp_twitter_plugin_tweets)) {
			$image = $tp_twitter_plugin_tweets[0]['image'];
			$screen_name = $tp_twitter_plugin_tweets[0]['name'];
			
            echo '<div class="tweets-author">
                 <img src="' . $image . '" alt="" />
                 <strong>' . $screen_name . ' <span>@' . $instance['username'] . '</span></strong> '; ?>

            <a href="https://twitter.com/<?php echo $instance['username']; ?>"
               class="twitter-follow-button"
               data-show-count="false"
               data-lang="en"><?php _e('Follow me', 'dfd'); ?></a>
            <script>!function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", "twitter-wjs");</script>

            <?php echo '</div>';


            print '<div class="tweet-list">';
            $fctr = '1';
            foreach ($tp_twitter_plugin_tweets as $tweet) {
                print '<div class="tweet"><i class="soc_icon-twitter-3"></i>' . $tweet['text'] . '<div class="time">' . relative_time($tweet['time']) . '</div></div>';
                if ($fctr == $instance['tweetstoshow']) {
                    break;
                }
                $fctr++;
            }
			
			if ($instance['read_all'] == 1) {
				print DFD_HTML::read_more('https://twitter.com/'.$instance['username'], __('Read all tweets', 'dfd'));
			}

            print '</div>';
        }


        echo $after_widget;
    }


    //save widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['consumerkey'] = strip_tags($new_instance['consumerkey']);
        $instance['consumersecret'] = strip_tags($new_instance['consumersecret']);
        $instance['accesstoken'] = strip_tags($new_instance['accesstoken']);
        $instance['accesstokensecret'] = strip_tags($new_instance['accesstokensecret']);
        $instance['cachetime'] = strip_tags($new_instance['cachetime']);
        $instance['username'] = strip_tags($new_instance['username']);
        $instance['tweetstoshow'] = strip_tags($new_instance['tweetstoshow']);
		$instance['read_all'] = intval($new_instance['read_all']);

        if ($old_instance['username'] != $new_instance['username']) {
            delete_option('tp_twitter_plugin_last_cache_time');
        }

        return $instance;
    }


    //widget settings form
    public function form($instance)
    {
        $defaults = array('title' => '', 'consumerkey' => '', 'consumersecret' => '', 'accesstoken' => '', 'accesstokensecret' => '', 'cachetime' => '', 'username' => '', 'tweetstoshow' => '');
        $instance = wp_parse_args((array)$instance, $defaults);

        echo '
				<p><label>Title:</label>
					<input type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . esc_attr($instance['title']) . '" class="widefat" /></p>
				<p><label>Consumer Key:</label>
					<input type="text" name="' . $this->get_field_name('consumerkey') . '" id="' . $this->get_field_id('consumerkey') . '" value="' . esc_attr($instance['consumerkey']) . '" class="widefat" /></p>
				<p><label>Consumer Secret:</label>
					<input type="text" name="' . $this->get_field_name('consumersecret') . '" id="' . $this->get_field_id('consumersecret') . '" value="' . esc_attr($instance['consumersecret']) . '" class="widefat" /></p>
				<p><label>Access Token:</label>
					<input type="text" name="' . $this->get_field_name('accesstoken') . '" id="' . $this->get_field_id('accesstoken') . '" value="' . esc_attr($instance['accesstoken']) . '" class="widefat" /></p>
				<p><label>Access Token Secret:</label>
					<input type="text" name="' . $this->get_field_name('accesstokensecret') . '" id="' . $this->get_field_id('accesstokensecret') . '" value="' . esc_attr($instance['accesstokensecret']) . '" class="widefat" /></p>
				<p><label>Cache Tweets in every:</label>
					<input type="text" name="' . $this->get_field_name('cachetime') . '" id="' . $this->get_field_id('cachetime') . '" value="' . esc_attr($instance['cachetime']) . '" class="small-text" /> hours</p>
				<p><label>Twitter Username:</label>
					<input type="text" name="' . $this->get_field_name('username') . '" id="' . $this->get_field_id('username') . '" value="' . esc_attr($instance['username']) . '" class="widefat" /></p>
				<p><label>Tweets to display:</label>
					<select type="text" name="' . $this->get_field_name('tweetstoshow') . '" id="' . $this->get_field_id('tweetstoshow') . '">';
				$i = 1;
				for (i; $i <= 10; $i++) {
					echo '<option value="' . $i . '"';
					if ($instance['tweetstoshow'] == $i) {
						echo ' selected="selected"';
					}
					echo '>' . $i . '</option>';
				}
				echo '</select></p>';
				
				echo '<p><label>Show read all link:</label>
					<select type="text" name="' . $this->get_field_name('read_all') . '" id="' . $this->get_field_id('read_all') . '">';
				echo '<option value="0"';
					if ($instance['read_all'] == 0) {
						echo ' selected="selected"';
					}
					echo '>'.__('No', 'dfd').'</option>';
				echo '<option value="1"';
					if ($instance['read_all'] == 1) {
						echo ' selected="selected"';
					}
					echo '>'.__('Yes', 'dfd').'</option>';
				echo '</select></p>';
    }
}
