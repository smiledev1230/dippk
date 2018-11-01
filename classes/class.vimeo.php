<?php

include 'vimeo/ExceptionInterface.php';
include 'vimeo/VimeoRequestException.php';
include 'vimeo/VimeoUploadException.php';

class Vimeo
{
    
	const ROOT_ENDPOINT = 'https://api.vimeo.com';
    const AUTH_ENDPOINT = 'https://api.vimeo.com/oauth/authorize';
    const ACCESS_TOKEN_ENDPOINT = '/oauth/access_token';
    const CLIENT_CREDENTIALS_TOKEN_ENDPOINT = '/oauth/authorize/client';
    const REPLACE_ENDPOINT = '/files';
    const VERSION_STRING = 'application/vnd.vimeo.*+json; version=3.2';
    const USER_AGENT = 'vimeo.php 1.2.6; (http://developer.vimeo.com/api/docs)';
    const CERTIFICATE_PATH = '/vimeo/certificates/vimeo-api.pem';
    
	//new
	public $site_prefix = false;
	public $vimeo_user_id;
    private $_cache_enabled = false;
    private $_cache_dir = false;
    private $_upload_md5s = array();
	private $total_albums;
	private $total_videos;
	private $videos;
	public $albums = array();
	private $_client_id = null;
    private $_client_secret = null;
    private $_access_token = null;

    protected $_curl_opts = array();
    protected $CURL_DEFAULTS = array();

	public function __construct()
    {
        $this->_client_id = "3d80582d5286e4fa28383ced83565d4aa1d40bdf";
        $this->_client_secret = "0e15582b6776e99be58d6322f9305320e94b0084";
        $this->_access_token = "7e9d25cce2fc29861626647f3a1680bb";
        $this->CURL_DEFAULTS = array(
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            //Certificate must indicate that the server is the server to which you meant to connect.
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => realpath(__DIR__) . self::CERTIFICATE_PATH
        );

        
    }
    
	public function player_url( $video_id, $api = false, $iframe_id = null ) {
		$api_ext = $api ? '?api=1&player_id=' . $iframe_id : '';
		//$api_ext = $api ? '?js_api=1&js_swf_id=' . $iframe_id : '';
		return 'http://player.vimeo.com/video/' . $video_id . $api_ext;
	}
	
	public function album_url( $album_id ) {
		return 'http://player.vimeo.com/hubnut/album/' . $album_id . '?color=0093d0&amp;background=000000&amp;slideshow=0&amp;video_title=1&amp;video_byline=0';
	}
	
	public function get_album_previews( $album_array, $limit = null ) {
		$albums = array();
		$counter = 1;
		if( $_SESSION['albums'] ) {
			foreach( $_SESSION['albums'] as $album ) {
				if( ( !$this->site_prefix || substr( $album['name'], 0, strlen( $this->site_prefix ) ) == $this->site_prefix )
					&& in_array( $album['id'], $album_array ) ) {
					$key = implode( '', array_keys( $album_array, $album['id'] ) );
					$albums[$key] = $album;
					$video = $this->call('/me/albums/'.$album['id'].'/videos', array('summary_response' => true));
					$albums[$key]['thumbnail'] = $video['body']['data']['0']['pictures']['sizes'][1]['link'];
					if( $counter == $limit ) break;
					$counter++;
				}
			}
			ksort( $albums );
		} else {
			$response = $this->call('/me/albums/videos', array('sort' => 'newest'));
			$albums = $response['body']['data'];
			$key = 0;
			foreach( $albums as $album ) {
				if( ( !$this->site_prefix || substr( $album->title, 0, strlen( $this->site_prefix ) ) == $this->site_prefix )
					&& in_array( $album->id, $album_array ) ) {
					$albums[$key] = (array) $album;
					$video = $this->call('/me/albums/'.$album->id.'/videos', array('summary_response' => true));
					$albums[$key]['thumbnail'] = $video['body']['data']['0']['pictures']['sizes'][1]['link'];
					$key++;
					if( $counter == $limit ) break;
					$counter++;
				}
			}
		}
		return $albums;
	}
	
