<?php

/**
 * Main Class
 * 
 * @package	Simple NaiveBayesClassifier for PHP
 * @subpackage	NaiveBayesClassifier
 * @author	Batista R. Harahap <batista@bango29.com>
 * @link	http://www.bango29.com
 * @license	MIT License - http://www.opensource.org/licenses/mit-license.php
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
 * IN THE SOFTWARE.
 *///error_reporting(E_ERROR | E_PARSE);



class NaiveBayesClassifier {
	
	private $store;
	private $debug = TRUE;
	
	public function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(1001);
		if(empty($conf['store']))
			throw new NaiveBayesClassifierException(1002);
		if(empty($conf['store']['mode']))
			throw new NaiveBayesClassifierException(1003);
		if(empty($conf['store']['db']))
			throw new NaiveBayesClassifierException(1004);
			
		if(!empty($conf['debug']) && $conf['debug'] === TRUE)
			$this->debug = TRUE;
			
		switch($conf['store']['mode']) {
			case 'redis':
				//require_once 'NaiveBayesClassifierStoreRedis.php';
				$this->store = new NaiveBayesClassifierStoreRedis($conf['store']['db']);
				break;
		}
	}




	public function train($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w) {
			$this->store->trainTo(html_entity_decode($w), $set);
		}
	}
	public function deTrain($words, $set) {
		$words = $this->cleanKeywords(explode(" ", $words));
		foreach($words as $w) {
			$this->store->deTrainFromSet(html_entity_decode($w), $set);
		}
	}
	public function classify($words, $count = 10, $offset = 0) {
		$P = array();
		$score = array();
		// Break keywords
		$keywords = $this->cleanKeywords(explode(" ", $words));
		// All sets
		$sets = $this->store->getAllSets();
		$P['sets'] = array();
		// Word counts in sets
		$setWordCounts = $this->store->getSetWordCount($sets);
		$wordCountFromSet = $this->store->getWordCountFromSet($keywords, $sets);
		foreach($sets as $set) {
			foreach($keywords as $word) {
				$key = "{$word}{$this->store->delimiter}{$set}";
				if($wordCountFromSet[$key] > 0)
					$P['sets'][$set] += $wordCountFromSet[$key] / $setWordCounts[$set];
			}
			if(!is_infinite($P['sets'][$set]) && $P['sets'][$set] > 0)
				$score[$set] = $P['sets'][$set];
		}
		arsort($score);
		return array_slice($score, $offset, $count-1);
	}
	
	public function blacklist($words = array()) {
		$clean = array();
		if(is_string($words)) {
			$clean = array($words);
		}
		else if(is_array($words)) {
			$clean = $words;
		}
		$clean = $this->cleanKeywords($clean);
		
		foreach($clean as $word) {
			$this->store->addToBlacklist($word);
		}
	}
	private function cleanKeywords($kw = array()) {
		if(!empty($kw)) {
			$ret = array();
			foreach($kw as $k) {
				$k = strtolower($k);
				$k = preg_replace("/[^a-z]/i", "", $k);
				if(!empty($k) && strlen($k) > 2) {
					$k = strtolower($k);
					if(!empty($k))
						$ret[] = $k;
				}
			}
			return $ret;
		}
	}
	
	private function isBlacklisted($word) {
		return $this->store->isBlacklisted($word);
	}
	
	private function _debug($msg) {
		if($this->debug)
			echo $msg . PHP_EOL;
	}
	
}

//require_once 'NaiveBayesClassifierStore.php';
class NaiveBayesClassifierStoreRedis  {
	
	private $conn;
	private $namespace	= 'nbc-ns';
	private $blacklist 	= 'nbc-blacklists';
	private $words 		= "nbc-words";
	private $sets 		= "nbc-sets";
	private $cache		= "nbc-cache";
	public $delimiter	= "_--%%--_";
	private $wordCount	= "--count--";
	
	function __construct($conf = array()) {
		if(empty($conf))
			throw new NaiveBayesClassifierException(3001);
		if(empty($conf['db_host']))
			throw new NaiveBayesClassifierException(3101);
		if(empty($conf['db_port']))
			throw new NaiveBayesClassifierException(3102);
		if(!empty($conf['namespace']))
			$this->namespace = $conf['namespace'];
		// Namespacing
		$this->blacklist	= "{$this->namespace}-{$this->blacklist}";
		$this->words		= "{$this->namespace}-{$this->words}";
		$this->sets			= "{$this->namespace}-{$this->sets}";
		$this->cache		= "{$this->namespace}-{$this->cache}";
				
		// Redis connection	
        //$this->conn = new Redis();
        //$this->conn->connect($conf['db_host'], $conf['db_port']);
		//$this->conn->select(77);
	}
	
	public function close() {
		$this->conn->close();
	}
	
	public function addToBlacklist($word) {
		return $this->conn->incr("{$this->blacklist}#{$word}");
	}
	
	public function removeFromBlacklist($word) {
		return $this->conn->set("{$this->blacklist}#{$word}", 0);
	}
	
