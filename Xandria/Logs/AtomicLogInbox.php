<?php

//extends
require_once( 'Xandria/Logs/AbstractAtomicLogDirectory.php' );


/**
* @desc The inbox directory that contains atomic log files (which are named according to time).
* 
* This class expects DOCROOT_PARENT_DIR to have been defined
* I do not want to have to pass in variable to it.
*/
class Xandria_Logs_AtomicLogInbox extends Xandria_Logs_AbstractAtomicLogDirectory
	{


	/**
	* @desc 
	* @returns the path to the file that was created
	*/
	public function storeMessageAsAtomicLogFile( 
			$message
			) 
		{
		//Pick a name for file based on time.
		$filename = $this->createAtomicLogFileName();
		$pathToAtomicLogFile = $this->getPathToAtomicLogDirectory() . '/' . $filename;

		//Open file for appending ( in case two messages are written at exactly the same time ).
		$atomicLogFileHandle = fopen( $pathToAtomicLogFile, 'a' );

		if (!( $atomicLogFileHandle ))
			{
			//If open fails an E_WARNING would have already been generated. Just return.
			return;			
			}

		//Write message to file.
		fwrite( $atomicLogFileHandle, $message );

		//Close file.
		fclose( $atomicLogFileHandle );

		//Note that I do not 'clean-up dead wood older than x' here because this would add side effects to the method.
		return $filename;
		}


	public function getPathToAtomicLogDirectory()
		{
		if ( $this->_pathToAtomicLogDirectory )
			{
			return $this->_pathToAtomicLogDirectory;
			}
		else	{
			return DOCROOT_PARENT_DIR . '/appdata/app_logs/atomic_logs/inbox';
			}
		}


	/**
	* @desc Moves the matching atomic logs into the directory specified by the archive object.
	* 
	* @param Xandria_Logs_AtomicLogArchive
	* @param int -  Threshold number of weeks in the past marking delimiting the records to leave untouched.
	* 
	* @return int Number of log files archived.
	* 
	*/
	public function archiveAtomicLogFilesWhoseNamesImplyTheyAreOlderThanNWeeks( 
			Xandria_Logs_AtomicLogArchive $destinationAtomicLogArchive, 
			$nWeeks 
			)
		{
		return $this->archiveAtomicLogFilesOlderThanUnixMicroTimeFloat( 
			$destinationAtomicLogArchive, 
			$this->getMicrotimeFloatAtNWeeksAgo( $nWeeks )
			);
		}


	/**
	* @desc A way of flushing deadwood files out of the inbox whilst still preserving them for future analysis.
	* It assumes filenames are in the form of 'microtimes as float'.
	* 
	* @param Xandria_Logs_AtomicLogArchive
	* @param float - The cut-off Unix microtime after which files will be unaffected.
	* 	
	* @return Number of log files archived	
	*/
	protected function archiveAtomicLogFilesOlderThanUnixMicroTimeFloat( 
			Xandria_Logs_AtomicLogArchive $destinationAtomicLogArchive, 
			$microtimeFloat 
			)
		{

		$fileNamesArray = $this->findAllAtomicLogFileNames();

		$archivedCount = 0;

		foreach ( $fileNamesArray as $fileName ) 
			{
			if ( $this->isAtomicLogFileNameOlderThanMicroTimeFloat( $fileName, $microtimeFloat )  )
				{
				$archivedCount++;

				$isMoved = rename( 
					$this->getPathToAtomicLogDirectory() . "/" . $fileName, //old name
					$destinationAtomicLogArchive->getPathToAtomicLogDirectory() . "/" . $fileName //new name
					);

				if (!( $isMoved ))
					{
					//It failed. Throw an exception.
					throw new Exception( 
						'Failed to move file [' . $fileName . ']'
						. ' from inbox [' . $this->getPathToAtomicLogDirectory() . ']'
						. ' to archive [' . $destinationAtomicLogArchive->getPathToAtomicLogDirectory() . '].' );
					}
				}
			}

		return $archivedCount;
		}
	}
