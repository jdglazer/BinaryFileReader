<?php
	
	class BinaryFile {
		
		//pool variables
		private $bin_str;
		
		private $_fileptr;
		
		private $endianness;
		
		private $machine_endianness;
		
		private $type;
		
		private $valid_types = Array( "d" => 8, 
										"f" => 4,
										 "l" => 8,
										 "i" => 4,
										 "s" => 2 );
		
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
		
		private function _const() {
			
			$this->endianness = "B";
			
			$this->type = "d";
			
		}
		
		private function setMachineEndianness() {
			
			$sampleByteStream =	"abcd";
			
			$ch = unpack( "i", $sampleByteStream );
			
			if( $ch[1] == 1633837924 )
			
				$this->machine_endianness = "B";
				
			else if( $ch[1] == 1684234849 )
			
				$this->machine_endianness = "L";
		}
		

//WORK ON THE READ FUNCTIONALITY
		public function read( $type, $litEnd, $offset ) {
			
			if( $this->setType( $type ) ) {
			
				$litEnd ? $this->setLittleEnd() : $this->setBigEnd();
				
				fseek( $this->_fileptr, $offset );
				
				$this->bin_str = fread( $this->_fileptr, $this->valid_types[ $this->type ] ) ;
				
				if( $this->endianness != $this->machine_endianness )
				
					$this->bin_str = strrev( $this->bin_str );
						
				return unpack( $type,  $this->bin_str )[1];
			}
			else
				return false;
			
						
		}
		
		public function setLittleEnd( ) {
			
			$this->endianness = "L";
		}
		
		public function setBigEnd( ) {
			
			$this->endianness = "B";
		}
		
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
