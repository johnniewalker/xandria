<?php
/**
*@desc generic class to represent identify field of wgp domain objects
* note the first use case happens to have ids of 'urls' rather than ints
*/

class Xandria_Uqims_Models_UIIdKey
	{
	protected $_POIDTypeCodeAsUITId;
	protected $_POIDItemId;

	/**
	*@desc holds any validation messages
	*/
	private $_messages = array();

	public function __construct( 
			$POIDTypeCodeAsUITId,
			$POIDItemId 
			)
		{
		//assert $POIDTypeCodeAsUITId is always int
		//assert $POIDItemId is string - i.e. could be either an int or perhaps an url

		$this->_POIDTypeCodeAsUITId = $POIDTypeCodeAsUITId;
		$this->_POIDItemId = $POIDItemId;
		}

	public function getPOIDTypeCodeAsUITId()
		{
		return $this->_POIDTypeCodeAsUITId;
		}

	public function getPOIDItemId()
		{
		return $this->_POIDItemId;
		}

	/**
	*@desc we cannot tell if it is truly valid, but we can tell if there is at least one POIDTypeCodeAsUITId and one POIDItemId to make up the gloabl id of this key
	*/
	public function isWellFormed()
		{
		$this->_messages = array();

		if ( ( $this->getPOIDTypeCodeAsUITId() > 0 )
			 && ( strlen( $this->getPOIDItemId() ) > 0 ) )
			{
			return TRUE;
			}

		//add some messages for help debugging
		if (!( $this->getPOIDTypeCodeAsUITId() > 0 ))
			{
			$this->_messages[] = 'POIDTypeCodeAsUITId is not greater than zero';
			}

		if (!( strlen( $this->getPOIDItemId() ) > 0 ))
			{
			$this->_messages[] = 'POIDItemId does not have a string length greater than zero';
			}

		return FALSE;
		}

	protected function makeUniqueIdString()
		{
		if (!( $this->isWellFormed() ))
			{
			throw new Exception( 
				'This ID is not well formed and hence it would be misleading to represent it as a string'
				 );
			}
		//else
		return 'UqimsUIIdKey:://'
			. 'POIDTypeCodeAsUITId:'
			. $this->getPOIDTypeCodeAsUITId() 
			. 'POIDItemId:' 
			. $this->getPOIDItemId();
		}

	public function getAsString()
		{
		return $this->makeUniqueIdString();
		}

	public function getMessages()
		{
		return $this->_messages;
		}

	}