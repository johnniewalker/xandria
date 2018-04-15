<?php

namespace ConcreteDemos;

use Xandria\ConcreteDemos;

class ConcreteORMapperTest extends \Codeception\Test\Unit
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
    public function testGetMessagesIsEmptyAfterInstantiation()
    {
        $concreteORMapper = new ConcreteDemos\DAccess\ConcreteORMapper();
        $this->isEmpty($concreteORMapper->getMessages());
    }
}