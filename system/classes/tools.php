<?php
	class Tools {
		
		static function get_youtube_data($video_url) 
		{			
			$api = 'https://gdata.youtube.com/feeds/api/videos/';
			$params = 'v=2&alt=json';
			
			parse_str(parse_url($video_url, PHP_URL_QUERY), $results);
			if(isset($results['v'])) { 
				$api_video_url = $api . $results['v'] . '?' . $params;
				
				$response = file_get_contents($api_video_url);
				$rdata = json_decode($response, true);
						
				$data = array();
				$data['id'] = $results['v'];
				$data['title'] = $rdata['entry']['title']['$t'];
				$data['description'] = $rdata['entry']['media$group']['media$description']['$t'];
				$data['keywords'] = isset($rdata['entry']['media$group']['media$keywords']['$t']) ? $rdata['entry']['media$group']['media$keywords']['$t'] : '';
				$data['author'] = array('name' => $rdata['entry']['author'][0]['name']['$t'], 'url' => $rdata['entry']['author'][0]['uri']['$t']);
				$data['thumbnail'] = $rdata['entry']['media$group']['media$thumbnail'][0]['url'];
				$data['duration'] = $rdata['entry']['media$group']['yt$duration']['seconds'];
				
				return $data;
			}
			else {
				echo "WRONG URL: " . $video_url;
			}	
			return null;
		}
		
		static function secs2mins($seconds=0)
		{
			if($seconds >= 3600) {
				return gmdate("H:i:s", $seconds);
			}
			else {
				return gmdate("i:s", $seconds);
			}
		}

		static function abbreviate_number($size, $postsign=false) {
			
			$sizes = $postsign ? array("", "K+", "M+") : array("", "K", "M");
			if ($size == 0) { return('0'); } else {
			return (round($size/pow(1000, ($i = floor(log($size, 1000)))), 0) . $sizes[$i]); }
		}		
		
		static function save_image($image, $id) {
			if (!Upload::valid($image) OR !Upload::not_empty($image) OR !Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
			{
				return FALSE;
			}	 
			$directory = DOCROOT.'assets/img/pictures/';
	 
			if ($file = Upload::save($image, NULL, $directory))
			{
				$filename = strtolower(sha1('user-picture-'.$id)).'.jpg';
				$
	 
				$img = Image::factory($file);
				/* $img = $img->resize(200, 200, Image::AUTO); */
				$img->save($directory.$filename);	 
				// Delete the temporary file
				unlink($file);
	 
				return $filename;
			}	 
			return FALSE;
		}
		
		static function timestamptolongformat($timestamp) { return date("d/m/y h:i:s", $timestamp);	}
		static function timestamptoshortformat($timestamp) { return date("d/m/y", $timestamp);	}
		static function timestamptodescriptiveformat($timestamp) { return date("D j M Y \o\\n H:i:s", $timestamp); }
		
		static function strendswith($haystack, $needle)
		{
			$length = strlen($needle);
			if ($length == 0) {
				return true;
			}
			return (substr($haystack, -$length) === $needle);
		}		
		
		static function strappends($str) {
			if(Tools::strendswith($str, 's')) {
				return $str."'";
			}
			else {
				return $str."'s";
			}
		}
		
		static function validatemail($email){
			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}
		
	}
	
?>