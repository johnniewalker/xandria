<?php
namespace Xandria\Logs;


/**
* @desc Script to Browse contents of atomic logs and delete old ones older than x weeks
* 
* @todo Re-think the way this is tested. Cos it is quite bad to mix test code with feature code.
*/


class AtomicLogBrowserController
	{

	protected $inboxDirectoryObject;
	protected $archiveDirectoryObject;

	/**
	* @desc 
	* 
	* @return Zend_View
	*/
	public function executeIteration()
		{	
		//Use default log directory.	
		$currentAtomicLogDirectory = $this->getCurrentAtomicLogDirectoryObject();


		$feedbackMssgs = array();


		if ( ( isset( $_POST['action-button'] ) ) && ( 'Archive Logs' == $_POST['action-button'] ) && (!( $this->isCurrentlyArchive() )) )
			{		
			//We are not in the archive and we have been told to archive some files in the current directory.

			$feedbackMssgs[] = 'Archiving logs older than '. $_POST['numWeeksOld'] .' weeks old';
			$feedbackMssgs[] = ( (string) $currentAtomicLogDirectory
						->archiveAtomicLogFilesWhoseNamesImplyTheyAreOlderThanNWeeks( 
							$this->getArchiveDirectoryObject(),
							$_POST['numWeeksOld'] ) 
							)
					. ' files were archived.';	
			}
		elseif ( ( isset( $_POST['action-button'] ) ) && ( 'Delete Logs' == $_POST['action-button'] ) )
			{		
			$feedbackMssgs[] = 'Deleting logs older than '. $_POST['numWeeksOld'] .' weeks old';
			$feedbackMssgs[] = ( (string) $currentAtomicLogDirectory
						->deleteAtomicLogFilesWhoseNamesImplyTheyAreOlderThanNWeeks( 
							$_POST['numWeeksOld'] ) 
							)
					. ' files were deleted.';	
			}

		return $feedbackMssgs;
		}

	/**
	* @desc Get the appropriate AtomicLogDirectory name depending on if we are in the inbox or the archive.
	*/
	public function getCurrentAtomicLogDirectoryName()
		{	

		if ( $this->isCurrentlyArchive() )
			{
			return 'archive';
			}

		//else assume we are in the inbox
		return 'inbox';
		}


	/**
	* @desc Get the appropriate AtomicLogDirectoryObject depending on if we are in the inbox or the archive.
	*/
	public function getCurrentAtomicLogDirectoryObject()
		{	

		if ( $this->isCurrentlyArchive() )
			{
			return $this->getArchiveDirectoryObject();
			}

		//else assume we are in the inbox
		return $this->getInboxDirectoryObject();
		}

	public function isCurrentlyArchive()
		{
		if ( ( isset( $_REQUEST['isArchive'] ) ) && ( $_REQUEST['isArchive'] ) )
			{
			return TRUE;
			}			
		}

	/**
	* @desc Lazy load the InboxDirectoryObject 
	* 
	* @return \Xandria\Logs\AtomicLogInbox;
	*/
	protected function getInboxDirectoryObject()
		{
		if (!( $this->inboxDirectoryObject ))
			{
			$this->inboxDirectoryObject = new \Xandria\Logs\AtomicLogInbox();
			}

		return $this->inboxDirectoryObject;				
		}

	/**
	* @desc Lazy load the InboxDirectoryObject
	* 
	* @return \Xandria\Logs\AtomicLogArchive;
	*/
	protected function getArchiveDirectoryObject()
		{

		if (!( $this->archiveDirectoryObject ))
			{
			$this->archiveDirectoryObject = new \Xandria\Logs\AtomicLogArchive();
			}

		return $this->archiveDirectoryObject;
		}
	}
