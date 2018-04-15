<?php

namespace Xandria\DAccess;

/**
 * @desc layer super type for all mappers  ( OR and OU )
 */

abstract class AbstractMapper implements ICollectionElementCreator
{
    protected $_messages = array();

    /**
     * @desc the currently used adaptor either a db adaptor or a UqimsDataAccess adaptor depending on child mapper class
     */
    protected $_dataSrcAdaptor;


    public function createDomainObjectUsingRawDataArray($dataResultArray)
    {
        return $this->abstractDataMapperDoCreateDomainObjectUsingRawDataArray($dataResultArray);
    }

    /**
     * @desc unfortunatley this is overidden in subclasses that magically get an adaptor if none inject
     */
    protected function getDataSrcAdaptor()
    {
        return $this->_dataSrcAdaptor;
    }

    protected function setDataSrcAdaptor($dataSrcAdaptor)
    {
        $this->doSetDataSrcAdaptor($dataSrcAdaptor);
    }

    //abstract protected function doSetDataSrcAdaptor( $dataSrcAdaptor );

    /**
     * @desc template method cos it calls static self method to get the default adaptor
     */
    //abstract protected function doGetDefaultDataSrcAdaptor();


    /**
     * @desc if object is already present in identity map ( object watcher ) - then returns it, else returns null
     */
    protected function getDomainObjectFromObjectWatcher($idObj)
    {
        $objectWatcher = \Xandria\Uquims\Models\ObjectWatcher::getInstance();
        $domainObject = $objectWatcher->getObjectByGlobalId($idObj);

        if (is_object($domainObject)) {
            return $domainObject;
        }


        return null;
    }


    public function getMessages()
    {
        return $this->_messages;
    }


}