<?php
function dpArticleShare_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}

function dpArticleShare_plugin_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function dpArticleShare_reslash_multi(&$val,$key) 
{
   if (is_array($val)) array_walk($val,'dpArticleShare_reslash_multi',$new);
   else {
      $val = dpArticleShare_reslash($val);
   }
}


function dpArticleShare_reslash($string)
{
   if (!get_magic_quotes_gpc())$string = addslashes($string);
   return $string;
}

add_filter( 'the_content', 'dpArticleShare_content_filter', 10 );

function dpArticleShare_content_filter( $content ) {
	global $post, $dpArticleShare;
	
	$dp_article_share_disable = get_post_meta($post->ID, 'dp_article_share_disable', true);
	$dp_article_share_position = get_post_meta($post->ID, 'dp_article_share_position', true);
	
	if ( @!in_array(get_post_type($post), $dpArticleShare['scope']) || ((is_front_page() || is_home()) && !in_array('home', $dpArticleShare['scope'])) || ($dp_article_share_disable) || !is_main_query() || !is_singular($dpArticleShare['scope']) ) return $content;
	
	$position = $dpArticleShare['position'];
	
	if($dp_article_share_position != "") {
		$position = $dp_article_share_position;
	}
	
	if($position == 'horizontal-bottom') {		
		$content .= do_shortcode('[dpArticleShare]');
	} else { 
		$content = do_shortcode('[dpArticleShare]').$content;
		if($position == 'horizontal-top-bottom') {	
			$content = $content.do_shortcode('[dpArticleShare]');
		}
	}

    // Returns the content.
    return $content;
}

function dpArticleShare_updateNotice(){
    echo '<div class="updated">
       <p>Updated Succesfully.</p>
    </div>';
}

if(@$_GET['settings-updated'] && ($_GET['page'] == 'dpArticleShare-settings')) {
	add_action('admin_notices', 'dpArticleShare_updateNotice');
}

function dpArticleShare_file_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    if ((!ini_get('open_basedir') && !ini_get('safe_mode'))) {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	}
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function dpArticleShare_url_shortener($longUrl, $post_id = '') {
	global $dpArticleShare;
	
	if($dpArticleShare['bitly_enabled']) {
		$url = 'http://api.bitly.com/v3/shorten';
		$fields = array(
			"format" => "json",
            "apiKey" => $dpArticleShare['bitly_api_key'],
            "login" => $dpArticleShare['bitly_login'],
            "longUrl" => $longUrl
		);
	} else {
		//set POST variables
		$url = 'https://www.googleapis.com/urlshortener/v1/url';
		$fields = array(
					'longUrl' => $longUrl
			);
	}
	/*if(is_numeric($post_id)) {
		if(get_post_meta($post_id, 'dp_share_short_url', true) != "" && ( ($dpArticleShare['bitly_enabled'] && strpos(get_post_meta($post_id, 'dp_share_short_url', true), 'goo.gl') === false) || (!$dpArticleShare['bitly_enabled'] && strpos(get_post_meta($post_id, 'dp_share_short_url', true), 'goo.gl') !== false) )) {
			return get_post_meta($post_id, 'dp_share_short_url', true);
		}
	}*/
	
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	if($dpArticleShare['bitly_enabled']) {
		curl_setopt($ch,CURLOPT_URL, $url.'?'.$fields_string);
	} else {
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
	}
	curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	//execute post
	$result = json_decode(curl_exec($ch), true);
	
	//close connection
	curl_close($ch);
	
	if($dpArticleShare['bitly_enabled'] && $result['status_code'] == 200) {
		$result['id'] = $result['data']['url'];
	}

	if($result['id'] != "") {
		if(is_numeric($post_id)) {
			update_post_meta($post_id, 'dp_share_short_url', $result['id']);
		}
    	return $result['id'];
	} else {
		return $longUrl;
	}
}

add_action( 'wp_ajax_nopriv_updateAllPostShares', 'dpArticleShare_updateAllPostShares' );
add_action( 'wp_ajax_updateAllPostShares', 'dpArticleShare_updateAllPostShares' );

function dpArticleShare_updateAllPostShares() {
	global $dpArticleShare;
	
	$scope_list = $dpArticleShare['scope'];

	if(in_array('home', $scope_list)) {
		$indexCompleted = array_search('home', $scope_list);
		unset($scope_list[$indexCompleted]);
	}
	
	if(!is_array($scope_list) || count($scope_list) == 0) {
		$scope_list = array('post', 'page');
	}
	
	foreach($scope_list as $scope) {
		if($scope == 'home') continue;
		
		$args = array( 
			'posts_per_page' => -1, 
			'post_type'=> $scope
		);

		query_posts($args);
		if (have_posts()) {
			while (have_posts()) : the_post(); 
				echo "Title: ".get_the_title()."<br>";
				dpArticleShare_updatePostShares(get_the_ID(), false);
			endwhile;
		}
	}
	
	die();
}

