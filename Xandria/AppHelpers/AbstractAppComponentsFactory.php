<?php


/**
* @desc Abstract Application-wide components finder.
*/
abstract class Xandria_AppHelpers_AbstractAppComponentsFactory
	{

	public function __construct(
			$appComponentsFactory 
			)
		{
		$this->_appComponentsFactory = $appComponentsFactory;
		}

	}