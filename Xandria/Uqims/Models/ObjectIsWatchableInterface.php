<?php
/**
*@desc interface implemented by Domain Objects to ensure that our object watcher can interact with them properly
*/

interface Xandria_Uqims_Models_ObjectIsWatchableInterface
	{
	/**
	*@return Xandria_Uqims_Models_UIIdKey
	*/
	public function getGlobalIdObj();
	}