	public function get_db_albums( $album_array ) {
		$sql = "SELECT * FROM " . SEARCH_ALBUM_TABLE . " WHERE aID IN (" . implode( ',', $album_array ) . ") ORDER BY priority, aTitle";
		$rs = mysql_query( $sql );
		$key = 0;
		while( $row = @mysql_fetch_assoc( $rs ) ) {
			$albums[$key]['id'] = $row['aID'];
			$albums[$key]['title'] = $row['aTitle'];
			$albums[$key]['total_videos'] = $row['aVideos'];
			$albums[$key]['thumbnail'] = $row['aThumb'];
			$key++;
		}
		return $albums;
	}
	
	public function get_album( $album_id ) {
		$album = array();
		if( $_SESSION['albums'] ) {
			foreach( $_SESSION['albums'] as $sessionAlbum ) {
				if( $sessionAlbum['id'] == $album_id ) {
					$album['name'] = $this->site_prefix ? substr( $sessionAlbum['name'], strlen( $this->site_prefix ) ) : $sessionAlbum['name'];
					$album['description'] = $sessionAlbum['description'];
				}	
			}
		} else {
			$response = $this->call('/me/albums', array());
			$vimeoAlbums = $response['body']['data'];
			foreach( $vimeoAlbums as $vimeoAlbum ) {
				$uri = $vimeoAlbum['uri'];
				$uri_break = explode("/", $uri);
				$video_id = $uri_break[2];

				if( $video_id == $album_id ) {
					$album['name'] = $this->site_prefix ? substr( $vimeoAlbum['name'], strlen( $this->site_prefix ) ) : $vimeoAlbum['name'];
					$album['description'] = $vimeoAlbum['description'];
				}
			}
		}
		$this->get_album_videos($album_id);
		$album['lessons'] = $this->total_videos;
		$album['runtime'] = 0;
		$album['videos'] = $this->videos;
		foreach( $album['videos'] as $key => $video ) {
			$album['runtime'] += $video['duration'];
			// get the video id too
			if( $video['created_time'] < $album['release_date'] || $key == 0 ) $album['release_date'] = $video['created_time'];
			if( $video['modified_time'] > $album['updated'] || $key == 0 ) $album['updated'] = $video['modified_date'];
		}
		return $album;
	}
	
	public function get_album_videos($album_id) {
		$this->videos = array();
		$page = 0;
		do {
			$page++;
			$this->get_videos_page( $album_id, $page );
		} while( ( $page * 50 ) < $this->total_videos );
		return $this->videos;
	}
	
	private function get_videos_page( $album_id, $page ) {
		// check if this has to be sorted according to date or not
		include 'inc/common.php';
		
        $response = $this->call('/me/albums/'.$album_id.'/videos', array('direction'=>'desc','sort'=>'date','page' => $page,'per_page'=>50, 'full_response' => true));
		
		$this->total_videos = $response['body']['total'];
		if( is_array( $response['body']['data']) ) {
			foreach( @$response['body']['data'] as $v ) {
				$uri = $v['uri'];
				$uri_break = explode("/", $uri);
				
				$video_id = $uri_break[2];
				$v['id']=$video_id;
				$this->videos[] = $v;
			}
		} else {
			return false;
		}
		return true;
	}
	
	public function get_video( $video_id ) {
		$response = $this->call('/videos/'.$video_id);
		return $response['body'];
	}
	
	public function build_album_array() {
		$page = 0;
		do {
			$page++;
			$this->get_albums_page( $page );
		} while( ( $page * 50 ) < $this->total_albums );
		return $this->albums;
	}
	
	private function get_albums_page( $page ) {
		$response = $this->call('/me/albums', array('page' => $page,'per_page'=>50));
		$this->total_albums = $response['body']['total'];
		if( is_array( $response['body']['data'] ) ) {
			foreach( @$response['body']['data'] as $a ) {
				if( !$this->site_prefix || strstr( $a['name'], $this->site_prefix ) ) {
					// break the uri and get the album id
					$uri = $a['uri'];
					$uri_break = explode("/", $uri);
					
					$album_id = $uri_break[4];

					$this->albums[$album_id] = $a;

					if( $this->site_prefix ) $this->albums[$album_id]['title'] = substr( $this->albums[$album_id]['title'], strlen( $this->site_prefix ) );
				}
			}
			
		} else {
			return false;
		}
		return true;
	}
	
