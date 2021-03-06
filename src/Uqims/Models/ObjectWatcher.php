<?php

namespace Xandria\Uquims\Models;

/**
 * @desc generic object watcher - singleton to do two things for wgp domain objects:
 *   * identity map
 *   * unit of work
 */
class ObjectWatcher
{
    private static $instance;

    private function __construct()
    {
    }

    protected $_ObjectsArray = array();


    static function getInstance()
    {
        if (!(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }


    /**
     * @desc used in testing to remove the instance
     */
    static function clearInstance()
    {
        self::$instance = null;
    }

    public function getObjectByGlobalId(\Xandria\Uquims\Models\UIIdKey $idObj)
    {
        if (!($idObj->isWellFormed())) {
            return null;
        }

        return ((isset($this->_ObjectsArray[$idObj->getAsString()]))
            && (is_object($this->_ObjectsArray[$idObj->getAsString()])))
            ? $this->_ObjectsArray[$idObj->getAsString()] : null;
    }

    public function addObject(ObjectIsWatchableInterface $domainObject)
    {
        $this->doAddObject($domainObject);
    }

    protected function doAddObject(ObjectIsWatchableInterface $domainObject)
    {
        $idObj = $domainObject->getGlobalIdObj();

        if (!($idObj->isWellFormed())) {
            throw new \Exception('Cannot add object to watched list becuase the id is not well formed');
        }

        $this->_ObjectsArray[$idObj->getAsString()] = $domainObject;
    }

}