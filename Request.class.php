<?php 
/**
* Wrapper Class for sending cURL requests
*/

class Request {

	/**
	* @param $options = [
	* 	'headers' => [], //non-associtive array ex ["Content-Type: text/html"]
	*	'type' => string, get/post,
	*	'fields' => [], //associative array
	*	'payload' => string,
	*	'cookies' => boolean //enables cookies,
	*	'cookie_path' => string //if not set, saves to /tmp/cookies.txt,
	* ]
	*
	*/
	public static function http_request($url, $options) {
		

		if (isset($options['type']) && strtolower($options['type']) == "post") {
			$type = 1;

			$curl = curl_init($url);

			curl_setopt($curl,CURLOPT_POST,1);

			if (isset($options['fields']))
				curl_setopt($curl,CURLOPT_POSTFIELDS,$options['fields']);
			elseif (isset($options['payload']))
				curl_setopt($curl,CURLOPT_POSTFIELDS,$options['payload']);
		}
		elseif (isset($options['fields']) && count($options['fields']) > 0) {
			$query_params = http_build_query($options['fields']);

			$url .= "?".$query_params;

			$curl = curl_init($url);
		}
		else 
			$curl = curl_init($url);

		if ($options) {
			if (isset($options['cookies']) && $options['cookies']) {

				$cookiePath = "/tmp/cookies.txt";
				if (isset($options['cookie_path'])) 
					$cookiePath = $options['cookie_path'];

				curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiePath);
				curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiePath);
			}
		}

		$headers = [];
		if (isset($options['headers']))
			$headers = $options['headers'];

		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_HTTPHEADER => $headers
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

	//shorthand function
	public static function get($url, $fields = [], $headers=[])
	{
		return self::http_request($url,[
			'type' => 'get',
			'headers' => $headers,
			'fields' => $fields
		]);
	}

	public static function post($url, $fields = [], $headers=[])
	{
		return self::http_request($url,[
			'type' => 'post',
			'headers' => $headers,
			'fields' => $fields
		]);
	}
}
