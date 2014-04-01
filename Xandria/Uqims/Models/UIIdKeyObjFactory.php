<?php

require_once('Xandria/Uqims/Models/UIIdKey.php');

/**
*@desc factory object to create Xandria_Uqims_Models_UIIdKey
*
* i created it so that it could be injected into entities that wish to create a UIIdKey in their construtors
*
* passing a factory to do that job makes those entiies more testable
*
*/
class Xandria_Uqims_Models_UIIdKeyObjFactory
	{
	public function createNewUIIdKeyObj($typeCodeasUITid, $pOIDItemId)
		{
		return new Xandria_Uqims_Models_UIIdKey($typeCodeasUITid, $pOIDItemId);
		}
	}
