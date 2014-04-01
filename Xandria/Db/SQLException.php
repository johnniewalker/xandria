<?php

require_once('Xandria/MyErrorTrigger.php');


/**
*@desc this is special cos we can pass SQL queries in the error message and only display a bland SQL error messsage to public
* thus we have to specifiallcly call another method to get the sql
*/
class Xandria_Db_SQLException extends Exception
	{
	private $_SQLerror_mssg = '';

	/**
	* @desc triggers e user warning and stashes $SQLmessage so it does NOT get rendered to page if getMessage() method is invoked.
	*/
	public function __construct( $SQLmessage, $errorno = 0 )
		{
		//get instance of the Application Domain Configuration - set in the site_settings

		//held in this class not for publci consumption
		$this->_SQLerror_mssg = $SQLmessage;

		//public_error
		$message = 'SQL Error';

		//trigger Graceful Db Degradation Warning
		require_once('Xandria/MyErrorTrigger.php');
		Xandria_MyErrorTrigger::triggerDbSQLExceptionWarning( $this );

		//call the exception constructor
		parent::__construct( $message, $errorno );
		}

	public function get_SQLerror_mssg()
		{
		return $this->_SQLerror_mssg;
		}

	}