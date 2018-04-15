<?php

namespace AppEnvironment;

use Xandria\AppEnvironment;

class EnvironmentTypeTest extends \Codeception\Test\Unit
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
    public function testDoesEnvIndicateDevelopmentEnvironment()
    {
        $appEnv = AppEnvironment\EnvironmentType::doesEnvVarIndicateDevelopmentEnvironment();
        $this->assertEquals($appEnv, false);
    }
}