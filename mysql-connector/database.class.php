<?php
	/**
	 *  ######################################################
	 *	MySQL Database Class
	 *	Class upgraded for use with 5.3+ PHP Servers.
	 *
	 *	@version 1.0
	 *	@author RZEROSTERN
	 *	@license Beerware Rev 43 for @yagarasu, @t1niebl4zz, @GatussoIII, @juliettemaxwell, @nubieshita and @TijoMONSTER.
	 *	@license Creative Commons CC-BY-SA 4.0 for the rest of the world.
	 *
	 * 	----------------------------------------------------------------------------
	 * 						"THE BEER-WARE LICENSE" (Revision 43):
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
	 *
	 *	########################################################
	 */
	class MySQLDatabase
	{
		private $host;
		private $user;
		private $pass;
		private $database;
		private $linkdb;
		private $is_connected;

		/**
		 *	Constructor
		 *
		 *	@param $p_host Host for the database
		 * 	@param $p_user User for the database
		 * 	@param $p_pass Password for the database
		 * 	@param $p_database Database schema to use
		 */
		public function __construct($p_host, $p_user, $p_pass, $p_database){
			$this->host = $p_host;
			$this->user = $p_user;
			$this->pass = $p_pass;
			$this->db = $p_database;
		}

		/**
		 *	connect
		 *	Connects to the database
		 * 	@return TRUE for success, FALSE for failure.
		 */
		public function connect(){
			$this->linkdb = @mysqli_connect($this->host, $this->user, $this->pass, $this->database);

			if($this->linkdb == false){
				echo "Error al conectar a MySQL: ".mysqli_connect_errno(). " - ".mysqli_connect_error();
				return false;
			} else {
				$this->is_connected = true;
				return true;
			}
		}

		/**
		 *	close
		 *	Closes the connection
		 * 	@return TRUE for success, FALSE for failure.
		 */
		public function close(){
			if(!$this->is_connected){
				return false;
			} else {
				mysqli_close($this->linkdb);
				$this->is_connected = false;
				return true;
			}
		}

		/**
		 *	count_rows
		 *	Count the rows of a determined table, with or without distinct elements.
		 *	@param $table Table to count the rows.
		 *	@param $conditional Conditional for filtering the count.
		 *	@param $unique Determines if the count will be with distinct elements.
		 *	@return Result of the count, FALSE for failure.
		 */
		public function count_rows($table, $conditional = "", $unique = ""){
			if(!$this->is_connected){
				return false;
			} else {
				$unique = mysqli_real_escape_string($unique);
				$table = mysqli_real_escape_string($table);
				$conditional = mysqli_real_escape_string($conditional);
				$cnt = ($unique == "") ? 'count(*)' : 'count(distinct ".$unique.")';
				$whe = ($conditional == "") ? '1=1' : $conditional;

				$query = "SELECT ".$cnt." FROM ".$table." WHERE ".$whe.";";
				$result = mysqli_query($this->linkdb, $query);
				$r = mysqli_fetch_array($result);
				return intval($r[0]);
			}
		}

		/**
		 *	query_insert
		 *	Inserts a row into a determined table.
		 *	@param $table Table where the row will be inserted.
		 *	@param $data Array with the data for insert (use the format $array['<name of the column>']).
		 *	@return Insert ID for success, FALSE for failure.
		 */
		public function query_insert($table, $data){
			if(!$this->is_connected){
				return false;
			} else {
				$table = mysqli_real_escape_string($table);
				$keys = array_keys($data);
				$keys = array_map("mysqli_real_escape_string", $keys);
				$qkeys = implode(",", $keys);

				foreach($data as $k=>$v){
					if($v != "NULL" && stripos($v, "()") === false){
						$data[$k] = "'".$v."'";
					}
				}

				$qval = implode(",", $data);
				$query = "INSERT INTO {$table} ({$qkeys}) VALUES ({$qval});";
				$result = mysqli_query($query);

				if($result === false){
					return false;
				} else {
					$lastid = mysqli_insert_id($this->linkdb);
					return $lastid;
				}
			}
		}

		/**
		 *	query_update
		 *	Updates a row into a determined table.
		 *	@param $table Table where the row will be updated.
		 *	@param $data Array with the data for update (use the format $array['<name of the column>']).
		 *	@param $conditional Conditional for making the row update.
		 *	@return TRUE for success, FALSE for failure.
		 */
		public function query_update($table, $data, $conditional){
			if(!$this->is_connected){
				return false;
			} else {
				$table = mysqli_real_escape_string($table);
				$conditional = mysqli_real_escape_string($conditional);

				$val = array();
				foreach($data as $k=>$v) {
					array_push($val, $k."='".$v."'");
				}
				$qVal = implode(", ", $val);
				$query = "UPDATE {$table} SET {$qVal} WHERE {$conditional}";
				$res = mysqli_query( $query );
				if( $res === false ) {
					return false;
				} else {
					return true;
				}
			}
		}

		/**
		 *	query_delete
		 *	Inserts a row into a determined table.
		 *	@param $table Table where the row will be deleted.
		 *	@param $conditional Conditional for making the row delete.
		 *	@return TRUE for success, FALSE for failure.
		 */
		public function query_delete($table, $conditional){
			if(!$this->is_connected){
				return false;
			} else {
				$table = mysqli_real_escape_string($table);
				$conditional = mysqli_real_escape_string($conditional);

				$query = "DELETE FROM {$table} WHERE {$conditional}";
				$res = mysqli_query($query);
				if( $res === false ) {
					return false;
				} else {
					return true;
				}
			}
		}

		/**
		 *	query_first
		 *	Gets the first row from a query
		 *	@param $query Query string
		 *	@return Query result in associative array for success, FALSE for failure.
		 */
		public function query_first($query){
			if(!$this->is_connected){
				return false;
			} else {
				$result = mysqli_query($this->linkdb, $query);
				if($result === false){
					return false;
				} else {
					return mysqli_fetch_array($result, MYSQLI_ASSOC);
				}
			}
		}

		/**
		 *	fetch_array
		 *	Gets all rows from a query
		 *	@param $query Query string
		 *	@return Query result in associative array for success, FALSE for failure.
		 */
		public function fetch_array($query){
			if(!$this->is_connected){
				return false;
			} else {
				if(!$this->isConn) { return false; } else {
					$result = mysqli_query($this->linkdb, $query);
					if($result === false) {
						return false;
					} else {
						$arrElements = array();
						while($el = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
							array_push($arrElements, $el);
						}
						mysql_free_result($res);
						return $arrElements;
					}
				}
			}
		}

		/**
		 *	escape
		 *	Sanitizes a string
		 *	@param $string String to sanitize
		 *	@return Sanitized string for success, FALSE for failure.
		 */
		public function escape($string){
			if(!$this->linkdb){
				return false;
			} else {
				return mysqli_real_escape_string($string);
			}
		}

		/**
		 *	get_last_error
		 *	Obtains the last error from a query.
		 *	@return String with error details.
		 */
		public function get_last_error(){
			return "Error al realizar consulta en MySQL: ".mysqli_connect_errno(). " - ".mysqli_connect_error();
		}

		/**
		 *	is_connected
		 *	Determines if the instance is connected
		 *	@return Result of connected instance (TRUE or FALSE).
		 */
		public function is_connected(){	return $this->is_connected;	}
	}
?>