	public function convert_duration( $duration ) {
		$dur_h = floor( $duration / 3600 );
		$dur_m = floor( ( $duration - $runtime_h * 3600 ) / 60 );
		$dur_s = $duration - ( $dur_h * 3600 + $dur_m * 60 );
		$runtime = ( $dur_h > 0 ) ? $dur_h . 'h ' : '';
		$runtime .= ( $dur_h == 0 && $dur_m == 0 ) ? '' : $dur_m . 'm ';
		$runtime .= $dur_s . 's';
		return $runtime;
	}
	
	public function convert_date( $date ) {
		$datetime = explode( ' ', $date );
		$d = explode( '-', $datetime[0] );
		$t = explode( ':', $datetime[1] );
		return date( 'F j, Y', mktime( $t[0], $t[1], $t[2], $d[1], $d[2], $d[0] ) );
	}
	
    public function update_database( $album_array ) {
		$priority_arr = array();
		$sql = "SELECT aID, priority FROM " . SEARCH_ALBUM_TABLE . " ORDER BY aID";
		$result = @mysql_query( $sql );
		while( $row = @mysql_fetch_assoc( $result ) ) {
			$priority_arr[$row['aID']] = $row['priority'];
		}
		ob_end_flush(); ob_flush(); flush();
		mysql_query("UPDATE " . SEARCH_ALBUM_TABLE . " SET sync = 'N'");
		mysql_query("UPDATE " . SEARCH_VIDEO_TABLE . " SET sync = 'N'");
		$page = 0;
		do{
			$page++;
			$response = $this->call('/me/albums', array( 'page' => $page,'per_page'=>50 ));

			foreach( $response['body']['data'] as $a ) {

				// break the uri and get the album id
				$uri = $a['uri'];
				$uri_break = explode("/", $uri);
				
				$album_id = $uri_break[4];

				echo str_pad( $album_id.'-'.$a['name'].'<br>', 4096 );
				ob_flush(); flush();
				if( ( !$this->site_prefix || strstr( $a['name'], $this->site_prefix ) ) && in_array( $album_id, $album_array ) ) {
					$this->get_album_videos($album_id);
					foreach( $this->videos as $v ) {
						$uri = $v['uri'];
						$uri_break = explode("/", $uri);
						$video_id = $uri_break[2];

						$sql = "INSERT INTO " . SEARCH_VIDEO_TABLE . " ( aID, vID, vTitle, vDescription, vDuration, vThumbnail, keywords, sync ) VALUES ( " . $album_id . ", " . $video_id . ", '" . mysql_real_escape_string($v['name']) . "', '" . mysql_real_escape_string($v['description']) . "', '" . $v['duration'] . "', '" . $v['pictures']['sizes'][1]['link'] . "', 'video," . mysql_real_escape_string($a['name']) . "," . mysql_real_escape_string($v['name']) . "," . mysql_real_escape_string($v['description']) . "', 'Y' ) ON DUPLICATE KEY UPDATE vTitle ='" . mysql_real_escape_string($v['name']) . "', vDescription ='" . mysql_real_escape_string($v['description']) . "', vDuration ='" . $v['duration'] . "', vThumbnail ='" . $v['pictures']['sizes'][1]['link'] . "', sync = 'Y'";
						mysql_query( $sql );
						echo str_pad( '-----'.$sql.'<br>', 4096 );
						ob_flush(); flush();
					}
					$sql = "INSERT INTO " . SEARCH_ALBUM_TABLE . " ( aID, aTitle, aDescription, aVideos, aThumb, sync";
					if( array_key_exists( $album_id, $priority_arr )) $sql .= ", priority";
					$sql .= " ) VALUES ( " . $album_id . ", '" . mysql_real_escape_string($a['name']) . "', '" . mysql_real_escape_string($a['description']) . "', '" . $a['metadata']['connections']['videos']['total'] . "', '" . $this->videos[0]['pictures']['sizes'][1]['link'] . "', 'Y'";
					if( array_key_exists( $album_id, $priority_arr )) $sql .= ", '" . $priority_arr[$album_id] . "'";
					$sql .= " ) ON DUPLICATE KEY UPDATE aTitle ='" . mysql_real_escape_string($a['name']) . "', aDescription ='" . mysql_real_escape_string($a['description']) . "', aVideos ='" . $a['metadata']['connections']['videos']['total'] . "', aThumb ='" . $this->videos[0]['pictures']['sizes'][1]['link'] . "', sync = 'Y'";
					mysql_query( $sql );
					echo str_pad( $sql.'<br>', 4096 );
					ob_flush(); flush();
				}
			}
		} while( ( $page * 50 ) <= $response['body']['total'] );
		//mysql_query("DELETE FROM " . SEARCH_ALBUM_TABLE . " WHERE sync = 'N'");
		//mysql_query("DELETE FROM " . SEARCH_VIDEO_TABLE . " WHERE sync = 'N'");
	}
	
