<?php

namespace Xandria\DAccess;

use Xandria\Db;

/**
 * @desc Abstract superclass Object-to-Uqims mapper for 'classifieds' domain entities
 * extended by advertising objects and classfcns
 *
 * NOTE: try to keep this simple.
 * Child classes should be able to subclass it without the need for
 * 'domain objects' with TypeCodeasUITids
 * or 'object watching' or 'tbl descs' or anything fancy
 */
abstract class AbstractORMapper extends AbstractMapper
{

    /**
     * @desc to keep a tally of queries for error reporting
     */
    protected $_queryCounter = 0;

    /**
     * @desc for those mappers that use a desc array checker
     */
    protected $_tblDescArrayChecker;


    //numAffectedRecords of the latest update query that was executed
    protected $_numAffectedRecords;


    public function countAll()
    {
        return $this->doCountAll();
    }

    public function update($domainObject)
    {
        //note clear message once
        $this->_messages = array();

        return $this->doUpdate($domainObject);
    }

    public function getDataSrcAdaptor()
    {
        return $this->_dataSrcAdaptor;
    }


    /**
     * @desc
     * @param string INSERT query
     * @return last insert id
     */
    protected function getNextAutoIncrementIdByExecutingINSERTQuery($queryStr)
    {
        try {
            $resultObj = $this->getDataSrcAdaptor()->query($queryStr);

            if (!($resultObj)) {
                throw new \Exception('No result returned from query()');
            }

            $lastInsertId = $this->getDataSrcAdaptor()->lastInsertId();

            if (!($lastInsertId)) {
                $message = 'no last insert id returned';
                throw new  \SQLException($message);
            }

            return $lastInsertId;
        } catch (\Exception $e) {
            //note we *can* add query to mssg cos this is a custom SQLException that witholds the message if attmepting to get thru standard exceptioninterface
            throw new Xandria_Db_SQLException(
                'Error During insert for query '
                . $queryStr
                . $e->getMessage()
            );
        }
    }

    /**
     * @desc this is for tables that have a natural key and so should not need to get last insert id
     *    * @param string INSERT query
     * @return void
     */
    protected function executeNaturalKeyedINSERTQuery($queryStr)
    {
        try {
            $resultObj = $this->getDataSrcAdaptor()->query($queryStr);

            //no need to return last insert id here cos ihas a natural key

            if (!($resultObj)) {
                throw new Exception('No result returned from query()');
            }
        } catch (Exception $e) {
            //note we *can* add query to mssg cos this is a custom SQLException that witholds the message if attmepting to get thru standard exceptioninterface
            throw new Xandria_Db_SQLException(
                'Error During insert for query '
                . $queryStr
                . PHP_EOL
                . $e->getMessage()
            );
        }
    }


    /**
     * @desc public temporarily to allow repo to do some queries
     * - until we have all the sql moved intot he mapper
     *
     * @return boolean TRUE on success
     */
    public function runMutativeQuery($updateQuery)
    {
        //clear num affected records
        $this->_numAffectedRecords = null;

        try {
            $DbResultStatementObj = $this->getDataSrcAdaptor()->query($updateQuery);

            if (!(is_object($DbResultStatementObj))) {
                $SQLmessage = 'no statement returned for update query: '
                    . $updateQuery;
                throw new Xandria_Db_SQLException($SQLmessage);
            }

            $this->_numAffectedRecords = $DbResultStatementObj->rowCount();

            return TRUE;
        } catch (Exception $e) {
            $SQLmessage = 'SQL Update Error Whilst runing update/mutative query '
                . $e->getMessage()
                . ' '
                . $updateQuery;

            throw new Xandria_Db_SQLException($SQLmessage);
        }
    }

    /**
     * @desc a runs a query that returns a result obj
     * @param SQL query
     * @return Zend_Db_Statement
     */
    protected function runQuery($query)
    {

        try {
            $DbResultStatementObj = $this->getDataSrcAdaptor()->query($query);

            if (!(is_object($DbResultStatementObj))) {

                throw new Xandria_Db_SQLException(
                    'No statement returned for query: '
                    . $query
                );
            }


            return $DbResultStatementObj;
        } catch (Exception $e) {
            $SQLmessage = 'SQL Error Whilst running query '
                . $e->getMessage()
                . ' '
                . $query;

            throw new Xandria_Db_SQLException($SQLmessage);
        }
    }

    protected function getNumAffectedRecords()
    {
        return $this->_numAffectedRecords;
    }


    /**
     * @desc template method cos has type check depending on type of datasource adaptor
     */
    protected function doSetDataSrcAdaptor(
        Zend_Db_Adapter_Abstract $dataSrcAdaptor
    )
    {
        $this->_dataSrcAdaptor = $dataSrcAdaptor;
    }


    /**
     * @desc called by abstract mapper
     * delegates to the abstract method that is specific to this type of data source
     * @param $resultArray - a db result array keyed by col name
     */
    protected function abstractDataMapperDoCreateDomainObjectUsingRawDataArray(
        $dataResultArray
    )
    {
        return $this->createDomainObjectUsingRawDbDataArray($dataResultArray);
    }

