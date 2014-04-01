<?php

/**
* @desc Abstract directory that contains atomic log files (which are named according to time).
*/
abstract class Xandria_Logs_AbstractAtomicLogDirectory
	{


	/**
	* @desc 
	* @param path to directory - used for testing
	*/
	public function __construct( $pathToAtomicLogDirectory = FALSE )
		{
		$this->_pathToAtomicLogDirectory = $pathToAtomicLogDirectory;
		}

	/**
	* @desc Creates Atomic Log Filename based on microtime
	* 
	* This is in the abstract so that tests of the AtomicLogArchive can create files with valid names.
	*/
	public function createAtomicLogFileName()
		{
		return microtime( TRUE );
		}

	
	public function findAllAtomicLogs()
		{
		$fileNamesArray = $this->findAllAtomicLogFileNames();
	
		$atomicLogs = array();
		
		foreach ( $fileNamesArray as $fileName ) 
			{
			$atomicLogs[] = $this->readAtomicLogFileIntoString( $fileName );
			}
	
		return $atomicLogs;
		}

	public function getLogCount()
		{
		$logsArray = $this->findAllAtomicLogs();
		return count( $logsArray );
		}


	public function getAtomicLogsAsRecordJarFormattedString()
		{
		$logsArray = $this->findAllAtomicLogs();

		$str = '';
		$recordDelimeter = '';
		foreach ( $logsArray as $logMssg ) 
			{
			$str .= $recordDelimeter . $this->escapeJarRecord( $logMssg );
			$recordDelimeter = '%%' . PHP_EOL;			
			}

		return $str;
		}

	/**
	* @desc Bytestuffs any lines beggining with '%' with an extra '%'.
	*/
	protected function escapeJarRecord( $str )
		{
		$explodedArr = explode( PHP_EOL , $str );

		$lines = array();

		foreach( $explodedArr as $line )
			{
			if ( strpos( $line, '%' ) === 0 )
				{
				//It starts with a %
				//byte-stuff it with another one.
				$byteStuffing = '%';
				}
			else	{
				$byteStuffing = null;
				}

			$lines[] = $byteStuffing . $line;			
			}

		return implode( PHP_EOL , $lines );
		}
	


	protected function readAtomicLogFileIntoString( $fileName )
		{
		$str = 'file-name: ' . $fileName . PHP_EOL;
		$str .= PHP_EOL;
		$str .= file_get_contents( $this->getPathToAtomicLogDirectory() . "/" . $fileName );
		$str .= PHP_EOL;
		return $str;
		}


	protected function findAllAtomicLogFileNames()
		{
		
		$objects = scandir( 
			$this->getPathToAtomicLogDirectory(), 
			1 //@todo in php 5.4 we ought to use constant SCANDIR_SORT_DESCENDING
			);

		$fileNamesArray = array();

		foreach ( $objects as $object ) 
			{
			if ( filetype( $this->getPathToAtomicLogDirectory() . "/" . $object ) == "file" )
				{
				$fileNamesArray[] = $object;
				}				
			}

		reset( $objects );
		return $fileNamesArray;
		}

	/**
	* @desc Returns the microtime float of now minus n weeks ago.
	*/
	public function getMicrotimeFloatAtNWeeksAgo( $nWeeks )
		{
		$aWeekInSeconds = ( 7 * 24 * 60 * 60 );

		$cutOffMicrotimeFloat = ( microtime( TRUE ) - ( $nWeeks * $aWeekInSeconds ));

		return $cutOffMicrotimeFloat;
		}


	protected function isAtomicLogFileNameOlderThanMicroTimeFloat( 
			$filename, 
			$microtimeFloat 
			)
		{
		//First check it is a filenamed as a float

		$filenameAsMicrotimeFloat = ( ( float ) $filename );

		if (!( ( ( string ) $filenameAsMicrotimeFloat ) == $filename ))
			{
			//when the name is cast as a float changes.
			//so lets assume this was named as a float
			return FALSE;
			}


		return ( $filenameAsMicrotimeFloat < $microtimeFloat );
		}

	}