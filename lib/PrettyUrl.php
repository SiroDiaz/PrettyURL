<?php namespace PrettyUrl;


class PrettyUrl {

	private $url;
	private $blackList;

	private $replaceChars = array(
		'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i',
		'ò' => 'o',	'ù' => 'u', 'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u', 'â' => 'a',
		'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u', 'ã' => 'a', 'õ' => 'o', 'ű' => 'u',	'ý' => 'y',
		'ç' => 'c', 'ñ' => 'n', 'α' => 'a',	'β' => 'b',	'γ' => 'g', 'δ' => 'd', 'ε' => 'e',	'ζ' => 'z',
		'η' => 'h',	'θ' => '8',	'ι' => 'i',	'κ' => 'k',	'λ' => 'l',	'μ' => 'm',	'ν' => 'n', 'ξ' => '3',
		'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y',	'φ' => 'f',	'χ' => 'x',	'ψ' => 'ps',
		'ω' => 'w',	'ά' => 'a',	'έ' => 'e',	'ί' => 'i', 'ή' => 'h',	'ώ' => 'w',	'ς' => 's',	'ϊ' => 'i',
		'ΰ' => 'y',	'ϋ' => 'y',	'ΐ' => 'i', 'ş' => 's', 'ı' => 'i',	'ğ' => 'g',	'б' => 'b',	'в' => 'v',
		'г' => 'g',	'д' => 'd',	'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
		'л' => 'l',	'м' => 'm', 'н' => 'n', 'п' => 'p', 'т' => 't', 'ф' => 'f', 'ц' => 'c', 'ч' => 'ch',
		'ш' => 'sh','щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '№' => '',
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z', 'ă' => 'a', 'ș' => 's', 'ț' => 't'
	);

	/**
	 * Initialize the url and define the black list
	 *
	 * @throws Exception if the url is invalid
	 */	

	public function __construct($url){
		if(is_string($url) && !empty(trim($url))){
			$this->url = $url;
			$this->blackList = array();
		}else{
			throw new \Exception("The url can't be empty");
		}
	}

	/**
	 * set the url to other
	 *
	 * @param string $url the url to encode
	 */

	public function setUrl($url){
		if(is_string($url) && !empty($url)){
			$this->url = $url;
			return true;
		}

		return false;
	}

	/**
	 * return the url encoded or not encoded
	 *
	 * @return mixed the url if is valid
	 * 			false if the url invalid
	 */

	public function getUrl(){
		if(isset($this->url) && !empty($this->url) && is_string($this->url)){
			return $this->url;
		}

		return false;
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

		if((is_string($list) || is_integer($list) || is_float($list)) && !empty($list)){
			$this->blackList[] = $list;
			return true;
		}elseif (is_array($list) && count($list)) {
			$keys = array_keys($list);
			$keyLength = count($list);
			$valid_val = array();
			
			for($i = $keyLength - 1; $i >= 0; $i--) {
				if(is_int($keys[$i]) && (!is_array($list[$keys[$i]]) && !is_object($list[$keys[$i]]))) {
					if(is_string($keys[$i])){
						$valid_val[] = strtolower($list[$keys[$i]]);
					}else{
						$valid_val[] = $list[$keys[$i]];
					}
				}
			}

			$valid_val = array_unique($valid_val);

			if(count($valid_val) == 0){
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
	 * removes all strings or numbers inside
	 * the url text
	 *
	 * @return false if the url to modify is empty
	 */

	private function _filter(){
		$len = count($this->blackList);

		if($len == 0){
			return false;
		}

		for($i = $len - 1; $i >= 0; $i--){
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
	 * replaces all null values
	 *
	 * @return false if the string is empty
	 */

	private function _replaceNull() {

		if(strlen($this->url) == 0){
			return false;
		}

		$this->url = str_replace("\0", "", $this->url);
	}

	/**
	 * encode the data to make it valid for websites
	 * or forums
	 */

	public function Urlify(){
		$this->url = trim(strtolower($this->url));
		$this->_filter();
		$this->_replaceChars();
		$this->url = str_replace('_', ' ', $this->url);
		$this->url = preg_replace('#(\.|&|%|\$|\^|\'|\"|@|\#|\(|\)|\[|\]|\?|\¿|\!|\¡|/|€|\\\)#', '', $this->url);
		$this->url = preg_replace('#\s+#', '-', $this->url);
		$this->_replaceNull();	// delete all posible null values
	}

}