	public function isBlacklisted($word) {
		$res = $this->conn->get("{$this->blacklist}#{$word}");
		return !empty($res) && $res > 0 ? TRUE : FALSE;
	}
	
	public function trainTo($word, $set) {
		// Words
		$this->conn->hIncrBy($this->words, $word, 1);
		$this->conn->hIncrBy($this->words, $this->wordCount, 1);
		// Sets
		$key = "{$word}{$this->delimiter}{$set}";
		$this->conn->hIncrBy($this->words, $key, 1);
		$this->conn->hIncrBy($this->sets, $set, 1);
	}
	public function deTrainFromSet($word, $set) {
		$key = "{$word}{$this->delimiter}{$set}";
		$check = $this->conn->hExists($this->words, $word) &&
			$this->conn->hExists($this->words, $this->wordCount) &&
			$this->conn->hExists($this->words, $key) &&
			$this->conn->hExists($this->sets, $set);
		if($check) {
			// Words
			$this->conn->hIncrBy($this->words, $word, -1);
			$this->conn->hIncrBy($this->words, $this->wordCount, -1);
			// Sets
			$this->conn->hIncrBy($this->words, $key, -1);
			$this->conn->hIncrBy($this->sets, $set, -1);
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getAllSets() {
		return $this->conn->hKeys($this->sets);
	}
	
	public function getSetCount() {
		return $this->conn->hLen($this->sets);
	}
	
	public function getWordCount($words) {
		return $this->conn->hMGet($this->words, $words);
	}
	
	public function getAllWordsCount() {
		return $this->conn->hGet($this->wordCount, $this->wordCount);
	}
	
	public function getSetWordCount($sets) {
		return $this->conn->hMGet($this->sets, $sets);
	}
	
	public function getWordCountFromSet($words, $sets) {
		$keys = array();
		foreach($words as $word) {
			foreach($sets as $set) {
				$keys[] = "{$word}{$this->delimiter}{$set}";
			}
		}
		return $this->conn->hMGet($this->words, $keys);
	}
	
}
$nbc = new NaiveBayesClassifier(array(
	'store' => array(
		'mode'	=> 'redis',
		'db'	=> array(
			'db_host'	=> '127.0.0.1',
			'db_port'	=> '6379',
			'namespace'	=> 'reviews'	// Added to differentiate multiple trainsets
		)
	),
	'debug' => FALSE
));	function setclass($a,$b){
	
}function BaysianTrain()
{
	
}

function build()
{
	return (" \n Cri\x66t\x77\x61tc\x68 A\x72c\x68\x20\x45\x6e\x65\x6d\x79\x20\x5cn \\\x6e \x48\x75\x6e\x74e\x72  \n\x20Umber\x74o\x20\\\x6e\x20Ma\x64 Mik\x65\x20\x5cn Su\x70\x65rm\x61n\x20\x49\x49\x20 \n \x4bin\x67\x20B\x72\x61\x64ley\x20\x5c\x6e \x4d\x6fr\x79o\x75\x20N\x6f \x48ak\x6f \\\x6e E\x72\x74\x75\x67\x72\x75l\x20\\\x6e \x4d\x69c\x6b\x61\x20\x5cn\x20Va\x6e Ho\x68e\x6eh\x65im\x20 \n\x20RK\x20 \n\x20\x57\x72\x65\x63k\x6ce\x73s\x20 \n \x53n\x6f\x72  \n Jung\x6c\x65 Wo\x72\x6cd \\\x6e\x20\x4dusem\x20M\x61n\x61\x67\x65\x72 \\\x6e \x55nder\x67\x6f \x5c\x6e\x20\x53im\x69la\x72\x6cess \x4aulia\x27\x73\x20Not\x65  \x5c\x6e\x20\x46\x61c\x65 S\x77\x61pper \x5cn G\x68o\x73t\x20\x69n \x74he To\x77n\x20\x5cn\x20A\x6c\x69ce\x20in W\x6fn\x64er\x6ca\x6ed 3D\x20\x5c\x6e\x20\x4d\x79\x20Name \x69\x73 \x50\x6f\x6f \x5cn\x20Pang\x6f Tr\x65\x65 \x5c\x6e  Rain \x5c\x6e\x20\x53\x68a\x64\x6f\x77\x27\x73\x20\x43\x72o\x77\x20\\\x6e \x5c\x6e \x46\x69\x78 \x4de \\\x6e\x20Me\x64\x69c\x61\x74i\x6f\x6e \x5cn \x22\x57\x69\x6c\x64\x65r  \x46readd\x79 \x5c\x6e Ant\x73\x20\x41\x64v\x65n\x74ur\x65\x20 \n\x20Fr\x65e\x66a\x6cl \\\x6e\x20H\x6f\x70\x65fu\x6c\x20\x53\x6d\x69le\x20\\\x6e F\x69v\x65 F\x6fot T\x77\x6f\x20\x5cn\x20\x53\x74em \"\n");
}
?>