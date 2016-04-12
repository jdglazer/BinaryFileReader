<?php
/**
 * A php class for reading binary files. The class has been tested 
 * 
 * @author Glazer, Joshua D.
 */
	class BinaryFile {
		
		private $bin_str;
		
		private $_fileptr;
		
		private $endianness;
		
		private $machine_endianness;
		
		private $type;
		
		public static $SHORT = 's';
		
		public static $INT = 'i';
		
		public static $LONG = 'l';
		
		public static $FLOAT = 'f';
		
		public static $DOUBLE = 'd';
		
		private $valid_types = Array( "d" => 8, 
										"f" => 4,
										 "l" => 8,
										 "i" => 4,
										 "s" => 2 );
/**
 * Constructor
 * @param $file Takes either an address to a file or a file resource returned from fopen()
 * @param $openas Tells fopen() the mode in which to open file. See php documentation for mode parameter in <a href="http://php.net/manual/en/function.fopen.php">fopen()</a> for valid values
 * @throws Exception If invalid input type is passed to the first argument (not string or object)
 */ 		
		function __construct( $file, $openas ) {
			
			if( is_string( $file ) ) {
				
				$this->_fileptr = fopen( $file, $openas );
			}
			
			else if( is_object( $file ) )
				
				$this->_fileptr	;
			
			else
				throw new Exception("Invalid input type to constructor");
				
			$this->_const();
			
			$this->setMachineEndianness();	
			
		}
		
/**
 * Sets default values for endianness, namely big endian and double type
 */
		private function _const() {
			
			$this->endianness = "B";
			
			$this->type = "d";
			
		}
		
/**
 * Determined machine endianness
 */ 
		private function setMachineEndianness() {
			
			$sampleByteStream =	"abcd";
			
			$ch = unpack( "i", $sampleByteStream );
			
			if( $ch[1] == 1633837924 )
			
				$this->machine_endianness = "B";
				
			else if( $ch[1] == 1684234849 )
			
				$this->machine_endianness = "L";
		}
		

/**
 * Gets a specified type from a specified position in the file
 * 
 * @param $type A string indicating the type to return. Use the static properites of the class for ease ( BinaryFile->SHORT, BinaryFile->INT, BinaryFile->LONG, BinaryFile->FLOAT, BinaryFile->DOUBLE )
 * @param $litEnd boolean set to true if value is to be read as little endian, false for big endian
 * @param $offset The offset in bytes in the file to begin reading value from
 * @return value being read or false if invalid $type argument is passed in
 */
		public function read( $type, $litEnd, $offset ) {
			
			if( $this->setType( $type ) ) {
			
				$litEnd ? $this->setLittleEnd() : $this->setBigEnd();
				
				fseek( $this->_fileptr, $offset );
				
				$this->bin_str = fread( $this->_fileptr, $this->valid_types[ $this->type ] ) ;
				
				if( $this->endianness != $this->machine_endianness )
				
					$this->bin_str = strrev( $this->bin_str );
						
				$r = unpack( $type,  $this->bin_str );
				
				if( is_array($r) )
				
					return $r[1];
				else 
				
					return false;
			}
			else
				return false;
			
						
		}
/**
 * Sets the values to be read as little endian
 */
		public function setLittleEnd( ) {
			
			$this->endianness = "L";
		}

/**
 * Set the values to be rad as big endian
 */		
		public function setBigEnd( ) {
			
			$this->endianness = "B";
		}

/**
 * Sets the type to be read from the file
 * @param $type String indicating the type to be read from the file ( BinaryFile->SHORT, BinaryFile->INT, BinaryFile->LONG, BinaryFile->FLOAT, BinaryFile->DOUBLE )
 * @return returns true if the $type value passed in is valid identifier
 */		
		public function setType( $type ) {
			
			if( array_key_exists( $type, $this->valid_types ) ) {
			
				$this->type = $type;
				
				return true;
			}
			
			else
				return false;
		}
	}
	
?>