	/**
     * Cache a response.
     *
     * @param array $params The parameters for the response.
     * @param string $response The serialized response data.
     */
    private function _cache($params, $response)
    {
        // Remove some unique things
        unset($params['oauth_nonce']);
        unset($params['oauth_signature']);
        unset($params['oauth_timestamp']);

        $hash = md5(serialize($params));

        if ($this->_cache_enabled == self::CACHE_FILE) {
            $file = $this->_cache_dir.'/'.$hash.'.cache';
            if (file_exists($file)) {
                unlink($file);
            }
            return file_put_contents($file, $response);
        }
    }

    /**
     * Create the authorization header for a set of params.
     *
     * @param array $oauth_params The OAuth parameters for the call.
     * @return string The OAuth Authorization header.
     */
    private function _generateAuthHeader($oauth_params)
    {
        $auth_header = 'Authorization: OAuth realm=""';

        foreach ($oauth_params as $k => $v) {
            $auth_header .= ','.self::url_encode_rfc3986($k).'="'.self::url_encode_rfc3986($v).'"';
        }

        return $auth_header;
    }

    /**
     * Generate a nonce for the call.
     *
     * @return string The nonce
     */
    private function _generateNonce()
    {
        return md5(uniqid(microtime()));
    }

    /**
     * Generate the OAuth signature.
     *
     * @param array $args The full list of args to generate the signature for.
     * @param string $request_method The request method, either POST or GET.
     * @param string $url The base URL to use.
     * @return string The OAuth signature.
     */
    private function _generateSignature($params, $request_method = 'GET', $url = self::API_REST_URL)
    {
        uksort($params, 'strcmp');
        $params = self::url_encode_rfc3986($params);

        // Make the base string
        $base_parts = array(
            strtoupper($request_method),
            $url,
            urldecode(http_build_query($params, '', '&'))
        );
        $base_parts = self::url_encode_rfc3986($base_parts);
        $base_string = implode('&', $base_parts);

        // Make the key
        $key_parts = array(
            $this->_consumer_secret,
            ($this->_token_secret) ? $this->_token_secret : ''
        );
        $key_parts = self::url_encode_rfc3986($key_parts);
        $key = implode('&', $key_parts);

        // Generate signature
        return base64_encode(hash_hmac('sha1', $base_string, $key, true));
    }

    /**
     * Get the unserialized contents of the cached request.
     *
     * @param array $params The full list of api parameters for the request.
     */
    private function _getCached($params)
    {
        // Remove some unique things
        unset($params['oauth_nonce']);
        unset($params['oauth_signature']);
        unset($params['oauth_timestamp']);

        $hash = md5(serialize($params));

        if ($this->_cache_enabled == self::CACHE_FILE) {
            $file = $this->_cache_dir.'/'.$hash.'.cache';
            if (file_exists($file)) {
                return unserialize(file_get_contents($file));
            }
        }
    }

