<?php

namespace Xandria\AppHelpers;

/**
 * @desc Abstract Application-wide components finder.
 */
abstract class AbstractAppComponentsFactory
{

    public function __construct(
        $appComponentsFactory
    )
    {
        $this->_appComponentsFactory = $appComponentsFactory;
    }

}