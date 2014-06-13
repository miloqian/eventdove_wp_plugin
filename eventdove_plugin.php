<?php
/*
Plugin Name:EventDove Plugin
Plugin URI: https://eventdove.com
Description: EventDove Plugin
Version: 1.0
Author: Milo
Author URI: http://www.eventdove.com
License: GPL2
*/


if(!class_exists('EventDove_Plugin'))
{
	class EventDove_Plugin
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$WP_Plugin_Template_Settings = new EventDove_Plugin_Settings();

			// Register custom post types
//			require_once(sprintf("%s/post-types/post_type_template.php", dirname(__FILE__)));
//			$Post_Type_Template = new Post_Type_Template();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

        /**
         *
         * return partner event list
         *
         * @return array|string
         */
        public function partner_event_list(){
            $access_token   = get_option('access_token');
             $r  = self::uc_fopen("http://eventdove.com/api/event_list.do?access_token=".$access_token,0,"",'',FALSE,'',1500,TRUE);
            $ar = json_decode($r,true);
            $respcode =  $ar['errorCode'];
            $respstr = "";
            if($respcode = '90000'){
                $events = $ar['returnObject'];
                $retEvents =[];
                for($i =0; $i<sizeof($events);$i++){
                    $e = $events[$i];
                    $pubstatus = $e['pubStatus'];
                    if($pubstatus =='1' || $pubstatus =='2' || $pubstatus == '3'){
                        array_push($retEvents,$e);
                    }
                }
                krsort($retEvents['eventId']);
                return $retEvents;
            }
            return '';
        }


        //return event array list
        public  function event_list_array(){
            $access_token   = get_option('access_token');
            $r  = self::uc_fopen("http://eventdove.com/api/event_list.do?access_token=".$access_token,0,"",'',FALSE,'',1500,TRUE);
            $ar = json_decode($r,true);
            $respcode =  $ar['errorCode'];
            $respstr = "";
            if($respcode = '90000'){
                 $events = $ar['returnObject'];
                 $retEvents =array();
                for($i =0; $i<sizeof($events);$i++){
                    $e = $events[$i];
                    $pubstatus = $e['pubStatus'];
                    if($pubstatus =='1' || $pubstatus =='2' || $pubstatus == '3'){
                       array_push($retEvents,$e);
                    }
                }
                krsort($retEvents['eventId']);
                 return $retEvents;
            }
            return '';

        }

        public function set_eventdove_category(){
            $event_category   = get_option('event_category_name');
            wp_insert_term($event_category,'category',array('description' => 'EventDove event list category','slug' => 'eventdove-event-category'));
        }

        public  function compare_event($a,$b){
                  if($a['pubStatus'] == $b['pubStatus']) {
                    return 0 ;
                }
                return ($a['pubStatus']< $b['pubStatus']) ? 1: -1;
        }

        public function attendee_list($eventId){


        }

         public function event_list(){
             $access_token   = get_option('access_token');
            $r  = self::uc_fopen("http://eventdove.com/api/event_list.do?access_token=".$access_token,0,"",'',FALSE,'',1500,TRUE);
            $ar = json_decode($r,true);
            $respcode =  $ar['errorCode'];
            $respstr = "";
            if($respcode = '90000'){
                $respstr = $respcode.'<table>';
                $events = $ar['returnObject'];
//                 usort($events,  array('this', 'compare_event'));
                for($i =0; $i<sizeof($events);$i++){
                    $e = $events[$i];
                   $respstr = $respstr.'<tr><td>';
                    $respstr = $respstr.$e['eventTitle'].'</td><td>'.$e['pubStatus'];

                    $respstr = $respstr.'</td></tr>';
                }
                $respstr = $respstr.'</table>';
                return $respstr;
            }else{
                $respstr = 'no response date,errorcode is'.$respcode;

            }
            return $respstr;
        }


        /**
         * 远程打开URL
         * @param string $url   打开的url，　如 http://www.baidu.com/123.htm
         * @param int $limit   取返回的数据的长度
         * @param string $post   要发送的 POST 数据，如uid=1&password=1234
         * @param string $cookie 要模拟的 COOKIE 数据，如uid=123&auth=a2323sd2323
         * @param bool $bysocket TRUE/FALSE 是否通过SOCKET打开
         * @param string $ip   IP地址
         * @param int $timeout   连接超时时间
         * @param bool $block   是否为阻塞模式
         * @return    取到的字符串
         */
        public  function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
            $return = '';
            $matches = parse_url($url);
            !isset($matches['host']) && $matches['host'] = '';
            !isset($matches['path']) && $matches['path'] = '';
            !isset($matches['query']) && $matches['query'] = '';
            !isset($matches['port']) && $matches['port'] = '';
            $host = $matches['host'];
            $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
            $port = !empty($matches['port']) ? $matches['port'] : 80;
            if($post) {
                $out = "POST $path HTTP/1.0\r\n";
                $out .= "Accept: */*\r\n";
                //$out .= "Referer: $boardurl\r\n";
                $out .= "Accept-Language: zh-cn\r\n";
                $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
                $out .= "Host: $host\r\n";
                $out .= 'Content-Length: '.strlen($post)."\r\n";
                $out .= "Connection: Close\r\n";
                $out .= "Cache-Control: no-cache\r\n";
                $out .= "Cookie: $cookie\r\n\r\n";
                $out .= $post;
            } else {
                $out = "GET $path HTTP/1.0\r\n";
                $out .= "Accept: */*\r\n";
                //$out .= "Referer: $boardurl\r\n";
                $out .= "Accept-Language: zh-cn\r\n";
                $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
                $out .= "Host: $host\r\n";
                $out .= "Connection: Close\r\n";
                $out .= "Cookie: $cookie\r\n\r\n";
            }
            $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
            if(!$fp) {
                return 'Error';//note $errstr : $errno \r\n
            } else {
                stream_set_blocking($fp, $block);
                stream_set_timeout($fp, $timeout);
                @fwrite($fp, $out);
                $status = stream_get_meta_data($fp);
                if(!$status['timed_out']) {
                    while (!feof($fp)) {
                        if(($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                            break;
                        }
                    }

                    $stop = false;
                    while(!feof($fp) && !$stop) {
                        $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                        $return .= $data;
                        if($limit) {
                            $limit -= strlen($data);
                            $stop = $limit <= 0;
                        }
                    }
                }
                @fclose($fp);
                return $return;
            }
        }




        /**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=eventdove_plugin">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}


	} // END class WP_Plugin_Template
} // END if(!class_exists('WP_Plugin_Template'))

if(class_exists('EventDove_Plugin'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('EventDove_Plugin', 'activate'));
	register_deactivation_hook(__FILE__, array('EventDove_Plugin', 'deactivate'));

	// instantiate the plugin class
	$eventdove_plugin = new EventDove_Plugin();
    add_action( 'after_setup_theme', 'set_eventdove_category' );

//    add_filter('the_content', array('EventDove_Plugin', 'event_list'));
}
