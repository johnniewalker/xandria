<?php

namespace Xandria\Db\SQLQuery;

class LimitClause
{

    /**
     * @desc
     * @param size wanted
     * @param max size allowed
     * @param starting pointer value
     * @return void
     * @throws Exception if out of bounds
     */
    static public function ensureLimitClauseValuesAreWithinBounds(
        $collectionSize,
        $maxCollectionSize,
        $startingPointer
    )
    {

        if (!((is_integer($collectionSize)) && ($collectionSize >= 0))) {
            throw new \Exception('The $collectionSize must be an integer greater than, or equal to, Zero');
        }

        if ($collectionSize > $maxCollectionSize) {
            throw new \Exception('The $collectionSize must be less than the $maxCollectionSize: ' . ( int )$maxCollectionSize);
        }

        if (!((is_integer($startingPointer)) && ($startingPointer >= 0))) {
            throw new \Exception('The $startingPointer must be an integer greater than, or equal to, Zero');
        }
    }
}