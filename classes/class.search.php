<?
class Search extends Vimeo {
	public $results = array();
	public $result_type = array();
	public $result_info = array();
	private $keywords;
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_results( $search_str, $low_pri_albums = false ) {
		$search_str = @strtolower( trim( $search_str ) );
		$this->keywords = @explode( ' ', $search_str );
		$results_found = false;
		if( $this->get_vimeo_results( $low_pri_albums ) ) $results_found = true;
		if( $this->get_document_results() ) $results_found = true;
		return $results_found;
	}
	
	public function get_document_results() {
		$dir = 'content';
		$folders = scandir( $dir );
		$result_found = false;
		foreach( $folders as $folder ) {
			$doc_arr = array();
			$path = $dir . '/' . $folder;
			if( filetype( $path ) == 'dir' && substr( $folder, 0, 1 ) != '.' ) {
				$files = scandir( $path );
				foreach( $files as $entry ) {
					if( $entry != 'Thumbs.db' && substr( $entry, 0, 1 ) != '.' ) {
						$filepath = $path . '/' . $entry;
						$searchCount = 0;
						if( filetype( $filepath ) == 'file' ) {
							foreach( $this->keywords as $word ) {
								$searchCount += substr_count( strtolower( $filepath ), $word );
							}
							if($searchCount > 0) {
								if( !in_array( $folder, $this->result_type ) ) $this->result_type[] = $folder;
								$result_found = true;
								$filename = $this->get_document_info($filepath, $folder);
								$doc_arr[$filename] = $searchCount;
							}
						} elseif( filetype( $filepath ) == 'dir' ) {
							$subfiles = scandir( $filepath );
							foreach( $subfiles as $subentry ) {
								if( $subentry != 'Thumbs.db' && substr( $subentry, 0, 1 ) != '.' ) {
									$subfilepath = $filepath . '/' . $subentry;
									$searchCount = 0;
									if( filetype( $subfilepath ) == 'file' ) {
										foreach( $this->keywords as $word ) {
											$searchCount += substr_count( strtolower( $subfilepath ), $word );
										}
										if($searchCount > 0) {
											if( !in_array( $folder, $this->result_type ) ) $this->result_type[] = $folder;
											$result_found = true;
											$filename = $this->get_document_info($subfilepath, $folder);
											$doc_arr[$filename] = $searchCount;
										}
									}
								}
							}
						}
					}
				}
			}
			//finalize results
			$result_arr = array();
			if( count( $doc_arr ) > 0 ) {
				ksort( $doc_arr );
				//$priority = arsort( $doc_arr );
				$result_arr = array_keys( $doc_arr );
			}
			if( count( $result_arr ) > 0 ) {
				$this->results[$folder] = $result_arr;
			}
		}
		return $result_found;
	}
	
	private function get_document_info( $filepath, $folder ) {
		$types = $this->get_file_types();
		$file_info = pathinfo( $filepath );
		$title = $file_info['filename'];
		$ext = strtolower( substr( $file_info['extension'], 0, 3 ) );
		$this->result_info[$folder][$title]['path'] = $filepath;
		$this->result_info[$folder][$title]['title'] = $title;
		$this->result_info[$folder][$title]['ext'] = $ext;
		$this->result_info[$folder][$title]['type'] = $types[$ext] ? $types[$ext] : 'unknown';
		$thumbname = $file_info['dirname'] . '/thumbs/' . $title . '.png';
		if( file_exists( $thumbname ) )
			$this->result_info[$folder][$title]['thumb'] = $thumbname;
		return $file_info['filename'];
	}
	
	private function get_file_types() {
		$arr = array(
					'audio'			=> array( 'mp3', 'wav' ),
					'img'			=> array( 'bmp', 'jpg','gif','png' ),
					//'imgother'	=> array( 'tiff', 'eps' ),
					'msword'		=> array( 'doc', 'dot' ),
					'msexcel'		=> array( 'xls', 'xlt', 'xla' ),
					'mspowerpoint'	=> array( 'ppt', 'pot', 'ppa', 'pps' ),
					'msvisio'		=> array( 'vsd', 'vss', 'vst' ),
					'msaccess'		=> array( 'acc', 'mdb', 'snp' ),
					'msproject'		=> array( 'mpp' ),
					'msonenote'		=> array( 'one' ),
					'msoutlook'		=> array( 'pst', 'vcs' ),
					'mspublisher'	=> array( 'pub', 'puz' ),
					'adreader'		=> array( 'pdf' )
				);
		foreach( $arr as $type => $extensions ) {
			foreach( $extensions as $val ) {
				$types[$val] = $type;
			}
		}
		return $types;
	}
	
	public function get_vimeo_results( $low_pri_albums = false ) {
		/* Vimeo API search
		$response = $this->call('vimeo.videos.search', array( 'user_id' => $this->vimeo_user_id, 'query' => $search_str ) );
		$_SESSION['debug']['RESULTS'] = var_export($response,true); */
		/* DB search */
		$vid_arr = array(); $low_pri_arr = array();
		$fields = array( 'd.ID', 'a.aID', 'a.aTitle', 'a.aDescription', 'v.vID', 'v.vTitle', 'v.vDescription', 'v.vDuration', 'v.vThumbnail' );
		foreach( $this->keywords as $word ) {
			$sql = "SELECT " . implode( ',', $fields ) . " FROM " . SEARCH_VIDEO_TABLE . " v JOIN (" . SEARCH_ALBUM_TABLE . " a, " . FILES_DOCUMENTS_TABLE . " d) ON v.aID = a.aID AND v.vID = d.filename WHERE ";
			$where = array();
			foreach( $fields as $f ) {
				$where[] = $f . " LIKE '%" . $word . "%'";
			}
			$sql .= implode( ' OR ', $where );
			$result = @mysql_query($sql);
			while( $row = @mysql_fetch_assoc($result) ) {
				if( $low_pri_albums && @in_array( $row['aID'], $low_pri_albums ) ) {
					$low_pri_arr[] = $row['vID'];
				} else {
					$vid_arr[] = $row['vID'];
				}
				if( @!array_key_exists( $row['vID'], $this->result_info['videos'] ) ) {
					$this->result_info['videos'][$row['vID']] = $row;
				}
			}
		}
		$vid_arr = @array_filter($vid_arr, 'strlen');
		$low_pri_arr = @array_filter($low_pri_arr, 'strlen');
		$result_arr1 = array(); $result_arr2 = array();
		if( count( $vid_arr ) > 0 ) {
			$occurs = array_count_values( $vid_arr );
			arsort( $occurs );
			$result_arr1 = array_keys( $occurs );
			//$this->results['videos'] = $result_arr1;
			//return true;
		}
		if( count( $low_pri_arr ) > 0 ) {
			$occurs_low_pri = array_count_values( $low_pri_arr );
			arsort( $occurs_low_pri );
			$result_arr2 = array_keys( $occurs_low_pri );
			//$this->results['videos'] = $result_arr2;
			//return true;
		}
		if( count( $result_arr1 ) > 0 || count( $result_arr2 ) > 0 ) {
			$this->result_type[] = 'videos';
			$this->results['videos'] = array_merge( $result_arr1, $result_arr2 );
			return true;
		}
		return false;
	}
}
?>