    /**
     * Call an API method.
     *
     * @param string $method The method to call.
     * @param array $call_params The parameters to pass to the method.
     * @param string $request_method The HTTP request method to use.
     * @param string $url The base URL to use.
     * @param boolean $cache Whether or not to cache the response.
     * @param boolean $use_auth_header Use the OAuth Authorization header to pass the OAuth params.
     * @return string The response from the method call.
     */
    private function _request($url, $curl_opts = array())
    {
        // Merge the options (custom options take precedence).
        $curl_opts = $this->_curl_opts + $curl_opts + $this->CURL_DEFAULTS;

        // Call the API.
        $curl = curl_init($url);
        curl_setopt_array($curl, $curl_opts);
        $response = curl_exec($curl);
        $curl_info = curl_getinfo($curl);

        if(isset($curl_info['http_code']) && $curl_info['http_code'] === 0){
            $curl_error = curl_error($curl);
            $curl_error = !empty($curl_error) ? '[' . $curl_error .']' : '';
            throw new VimeoRequestException('Unable to complete request.' . $curl_error);
        }

        curl_close($curl);

        // Retrieve the info
        $header_size = $curl_info['header_size'];
        $headers = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        // Return it raw.
        return array(
            'body' => $body,
            'status' => $curl_info['http_code'],
            'headers' => self::parse_headers($headers)
        );
    }

    /**
     * Send the user to Vimeo to authorize your app.
     * http://www.vimeo.com/api/docs/oauth
     *
     * @param string $perms The level of permissions to request: read, write, or delete.
     */
    public function auth($permission = 'read', $callback_url = 'oob')
    {
        $t = $this->getRequestToken($callback_url);
        $this->setToken($t['oauth_token'], $t['oauth_token_secret'], 'request', true);
        $url = $this->getAuthorizeUrl($this->_token, $permission);
        header("Location: {$url}");
    }

    /**
     * Call a method.
     *
     * @param string $method The name of the method to call.
     * @param array $params The parameters to pass to the method.
     * @param string $request_method The HTTP request method to use.
     * @param string $url The base URL to use.
     * @param boolean $cache Whether or not to cache the response.
     * @return array The response from the API method
     */
    public function call($url, $params = array(), $method = 'GET', $json_body = true)
    {
        // add accept header hardcoded to version 3.0
        $headers[] = 'Accept: ' . self::VERSION_STRING;
        $headers[] = 'User-Agent: ' . self::USER_AGENT;
        $method = strtoupper($method);

        // add bearer token, or client information
        if (!empty($this->_access_token)) {
            $headers[] = 'Authorization: Bearer ' . $this->_access_token;
        }
        else {
            //  this may be a call to get the tokens, so we add the client info.
            $headers[] = 'Authorization: Basic ' . $this->_authHeader();
        }

        //  Set the methods, determine the URL that we should actually request and prep the body.
        $curl_opts = array();
        switch ($method) {
            case 'GET' :
                if (!empty($params)) {
                    $query_component = '?' . http_build_query($params, '', '&');
                } else {
                    $query_component = '';
                }

                $curl_url = self::ROOT_ENDPOINT . $url . $query_component;
                break;

            case 'POST' :
            case 'PATCH' :
            case 'PUT' :
            case 'DELETE' :
                if ($json_body && !empty($params)) {
                    $headers[] = 'Content-Type: application/json';
                    $body = json_encode($params);
                } else {
                    $body = http_build_query($params, '', '&');
                }

                $curl_url = self::ROOT_ENDPOINT . $url;
                $curl_opts = array(
                    CURLOPT_POST => true,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $body
                );
                break;
        }

        // Set the headers
        $curl_opts[CURLOPT_HTTPHEADER] = $headers;

        $response = $this->_request($curl_url, $curl_opts);
        

        $response['body'] = json_decode($response['body'], true);

        return $response;
    }

    /**
     * Enable the cache.
     *
     * @param string $type The type of cache to use (phpVimeo::CACHE_FILE is built in)
     * @param string $path The path to the cache (the directory for CACHE_FILE)
     * @param int $expire The amount of time to cache responses (default 10 minutes)
     */
    public function enableCache($type, $path, $expire = 600)
    {
        $this->_cache_enabled = $type;
        if ($this->_cache_enabled == self::CACHE_FILE) {
            $this->_cache_dir = $path;
            $files = scandir($this->_cache_dir);
            foreach ($files as $file) {
                $last_modified = filemtime($this->_cache_dir.'/'.$file);
                if (substr($file, -6) == '.cache' && ($last_modified + $expire) < time()) {
                    unlink($this->_cache_dir.'/'.$file);
                }
            }
        }
        return false;
    }