add_action( 'wp_ajax_nopriv_ArticleShare_SendMail', 'dpArticleShare_ArticleShare_SendMail' );
add_action( 'wp_ajax_ArticleShare_SendMail', 'dpArticleShare_ArticleShare_SendMail' );

function dpArticleShare_ArticleShare_SendMail() {
	global $dpArticleShare;
	
	$your_name = $_POST['your_name'];
	$your_email = $_POST['your_email'];
	$to = $_POST['to'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$message .= "\n\r----------------------------\n\r".$dpArticleShare['i18n_email_email_sent_by'].": ".$your_name." <".$your_email.">";
	
	add_filter( 'wp_mail_from_name', 'dpArticleShare_wp_mail_from_name' );
	add_filter( 'wp_mail_from', 'dpArticleShare_wp_mail_from' );

	wp_mail( $to, $subject, $message );
	die();	
}

function dpArticleShare_wp_mail_from_name( $original_email_from )
{
	return get_bloginfo('name');
}

function dpArticleShare_wp_mail_from( $original_email_address )
{
	return str_replace("wordpress@", "no-reply@", $original_email_address);
}

add_action( 'wp_ajax_nopriv_updatePostShares', 'dpArticleShare_updatePostShares' );
add_action( 'wp_ajax_updatePostShares', 'dpArticleShare_updatePostShares' );

function dpArticleShare_updatePostShares($post_id = '', $die = true) {
	
	$nonce = $_POST['postEventsNonce'];
	//if ( ! wp_verify_nonce( $nonce, 'ajax-get-events-nonce' ) )
    //    die ( 'Busted!');
	
	if(!is_numeric($post_id)) {
		$post_id = $_POST['post_id'];
	}
	
	if(!is_numeric($post_id)) { die(); }
	
	$total = 0;
	
	$last_update = get_post_meta($post_id, 'share_last_update', true);
	if(!is_numeric($last_update) || $last_update <= (time() - (15 * 60))) {
		
		$url = get_permalink($post_id);
		$json_string = dpArticleShare_file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url .'&t='.time());
		$json = json_decode($json_string, true);
		$twitter = intval( $json['count'] );
		$total += $twitter;
		
		$json_string = dpArticleShare_file_get_contents("http://www.linkedin.com/countserv/count/share?url=".$url."&format=json&t=".time());
		$json = json_decode($json_string, true);
		$linkedin = intval( $json['count'] );
		$total += $linkedin;
		
		$json_string = dpArticleShare_file_get_contents('http://graph.facebook.com/?ids=' . $url . '&t='.time());
		$json = json_decode($json_string, true);
		$facebook = intval( $json[$url]['shares'] );
		if(get_post_meta($post_id, 'share_facebook', true) > $facebook) {
			$facebook = intval( get_post_meta($post_id, 'share_facebook', true) );
		}
		//$facebook = isset($json->data->share_count) ? intval( $json->data->share_count ) : 0;
		$total += $facebook;
		
		$json_string = dpArticleShare_file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . $url . '&t='.time());
		$json_string = str_replace( array('receiveCount(', ')'), '', $json_string );
		$json = json_decode($json_string, true);
		$pinterest = intval( $json['count'] );
		$total += $pinterest;
		
		$json_string = dpArticleShare_file_get_contents('http://feeds.delicious.com/v2/json/urlinfo/data?url=' . $url . '&t='.time());
		$json = json_decode($json_string, true);
		$delicious = isset($json[0]['total_posts'])?intval($json[0]['total_posts']):0;
		$total += $delicious;
		
		$json_string = dpArticleShare_file_get_contents('http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $url . '&t='.time());
		$json = json_decode($json_string, true);
		$stumbleupon = isset($json['result']['views']) ? intval($json['result']['views']) : 0;
		$total += $stumbleupon;
		
		$json_string = dpArticleShare_file_get_contents('http://widgets.digg.com/buttons/count?url=' . urlencode($url) . '&t='.time());
		$json = json_decode($json_string, true);
		$digg = isset($json['result']['views']) ? intval($json['result']['views']) : 0;
		$total += $digg;
		
		$json_string = dpArticleShare_file_get_contents('http://www.reddit.com/api/info.json?url=' . urlencode($url) . '&t='.time());
		$json = json_decode($json_string, true);
		$reddit = isset($json['data']['children'][0]['data']['score']) ? intval($json['data']['children'][0]['data']['score']) : 0;
		$total += $reddit;
		
		$string = dpArticleShare_file_get_contents('http://widgets.bufferapp.com/button/?url=' . urlencode($url) . '&t='.time());
		$string = substr($string, strpos($string, 'id="buffer_count"') + 18);
		$string = substr($string, 0, strpos($string, '</span>'));
		$buffer = is_numeric($string) ? intval($string) : 0;
		$total += $buffer;
		
		$string = dpArticleShare_file_get_contents('http://vk.com/share.php?act=count&url=' . urlencode($url) . '&t='.time());
		$shares = array();
		preg_match( '/^VK\.Share\.count\(\d, (\d+)\);$/i', $string, $shares );
		$vk = is_numeric($shares[ 1 ]) ? intval($shares[ 1 ]) : 0;
		$total += $vk;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
		$json = json_decode($curl_results, true);
		$plusone = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
		$total += $plusone;
		
		update_post_meta($post_id, 'share_gplus', $plusone);
		update_post_meta($post_id, 'share_twitter', $twitter);
		update_post_meta($post_id, 'share_linkedin', $linkedin);
		update_post_meta($post_id, 'share_facebook', $facebook);
		update_post_meta($post_id, 'share_pinterest', $pinterest);
		update_post_meta($post_id, 'share_delicious', $delicious);
		update_post_meta($post_id, 'share_stumbleupon', $stumbleupon);
		update_post_meta($post_id, 'share_digg', $digg);
		update_post_meta($post_id, 'share_reddit', $reddit);
		update_post_meta($post_id, 'share_buffer', $buffer);
		update_post_meta($post_id, 'share_vk', $vk);
		update_post_meta($post_id, 'share_last_update', time());
		
		update_post_meta($post_id, 'share_total', $total);
		
	}

	if($die) 
		die();
}

