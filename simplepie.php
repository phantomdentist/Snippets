<?php
//Example of a titter feed with simplepie

//Setup wordpresses build in feed functionality
$username = 'Your_LEAP_Lewes';
include_once( ABSPATH . WPINC . '/class-simplepie.php' );
$feed = new SimplePie();
$feed->set_feed_url('https://api.twitter.com/1/statuses/user_timeline.rss?screen_name='.$username.'&count=3');
$feed->set_timeout(30); // set to 30 seconds
$feed->set_stupidly_fast(true);
$feed->enable_cache(true);
$feed->set_cache_location ( '/var/sites/y/yourleap.co.uk/public_html/wp-content/cache' );
$feed->set_cache_duration(1800);
$feed->init();
$feed->handle_content_type();

//Function using regular expression to make Tweet links clickable
function twitterify($ret) {
$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a target=\"_new\" href=\"\\2\" >\\2</a>", $ret);
$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a target=\"_new\" href=\"http://\\2\" >\\2</a>", $ret);
$ret = preg_replace("/@(\w+)/", "<a target=\"_new\" href=\"http://www.twitter.com/\\1\" >@\\1</a>", $ret);
$ret = preg_replace("/#(\w+)/", "<a target=\"_new\" href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $ret);
return $ret;
}
?>

<div class="twitter-feed">
    <div class="twitter-header clearfix">
        <h2>Our latest Tweets</h2>
        <a href="https://twitter.com/Your_LEAP_Lewes" class="twitter-follow-button" data-show-count="false">Follow @Your_LEAP_Lewes</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    <ul class="twitter-ul clearfix">
		<?php 
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $feed->get_items(0, 3) as $item ) : ?>
        <li>
        <?php echo twitterify( str_replace("$username: ", "", $item->get_description() ) ); ?>
        <span class="tweet-date"><?php echo $item->get_date('j M'); ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>