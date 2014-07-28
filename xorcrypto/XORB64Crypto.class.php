<?php
    class XORB64Crypto
    {
    	private $privatekey;
		private $string;
		private $base64_alphabet;
		
		public function __construct(){
			$this->base64_alphabet = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", 1);
		}
		
		public function encode($string, $key){
			$this->privatekey = $key;
			$this->string = $string;
			
			$stringarray = str_split($this->string, 1);
			$privatekeyarray = str_split($this->privatekey, 1);
			$privatekeylength = count($privatekeyarray);
			$privatekeycharcount = 0;
			$m = 0;
			$encoded = "";
			
			foreach($stringarray as $char){
				//PASO 1
				$ascii = ord($char);
				$privatekeycharcount = ($privatekeycharcount != 0 && $privatekeycharcount % $privatekeylength == 0) ? 0 : $privatekeycharcount;
				$asciikey = ord($privatekeyarray[$privatekeycharcount]);
				
				//PASO 2
				$xor = $ascii ^ $asciikey;
				
				//PASO 3
				$xorsalt = $xor + $privatekeylength;
				
				//PASO 4
				$binary = decbin($xorsalt);
				$binary = substr("00000000", 0, 8 - strlen($binary)) . $binary;
				
				//PASOS 5, 6 y 7
				for($k = 0; $k < strlen($binary); $k += 4){
					$nibble = bindec(substr($binary, $k, 4));
					$nibble = ($nibble * 4) + $m;
					$encoded .= $this->base64FromInt($nibble);
					++$m;
					
					$m = ($m > 3) ? 0 : $m;
				}
				
				$privatekeycharcount++;
			}
			
			return $encoded;
			
		}

		public function decode($hash, $key){			
			$decoded = "";
			$binarydata = "";
			$keyarray = str_split($hash, 1);
			$m = 0;
			
			$hash = str_split($hash, 1);
			$key = str_split($key, 1);
			
			foreach($hash as $c){
				$v = ($this->intFromBase64($c) - $m) / 4;
				$b = decbin($v);
				$binarydata .= substr("0000", 0, 4 - strlen($b)).$b;
				
				++$m;
				$m = ($m > 3) ? 0 : $m;
			}
			
			$keypos = 0;
			
			for($i = 0; $i < strlen($binarydata); $i += 8){
				if($i + 8 > strlen($binarydata)){ break; }
				$c = bindec(substr($binarydata, $i, 8));
				//var_dump($key[$keypos]);
				$xorc = ($c - count($key)) ^ ord($key[$keypos]);
				//var_dump($xorc);
				++$keypos;
				
				$keypos = ($keypos >= count($key)) ? 0 : $keypos;
				
				$decoded .= chr($xorc);
			}
			
			return $decoded;
		}
		
		private function base64FromInt($n){
			if($n > count($this->base64_alphabet))
				return "=";
			
			return $this->base64_alphabet[$n];
		}
		
		private function intFromBase64($n){
			return array_search($n, $this->base64_alphabet);
		}
		
    }
?>