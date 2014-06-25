<?php namespace PrettyUrl;


class PrettyUrl {

	private $url;
	private $file;
	private $maxLen;
	private $blackList = array();

	private $replaceChars = array(
		'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i',
		'ò' => 'o', 'ù' => 'u', 'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u', 'â' => 'a',
		'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u', 'ã' => 'a', 'õ' => 'o', 'ű' => 'u', 'ý' => 'y',
		'ç' => 'c', 'ñ' => 'n', 'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z',
		'η' => 'h', 'θ' => '8', 'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3',
		'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps',
		'ω' => 'w', 'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's', 'ϊ' => 'i',
		'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i', 'ş' => 's', 'ı' => 'i', 'ğ' => 'g', 'б' => 'b', 'в' => 'v',
		'г' => 'g', 'д' => 'd', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
		'л' => 'l', 'м' => 'm', 'н' => 'n', 'п' => 'p', 'т' => 't', 'ф' => 'f', 'ц' => 'c', 'ч' => 'ch',
		'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '№' => '',
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z', 'ă' => 'a', 'ș' => 's', 'ț' => 't'
	);


	/**
	 * Initialize the url and define the black list
	 *
	 * @param string  $url  the url to encode
	 * @param bool    $file if the url is a file or not(default false)
	 * @param integer $maxLen the maximum URL text size(default 0 that is unlimited)
	 * @throws Exception if the url is invalid
	 */


	public function __construct($url, $file = false, $maxLen = 0){
		if(is_string($url) && !empty(trim($url))){
			$this->url = $url;
			$this->file = (is_bool($file)) ? $file : false;
			$this->maxLen = (is_int($maxLen) && $maxLen > 0) ? $maxLen : 0;
		}else{
			throw new \Exception("The url can't be empty");
		}
	}


	/**
	 * set the url to other
	 *
	 * @param string $url the url to encode
	 * @return bool true if is a valid string false if it is invalid
	 */


	public function setUrl($url){
		if(is_string($url) && !empty(trim($url))){
			$this->url = $url;
			return true;
		}

		return false;
	}


	/**
	 * return the url encoded or not encoded
	 *
	 * @return string the url
	 */


	public function getUrl(){
		return $this->url;
	}


	/**
	 * set the type of file URL to encode
	 *
	 * @param bool $isFile if the URL must be for files
	 */


	public function setType($isFile) {
		$this->file = (!is_bool($isFile)) ? false : $isFile;
	}


	/**
	 * return the url type to encode or encoded
	 *
	 * @return string the type of link
	 * 			f --> file, d --> normal link
	 */


	public function getType(){
		return ($this->file) ? 'f' : 'd';
	}


	/**
	 * set the maximum length that can have the URL
	 * 
	 * @param integer $length the maximun URL length
	 *			(0 by default)
	 */


	public function setMaxLength($length) {
		$this->maxLen = (is_int($length) && $length > 0) ? $length : 0;
	}


	/**
	 * return the maximum length
	 *
	 * @return integer the maximum length
	 */


	public function getMaxLength() {
		return $this->maxLen;
	}


	/**
	 * set the url words or numbers to delete
	 *
	 * @param mixed $list an array, string or number to add
	 * 			to the black list
	 * @return bool false if is $list is an object or a bool
	 *			or if the list is empty. true if is an array,
	 * 			number or a string
	 */


	public function setBlackList($list){

		if(is_object($list) || is_bool($list)){
			return false;
		}

		if ((is_string($list) || is_integer($list) || is_float($list)) && !empty($list)) {
			$this->blackList[] = $list;
			return true;
		} elseif (is_array($list) && count($list)) {
			
			$keys = array_keys($list);
			$keyLength = count($list);
			$valid_val = array();
			
			for ($i = $keyLength - 1; $i >= 0; $i--) {
				if (is_int($keys[$i]) && (!is_array($list[$keys[$i]]) && !is_object($list[$keys[$i]]))) {
					if (is_string($keys[$i])) {
						$valid_val[] = strtolower($list[$keys[$i]]);
					} else {
						$valid_val[] = $list[$keys[$i]];
					}
				}
			}

			$valid_val = array_unique($valid_val);

			if (count($valid_val) == 0) {
				return false;
			}

			foreach ($valid_val as $val) {
				$this->blackList[] = $val;
			}

			return true;
		}else{
			return false;
		}
	}


	/**
	 * returns the words that are banned
	 *
	 * @return array the black list words
	 */


	public function getBlackList(){
		return $this->blackList;
	}


	/**
	 * load an array(dictionary of words with key/values)
	 *
	 * @param string $dict the filename to load
	 * @return bool false if the file doesn't exists
	 */


