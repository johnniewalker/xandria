<?php

/**
*@desc implemented by Mappers 
* Used by AbstractMapperCollection to create a 
* collection item from raw data to serve up to client
*/
interface Xandria_DAccess_ICollectionElementCreator
	{
	/**
	* @desc given raw data returns a domain object or praps just a result array (if no domain object class exists yet)
	* @param raw data array 
	* @return variable that represents an item /entity
	*/
	public function createDomainObjectUsingRawDataArray( $rawDataArray );

	}