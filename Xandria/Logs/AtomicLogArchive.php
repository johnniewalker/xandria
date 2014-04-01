<?php

//extends
require_once( 'Xandria/Logs/AbstractAtomicLogDirectory.php' );

/**
*@desc The archive directory that contains atomic log files (which are named according to time).
*/
class Xandria_Logs_AtomicLogArchive extends Xandria_Logs_AbstractAtomicLogDirectory
	{


	public function getPathToAtomicLogDirectory()
		{
		if ( $this->_pathToAtomicLogDirectory )
			{
			return $this->_pathToAtomicLogDirectory;
			}
		else	{
			return DOCROOT_PARENT_DIR . '/appdata/app_logs/atomic_logs/archive';
			}
		}


	/**
	* @desc 
	* @return Number of log files deleted.
	*/
	public function deleteAtomicLogFilesWhoseNamesImplyTheyAreOlderThanNWeeks( $nWeeks )
		{
		return $this->deleteAtomicLogFilesOlderThanUnixMicroTimeFloat( 
			$this->getMicrotimeFloatAtNWeeksAgo( $nWeeks ) 
			);
		}



	/**
	* @desc A way of cleaning up deadwood files.
	* It assumes filenames are in the form of 'microtimes as float'.
	* 
	* The only reason it is 'public' is for testing purposes.
	* @return Number of log files deleted	
	*/
	public function deleteAtomicLogFilesOlderThanUnixMicroTimeFloat( $microtimeFloat )
		{

		$fileNamesArray = $this->findAllAtomicLogFileNames();

		$deletedCount = 0;

		foreach ( $fileNamesArray as $fileName ) 
			{
			if ( $this->isAtomicLogFileNameOlderThanMicroTimeFloat( $fileName, $microtimeFloat )  )
				{
				$deletedCount++;
				unlink( $this->getPathToAtomicLogDirectory() . "/" . $fileName );
				}
			}

		return $deletedCount;
		}

	}