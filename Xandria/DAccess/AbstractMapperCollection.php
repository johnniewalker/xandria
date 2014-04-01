<?php

/**
*@desc generic mapper collection for both OR and UR mappers 
*/
abstract class Xandria_DAccess_AbstractMapperCollection implements Iterator, Countable
	{

	/**
	* @desc raw data should only be present if a mapper is present
	*/
	protected $rawDataArray = array();

	/**
	*@desc holds the objects that have been added or reconstituted from raw data
	*/
	protected $objectsArray = array();

	protected $_collectionElementCreator = null;

	protected $total = 0;
	protected $pointer = 0;

	/**
	* @desc some properties useful for de-bugging
	*/
	protected $totalNumberOfSatisfyingItemsInRepo;

	protected $DataQueryUsedToSelectCollection;


	public function __construct( $rawDataRows = null, $collectionElementCreator = null )
		{
		if ( $rawDataRows && $collectionElementCreator )
			{
			$this->initRawDataAndMapper( $rawDataRows, $collectionElementCreator );
			}
		}

	/**
	*@desc should only be called by constructor
	* @param array raw data rows
	* @param ICollectionElementCreator ( Xandria_DAccess_AbstractORMapper ) 
	*/
	protected function initRawDataAndMapper( 
			$rawDataRows, 
			Xandria_DAccess_ICollectionElementCreator $collectionElementCreator 
			)
		{

		//set the mapper
		$this->doSetCollectionElementCreator( $collectionElementCreator );

		//set the data
		$numrows = count( ( array )$rawDataRows );
		$this->total += $numrows;

		for ( $idx = 0 ; $idx < $numrows; ++$idx )
			{
			$this->rawDataArray[]  = $rawDataRows[$idx];
			}
		}

	/**
	* @desc default behaviour, maybe overidden by subclasses that want to add extra type checks
	*/
	protected function doSetCollectionElementCreator( $collectionElementCreator )
		{
		$this->_collectionElementCreator = $collectionElementCreator;
		}


	/**
	* leave the type checking of the arg to the concrete subclasses 
	* - to avoid being forced to create an DomainEntityAbstract if i don't want to
	*/
	protected function doAdd( $object )
		{
		$this->notifyAccess();
		$this->objectsArray[$this->total] = $object;
		$this->total++;
		}

	protected function notifyAccess()
		{
		//deliberatly left blank - see pg 273 and 287 OOP Book
		}


	/**
	*@desc checks if there is something at the pointer position 
	* - ( without loading raw data yet )
	*/
	public function hasElementAt( $num )
		{
		if ( ( $num >= $this->total ) || ( $num < 0 ) )
			{
			return FALSE;
			}

		if ( ( isset( $this->objectsArray[$num] ) ) )
			{
			return TRUE;
			}

		//assumes that the $this->rawDataArray was loaded as a zero-indexed arrayc in initRawDataAndMapper()
		if ( $this->rawDataArray[$num] )
			{
			return TRUE;
			}
		}

	/**
	*@desc public facing method to delegate to get object at
	*/
	public function getElementAt( $num )
		{
		return $this->getObjectAt( $num );
		}


	private function getObjectAt( $num )
		{
		$this->notifyAccess();
		if ( ( $num >= $this->total ) || ( $num < 0 ) )
			{
			return null;
			}

		if ( ( isset( $this->objectsArray[$num] ) ) )
			{
			return $this->objectsArray[$num];
			}

		//assumes that the $this->rawDataArray was loaded as a zero-indexed arrayc in initRawDataAndMapper()
		if ( $this->rawDataArray[$num] )
			{
			$this->objectsArray[$num] = $this->getCollectionElementCreator()
							->createDomainObjectUsingRawDataArray( $this->rawDataArray[$num] );

			return $this->objectsArray[$num];
			}
		}

	protected function getCollectionElementCreator()
		{
		return $this->_collectionElementCreator;
		}

	//to comply with Iterator interface

	public function rewind()
		{
		$this->pointer = 0;
		}

	public function current()
		{
		return $this->getObjectAt( $this->pointer );
		}

	public function key()
		{
		return $this->pointer;
		}

	public function next()
		{
		$row = $this->getObjectAt( $this->pointer );
		if ( $row ) 
			{
			$this->pointer++;
			}
		return $row;
		}

	//confirm that there is an element at the current pointer position
	public function valid()
		{
		return (!( is_null( $this->current() ) ));
		}

	public function count()
		{
		return $this->total;
		}


	/**
	* informational methods
	*/

	/***
	*@desc if the collection was created from a db query - we can set this based on a count( * ) query
	* @param int the result of a count( * ) query that used the exactly the same WHERE clause that was used to pull the collection data
	*/
	public function setTotalNumberOfSatisfyingItemsInRepo( $totalNumberOfSatisfyingItemsInRepo )
		{
		$this->totalNumberOfSatisfyingItemsInRepo = $totalNumberOfSatisfyingItemsInRepo;
		}

	public function getTotalNumberOfSatisfyingItemsInRepo()
		{
		return $this->totalNumberOfSatisfyingItemsInRepo;
		}

	public function isValueSetForTotalNumberOfSatisfyingItemsInRepo()
		{
		return ( isset( $this->totalNumberOfSatisfyingItemsInRepo ) ) ? TRUE : FALSE;
		}

	/***
	*@desc just a handy way of seeing the query when used in admin context
	*/
	public function setDataQueryUsedToSelectCollection( $DataQueryUsedToSelectCollection )
		{
		$this->DataQueryUsedToSelectCollection = $DataQueryUsedToSelectCollection;
		}

	public function getDataQueryUsedToSelectCollection()
		{
		return $this->DataQueryUsedToSelectCollection;
		}

	public function isValueSetForDataQueryUsedToSelectCollection()
		{
		return ( isset( $this->DataQueryUsedToSelectCollection ) ) ? TRUE : FALSE;
		}

	/**
	* @desc A booby trap peice of code that acts as a timebomb that will probably explode at the most inconveinient moment
	* 
	* This is a symtpom of our policy of forcing the controller to specify a collection size when doing finds. 
	* The idea is to avoid cases where we accidentally end up returning millions of rows and not seeing any notices.
	*/
	public function ensurePaginationNotNeededForCollection()
		{
		if ( count( $this ) < $this->getTotalNumberOfSatisfyingItemsInRepo() )
			{
			//Our arbitrary $collectionSize was not enough to find ALL
			throw new Exception(
				'Code change required. Our arbitrary $collectionSize was not enough to find ALL items. We need to add pagination to this action.'
				);
			}
		}


	}