function dpArticleShare_get_post_shares($post_id) {
	$result = "";
	
	$gplus = get_post_meta($post_id, 'share_gplus', true);
	$twitter = get_post_meta($post_id, 'share_twitter', true);
	$linkedin = get_post_meta($post_id, 'share_linkedin', true);
	$facebook = get_post_meta($post_id, 'share_facebook', true);
	$pinterest = get_post_meta($post_id, 'share_pinterest', true);
	$delicious = get_post_meta($post_id, 'share_delicious', true);
	$stumbleupon = get_post_meta($post_id, 'share_stumbleupon', true);
	$digg = get_post_meta($post_id, 'share_digg', true);
	$tumblr = get_post_meta($post_id, 'share_tumblr', true);
	$reddit = get_post_meta($post_id, 'share_reddit', true);
	$buffer = get_post_meta($post_id, 'share_buffer', true);
	$vk = get_post_meta($post_id, 'share_vk', true);
	$blogger = get_post_meta($post_id, 'share_blogger', true);
	
	$result["gplus"] =  (is_numeric($gplus) ? $gplus : 0);
	$result["twitter"] = (is_numeric($twitter) ? $twitter : 0);
	$result["linkedin"] = (is_numeric($linkedin) ? $linkedin : 0);
	$result["facebook"] = (is_numeric($facebook) ? $facebook : 0);
	$result['pinterest'] = (is_numeric($pinterest) ? $pinterest : 0);
	$result["delicious"] = (is_numeric($delicious) ? $delicious : 0);
	$result['stumbleupon'] = (is_numeric($stumbleupon) ? $stumbleupon : 0);
	$result['digg'] = (is_numeric($digg) ? $digg : 0);
	$result['tumblr'] = (is_numeric($tumblr) ? $tumblr : 0);
	$result['reddit'] = (is_numeric($reddit) ? $reddit : 0);
	$result['buffer'] = (is_numeric($buffer) ? $buffer : 0);
	$result['vk'] = (is_numeric($vk) ? $vk : 0);
	$result['blogger'] = (is_numeric($blogger) ? $blogger : 0);
	
	if($post_id == "") {
		$url = home_url();
		$json_string = dpArticleShare_file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url .'&t='.time());
		$json = json_decode($json_string, true);
		$result["twitter"] = intval( $json['count'] );
		
		$json_string = dpArticleShare_file_get_contents('http://graph.facebook.com/?ids=' . $url . '&t='.time());
		$json = json_decode($json_string, true);
		$result["facebook"] = intval( $json[$url]['shares'] );
	}
	
	return $result;
}

function dpArticleShare_get_disqus_counter($thread) {
    global $dpArticleShare;
	
	$key	= $dpArticleShare['disqus_api_key']; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
	$forum	= $dpArticleShare['disqus_shortname']; //

	// construct the query with our apikey and the query we want to make
	// Change api_key to api_secret when using your secret key
	/*
			DIFFERENT TYPES OF THREAD LOOKUPS:
			1. By DISQUS thread ID (default): thread=%s — thread IDs are universally unique in DISQUS, so you can remove 'forum' param if you like
			2. By identifier: thread:ident=%s — requires the forum parameter
			3. By URL: thread:link=%s — requires the forum parameter
	*/
	$endpoint = 'http://disqus.com/api/3.0/threads/details.json?api_key='.urlencode($key).'&forum='.$forum.'&thread=link:'.urlencode($thread);

	// setup curl to make a call to the endpoint
	$session = curl_init($endpoint);

	// indicates that we want the response back rather than just returning a "TRUE" string
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute GET and get the session back
	$result = json_decode(curl_exec($session));

	// close connection
	curl_close($session);

	// show the response in the browser
	
	return (is_numeric($result->response->posts) ? $result->response->posts : 0);

}

function dpArticleShare_cur_page($tld = false, $port = true) {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80" && $port) {
		if($tld) {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
	} else {
		if($tld) {
			$pageURL .= $_SERVER["SERVER_NAME"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	}
	return $pageURL;
}
?>