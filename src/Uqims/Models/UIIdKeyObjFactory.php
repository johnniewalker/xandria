<?php

namespace Xandria\Uquims\Models;


/**
 * @desc factory object to create Xandria_Uqims_Models_UIIdKey
 *
 * i created it so that it could be injected into entities that wish to create a UIIdKey in their construtors
 *
 * passing a factory to do that job makes those entiies more testable
 *
 */
class UIIdKeyObjFactory
{
    public function createNewUIIdKeyObj($typeCodeasUITid, $pOIDItemId)
    {
        return new UIIdKey($typeCodeasUITid, $pOIDItemId);
    }
}
