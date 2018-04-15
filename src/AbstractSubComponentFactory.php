<?php

namespace Xandria;

/**
 * @desc represents the basic interface to Sub Component Factory
 * class that sit in many of the components used by sib
 */
abstract class AbstractSubComponentFactory
{

    protected $_appComponentsFactory;

    public function __construct(
        \Xandria\AppHelpers\AbstractAppComponentsFactory $appComponentsFactory)
    {
        $this->_appComponentsFactory = $appComponentsFactory;
    }

}