    /**
     * Get an access token. Make sure to call setToken() with the
     * request token before calling this function.
     *
     * @param string $verifier The OAuth verifier returned from the authorization page or the user.
     */
    public function getAccessToken($verifier)
    {
        $access_token = $this->_request(null, array('oauth_verifier' => $verifier), 'GET', self::API_ACCESS_TOKEN_URL, false, true);
        parse_str($access_token, $parsed);
        return $parsed;
    }

    /**
     * Get the URL of the authorization page.
     *
     * @param string $token The request token.
     * @param string $permission The level of permissions to request: read, write, or delete.
     * @param string $callback_url The URL to redirect the user back to, or oob for the default.
     * @return string The Authorization URL.
     */
    public function getAuthorizeUrl($token, $permission = 'read')
    {
        return self::API_AUTH_URL."?oauth_token={$token}&permission={$permission}";
    }

    /**
     * Get a request token.
     */
    public function getRequestToken($callback_url = 'oob')
    {
        $request_token = $this->_request(
            null,
            array('oauth_callback' => $callback_url),
            'GET',
            self::API_REQUEST_TOKEN_URL,
            false,
            false
        );

        parse_str($request_token, $parsed);
        return $parsed;
    }

    /**
     * Get the stored auth token.
     *
     * @return array An array with the token and token secret.
     */
    public function getToken()
    {
        return array($this->_token, $this->_token_secret);
    }

    /**
     * Set the OAuth token.
     *
     * @param string $token The OAuth token
     * @param string $token_secret The OAuth token secret
     * @param string $type The type of token, either request or access
     * @param boolean $session_store Store the token in a session variable
     * @return boolean true
     */
    public function setToken($token, $token_secret, $type = 'access', $session_store = false)
    {
        $this->_token = $token;
        $this->_token_secret = $token_secret;

        if ($session_store) {
            $_SESSION["{$type}_token"] = $token;
            $_SESSION["{$type}_token_secret"] = $token_secret;
        }

        return true;
    }