	public function loadDict ($dict) {

		if (!file_exists('dict/'. $dict .'.php')) {
			return false;
		}

		$dict = require_once 'dict/'. $dict .'.php';

		if (is_array($dict) && count($dict)) {

			foreach ($dict as $key => $val) {
				if (is_string($key) && !array_key_exists(strtolower($key), $this->replaceChars)) {
					$this->replaceChars[strtolower($key)] = strtolower($val);
				}
			}

		}

	}


	/**
	 * add new chars to translate it to a valid
	 * character for the URL and remove repeated
	 * key/values
	 *
	 * @param array $chars array with key and value
	 * @return bool false if $chars is not a valid associative array
	 */


	public function addChars($chars) {
		if (!is_array($chars) && !count($chars)) {
			return false;
		}

		$len = count($this->replaceChars);
		$keys = array_keys($chars);
		$validChars = false;

		foreach ($keys as $key) {
			if(!is_string($key)) {
				$validChars = false;
				break;
			}else{
				$validChars = true;
			}
		}

		if ($validChars) {
			foreach ($chars as $key => $val) {
				if(!array_key_exists($key, $this->replaceChars)){
					$this->replaceChars[$key] = $val;
				}
			}

			array_unique($this->replaceChars);
		}
	}


	/**
	 * removes all strings or numbers inside
	 * the url text
	 *
	 * @return false if the url to modify is empty
	 */

	
	private function _filter(){
		$len = count($this->blackList);

		if ($len == 0) {
			return false;
		}

		for ($i = $len - 1; $i >= 0; $i--) {
			$this->url = str_replace($this->blackList[$i], '', $this->url);
		}
	}

	
	/**
	 * replace invalid url chars for valid chars
	 * or similars
	 */

	
	private function _replaceChars(){
		$len = strlen($this->url);
		
		for($i = 0; $i < $len; $i++){

			foreach($this->replaceChars as $key => $val){
				if(mb_substr($this->url, $i, 1, 'utf-8') == $key){
					$this->url[$i] = $val;

					if(ord($this->url[$i + 1]) != 0){
						$this->url[$i + 1] = null;  // to avoid ? character
					}
				}
			}
		}
	}


	/**
	 * short the URL to the defined length
	 *
	 * @return string the URL shortened
	 */

	
	private function _shortener () {
		if ($this->maxLen == 0 || $this->maxLen > strlen($this->url)) {
			return $this->url;
		}

		return substr($this->url, 0, $this->maxLen);
	}


	/**
	 * encode the data to make it valid for websites
	 * or forums
	 *
	 * @return mixed false if a file is invalid
	 * 			or a the URL encoded for search engines
	 */


	public function Urlify(){
		$this->url = trim(strtolower($this->url));
		$this->_filter();
		$this->_replaceChars();
		$this->url = str_replace('_', ' ', $this->url);
		
		// if is not a file the string then encode
		if (!$this->file) {
			
			$this->url = preg_replace(
				'#(\.|&|%|\$|\^|\'|\"|@|\#|\(|\)|\[|\]|\?|\¿|\!|\¡|/|\¬|\=|\·|:|;|\+|\,|\`|\||€|£|\\\)#',
				'',
				$this->url
			);

		} else {				// if is a file then encode a valid url for the file
			
			$this->url = explode('.', $this->url);
			$len = count($this->url);
			$aux = '';
			
			if ($len != 2) {
				
				if ($len <= 1) {
					return false;
				}

				// encode individual array
				for ($i = 0; $i < $len - 1; $i++) {
					$this->url[$i] = preg_replace(
						'#(\.|&|%|\$|\^|\'|\"|@|\#|\(|\)|\[|\]|\?|\¿|\!|\¡|/|\¬|\=|\·|:|;|\+|\,|\`|\||€|£|\\\)#',
						'',
						$this->url[$i]
					);
				}

				// join arrays to generate the encoded URL
				for ($i = 0; $i < $len - 1; $i++) {
					$aux .= $this->url[$i];
				}
				// adds the extension at the end of the string
				$aux .= ".". $this->url[$len - 1];
				$this->url = $aux;
			} else {			// in case of the file the file name was more valid then do this 
				$this->url[0] = preg_replace(
					'#(\.|&|%|\$|\^|\'|\"|@|\#|\(|\)|\[|\]|\?|\¿|\!|\¡|/|\¬|\=|\·|:|;|\+|\,|\`|\||€|£|\\\)#',
					'',
					$this->url[0]
				);

				$this->url = implode('.', $this->url);
			}
			
		}
		
		$this->url = preg_replace('#\s+#', '-', $this->url);
		$this->url = str_replace("\0", "", $this->url);	// delete all posible null values
		$this->url = $this->_shortener();
		
		if ($this->maxLen > 0 && $this->url[$this->maxLen - 1] == '-') {
			$this->url = substr($this->url,0 , $this->maxLen - 1);
		}

		return $this->url;		// return the url encoded for best search engine results
	}

}