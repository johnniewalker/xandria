<?php


//receives
require_once( 'Xandria/AppHelpers/AbstractAppComponentsFactory.php' );

/**
* @desc represents the basic interface to Sub Component Factory 
* class that sit in many of the components used by sib 
*/
abstract class Xandria_AbstractSubComponentFactory
	{

	protected $_appComponentsFactory;

	public function __construct(
			Xandria_AppHelpers_AbstractAppComponentsFactory $appComponentsFactory )
		{
		$this->_appComponentsFactory = $appComponentsFactory;
		}

	}