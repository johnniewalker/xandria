<?php

namespace Xandria\Uquims\Models;

/**
 * @desc interface implemented by Domain Objects to ensure that our object watcher can interact with them properly
 */

interface ObjectIsWatchableInterface
{
    /**
     * @return \Xandria\Uquims\Models\UIIdKey
     */
    public function getGlobalIdObj();
}