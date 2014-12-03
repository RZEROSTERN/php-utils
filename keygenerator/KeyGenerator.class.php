<?php
	/**
	 *	KeyGenerator
	 *	Generates a key with a given mask.
	 *
	 *	@version 1.0
	 *	@author RZEROSTERN based on Barand's code (http://forums.phpfreaks.com/topic/120028-solved-how-to-generate-a-product-serial-number/)
	 *	@license Beerware Rev 43 for @yagarasu, @t1niebl4zz, @GatussoIII/@eletgüin, @nubieshita and @TijoMONSTER.
	 *	@license Creative Commons CC-BY-SA 4.0 for the rest of the world.
	 *
	 * 	----------------------------------------------------------------------------
	 * 						"THE BEER-WARE LICENSE" (Revision 42):
	 *
	 * 	RZEROSTERN wrote this file. As long as you retain this notice you
	 * 	can do whatever you want with this stuff. If we meet some day, and you think
	 * 	this stuff is worth it, you can buy me a beer in return.
	 * 	----------------------------------------------------------------------------
	 *						CREATIVE COMMONS CC-BY-SA 4.O License
	 *	
	 *	For human understanding: 	http://creativecommons.org/licenses/by-sa/4.0/
	 *	For lawyer gangsters:		http://creativecommons.org/licenses/by-sa/4.0/legalcode
	 *	----------------------------------------------------------------------------
	 */

	class KeyGenerator
	{
		private $mask;

		/**
		 *	Constructor
		 *	The mask must contain only this characters: 
		 *		+ X for a capital letter.
		 *		+ 1 for a number.
		 *		+ - for a dash.
		 *	@param p_template The required mask for keys.
		 */
		public function __construct($p_mask){
			$this->mask = $p_mask;
		}

		/**
		 *	make_new_key
		 *	Retrieves a new key with the given mask.
		 *	@return Random key
		 */
		public function make_new_key(){
			$masklength = strlen($this->mask);
			$key = "";

			for($i = 0; $i < $masklength; $i++){
				switch($this->mask[$i]){
					case 'X': $key .= chr(rand(65,90)); break;
		            case '1': $key .= rand(0,9); break;
		            case '-': $key .= '-';  break;
				}
			}

			return $key;
		}

		/**
		 *	edit_mask
		 *	Edits the mask for the keys
		 *	@param p_mask The new key mask.
		 */
		public function edit_mask($p_mask){
			$this->mask = $p_mask;
		}
	}
?>