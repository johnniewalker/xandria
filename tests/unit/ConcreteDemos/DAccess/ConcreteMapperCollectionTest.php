<?php

namespace ConcreteDemos;

use Xandria\ConcreteDemos;


class ConcreteMapperCollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

    // tests
    public function testGetDataQueryUsedToSelectCollectionIsEmptyAfterInstantiation()
    {
        $concreteORMapperCollection = new ConcreteDemos\DAccess\ConcreteMapperCollection(null, null);
        $this->isEmpty($concreteORMapperCollection->getDataQueryUsedToSelectCollection());
    }
}