    /**
     * @desc
     * @return void
     * @throws Exception if out of bounds
     */
    protected function ensureLimitClauseValuesAreWithinBounds(
        $collectionSize,
        $maxCollectionSize,
        $startingPointer
    )
    {
        Xandria_Db_SQLQuery_LimitClause::ensureLimitClauseValuesAreWithinBounds(
            $collectionSize,
            $maxCollectionSize,
            $startingPointer
        );
    }


    /**
     * @param string - SQL query
     * @return Zend_Db_Statement
     * @throws Xandria_Db_SQLException on SQL Error or on FALSE resultObj
     */
    protected function getDbResultObjByExecutingSELECTQuery($queryStr)
    {
        try {

            $resultObj = $this->getDataSrcAdaptor()->query($queryStr);

            $this->_queryCounter++;

            if (!($resultObj)) {
                throw new Exception('No result returned from query()');
            }
            return $resultObj;
        } catch (Exception $e) {
            //note we *can* add query to mssg cos this is a custom SQLException that witholds the message if attmepting to get thru standard exceptioninterface
            throw new Xandria_Db_SQLException(
                'Error During select for query '
                . $queryStr
                . ' ' . $e->getMessage()
                . ' at query call count '
                . $this->_queryCounter
            );
        }
    }

    /**
     * @desc performs the processes to build a counted collection
     */
    protected function createCountedCollectionFromSelectQueryAndWHEREClause(
        $selectQueryStr,
        $WHEREClause,
        $tblDescSpec = null
    )
    {
        //we assume that the $selectQueryStr came from the
        //same tbl as defined by the $tblDescSpec - but that is tricky to check
        // - it will be checked at creation of domain object though

        //we assume that the $WHEREClause is exactly
        //the same as the one in the $selectQueryStr
        if (strpos($selectQueryStr, $WHEREClause) === false) {
            //strpos returns false if not found ins string
            //note we could even test for zero cos that would be wrong too , if the where was at position 0
            //but its good to make it clear that we know the return result possibilities of strpos
            throw new Exception(
                'The WHEREClause was not found in the selectQueryStr it should have been found'
            );
        }

        //then execute it
        $selectResultObj = $this->getDbResultObjByExecutingSELECTQuery($selectQueryStr); //implicitly throws exception

        //N O T E the some objects have BOTH a ResultArray AND a tbl desc array in each rawdata row
        $rows = $selectResultObj->fetchAll();

        $rawDataRows = $this->translateResultsetRowsToRawDataRowsArray(
            $rows,
            $tblDescSpec
        );

        //pass the rows to collection factory
        $collectionObj = $this->createCollectionMapper($rawDataRows);

        //now do a total count
        $totalNumberOfSatisfyingItemsInRepo = $this->getCountFromTableForWHEREClause(
            $WHEREClause,
            $tblDescSpec
        );

        //add the query for debugging
        $collectionObj->setDataQueryUsedToSelectCollection($selectQueryStr);
        $collectionObj->setTotalNumberOfSatisfyingItemsInRepo($totalNumberOfSatisfyingItemsInRepo);

        return $collectionObj;
    }

    /**
     * @desc
     * @throws exception if the check fails on any desc array elements
     */
    public function checkCriticalDescArrayElementsArray(
        $tbl_desc_array,
        $critical_desc_array_elements_array)
    {

        //implicitly throw exception on failure
        return $this->getTblDescArrayChecker()
            ->checkCriticalDescArrayElementsArray(
                $tbl_desc_array,
                $critical_desc_array_elements_array
            );
    }


    protected function getTblDescArrayChecker()
    {
        if (!($this->_tblDescArrayChecker)) {
            $this->_tblDescArrayChecker = new Xandria_Db_TblDescArrayChecker();
        }

        return $this->_tblDescArrayChecker;
    }


    /**
     * @desc converts db result set rows array into something that can be passed to a collection
     * @return array
     */
    protected function translateResultsetRowsToRawDataRowsArray(
        $rows,
        $tblDescSpec = null
    )
    {

        $rawDataRows = array();
        foreach ($rows as $originallyLoadedResultArray) {
            $rawDataRows[] = $this->assembleRawDbDataArrayParts(
                $originallyLoadedResultArray,
                $tblDescSpec
            );
        }
        return $rawDataRows;
    }

    /**
     * @desc this might be moved down to allow variation in child classes
     */
    protected function assembleRawDbDataArrayParts(
        $originallyLoadedResultArray,
        $tblDescSpec = null
    )
    {
        $rawDataArray = array();
        $rawDataArray['originallyLoadedResultArray'] = $originallyLoadedResultArray;
        $rawDataArray['tbl_desc_array'] = $tblDescSpec;
        return $rawDataArray;
    }


    /***
     * @desc
     * @return void
     * @throws Exception on non-well formed RawDbDataArray
     */
    protected function ensureRawDbDataArrayWellFormed($rawDataArray)
    {
        $isWellFormedSpecObj = $this->getIsRawDbDataArrayWellFormedSpecObj();

        if (!($isWellFormedSpecObj->isSatisfiedBy($rawDataArray))) {
            $mssg = 'Error - rawDataArray is not well formed';

            //for debugging
            //$mssg .= '<pre>'. print_r( $rawDataArray ) .'</pre>';

            foreach ($isWellFormedSpecObj->getMessages() as $specMssg) {
                $mssg .= ' - ' . $specMssg;
            }
            throw new Exception($mssg);
        }
    }

}