    /**
     * Upload a video in one piece.
     *
     * @param string $file_path The full path to the file
     * @param boolean $use_multiple_chunks Whether or not to split the file up into smaller chunks
     * @param string $chunk_temp_dir The directory to store the chunks in
     * @param int $size The size of each chunk in bytes (defaults to 2MB)
     * @return int The video ID
     */
    public function upload($file_path, $use_multiple_chunks = false, $chunk_temp_dir = '.', $size = 2097152, $replace_id = null)
    {
        if (!file_exists($file_path)) {
            return false;
        }

        // Figure out the filename and full size
        $path_parts = pathinfo($file_path);
        $file_name = $path_parts['basename'];
        $file_size = filesize($file_path);

        // Make sure we have enough room left in the user's quota
        $quota = $this->call('vimeo.videos.upload.getQuota');
        if ($quota->user->upload_space->free < $file_size) {
            throw new VimeoAPIException('The file is larger than the user\'s remaining quota.', 707);
        }

        // Get an upload ticket
        $params = array();

        if ($replace_id) {
            $params['video_id'] = $replace_id;
        }

        $rsp = $this->call('vimeo.videos.upload.getTicket', $params, 'GET', self::API_REST_URL, false);
        $ticket = $rsp->ticket->id;
        $endpoint = $rsp->ticket->endpoint;

        // Make sure we're allowed to upload this size file
        if ($file_size > $rsp->ticket->max_file_size) {
            throw new VimeoAPIException('File exceeds maximum allowed size.', 710);
        }

        // Split up the file if using multiple pieces
        $chunks = array();
        if ($use_multiple_chunks) {
            if (!is_writeable($chunk_temp_dir)) {
                throw new Exception('Could not write chunks. Make sure the specified folder has write access.');
            }

            // Create pieces
            $number_of_chunks = ceil(filesize($file_path) / $size);
            for ($i = 0; $i < $number_of_chunks; $i++) {
                $chunk_file_name = "{$chunk_temp_dir}/{$file_name}.{$i}";

                // Break it up
                $chunk = file_get_contents($file_path, FILE_BINARY, null, $i * $size, $size);
                $file = file_put_contents($chunk_file_name, $chunk);

                $chunks[] = array(
                    'file' => realpath($chunk_file_name),
                    'size' => filesize($chunk_file_name)
                );
            }
        }
        else {
            $chunks[] = array(
                'file' => realpath($file_path),
                'size' => filesize($file_path)
            );
        }

        // Upload each piece
        foreach ($chunks as $i => $chunk) {
            $params = array(
                'oauth_consumer_key'     => $this->_consumer_key,
                'oauth_token'            => $this->_token,
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_timestamp'        => time(),
                'oauth_nonce'            => $this->_generateNonce(),
                'oauth_version'          => '1.0',
                'ticket_id'              => $ticket,
                'chunk_id'               => $i
            );

            // Generate the OAuth signature
            $params = array_merge($params, array(
                'oauth_signature' => $this->_generateSignature($params, 'POST', self::API_REST_URL),
                'file_data'       => '@'.$chunk['file'] // don't include the file in the signature
            ));

            // Post the file
            $curl = curl_init($endpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $rsp = curl_exec($curl);
            curl_close($curl);
        }

        // Verify
        $verify = $this->call('vimeo.videos.upload.verifyChunks', array('ticket_id' => $ticket));

        // Make sure our file sizes match up
        foreach ($verify->ticket->chunks as $chunk_check) {
            $chunk = $chunks[$chunk_check->id];

            if ($chunk['size'] != $chunk_check->size) {
                // size incorrect, uh oh
                echo "Chunk {$chunk_check->id} is actually {$chunk['size']} but uploaded as {$chunk_check->size}<br>";
            }
        }

        // Complete the upload
        $complete = $this->call('vimeo.videos.upload.complete', array(
            'filename' => $file_name,
            'ticket_id' => $ticket
        ));

        // Clean up
        if (count($chunks) > 1) {
            foreach ($chunks as $chunk) {
                unlink($chunk['file']);
            }
        }

        // Confirmation successful, return video id
        if ($complete->stat == 'ok') {
            return $complete->ticket->video_id;
        }
        else if ($complete->err) {
            throw new VimeoAPIException($complete->err->msg, $complete->err->code);
        }
    }

    /**
     * Upload a video in multiple pieces.
     *
     * @deprecated
     */
    public function uploadMulti($file_name, $size = 1048576)
    {
        // for compatibility with old library
        return $this->upload($file_name, true, '.', $size);
    }

    /**
     * URL encode a parameter or array of parameters.
     *
     * @param array/string $input A parameter or set of parameters to encode.
     */
    public static function url_encode_rfc3986($input)
    {
        if (is_array($input)) {
            return array_map(array('Vimeo', 'url_encode_rfc3986'), $input);
        }
        else if (is_scalar($input)) {
            return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));
        }
        else {
            return '';
        }
    }


    /**
     * Convert the raw headers string into an associated array
     *
     * @param string $headers
     * @return array
     */
    public static function parse_headers($headers)
    {
        $final_headers = array();
        $list = explode("\n", trim($headers));

        $http = array_shift($list);

        foreach ($list as $header) {
            $parts = explode(':', $header, 2);
            $final_headers[trim($parts[0])] = isset($parts[1]) ? trim($parts[1]) : '';
        }

        return $final_headers;
    }

    public function update_album_videos($album_id){
        $videos = $this->get_album_videos($album_id);
        //first delete all the videos from the database
        $sql = "DELETE FROM search_video ";
        mysql_query($sql);

        foreach($videos as $video){
            $aID            = $album_id;
            $vID            = $video['id'];
            $vTitle         = $video['name'];
            $vDescription   = $video['description'];
            $vDuration      = $video['duration'];
            $vThumbnail     = @$video['pictures']['sizes'][2]['link'];
            $width          = $video['width'];
            $height         = $video['height'];

            echo $sql = "INSERT INTO search_video(aID,vID,vTitle,vDescription,vDuration,vThumbnail,width,height,sync) VALUES('$aID','$vID','$vTitle','$vDescription','$vDuration','$vThumbnail','$width','$height','Y')";
            echo "<br>";
            echo "<br>";
            mysql_query($sql);
        }
    }

    public function get_db_videos(){
        $sql = "SELECT * FROM search_video";
        $result = mysql_query($sql);
        $videos = array();
        while($row = mysql_fetch_assoc($result)){
            $videos[] = $row;
        }
        return $videos;
    }

    

}

//class VimeoAPIException extends Exception {}
