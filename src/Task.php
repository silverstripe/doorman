<?php

namespace AsyncPHP\Doorman;

use Serializable;

interface Task extends Serializable
{
    /**
     * @todo description
     *
     * @return string
     */
    public function getHandler();

    /**
     * @todo description
     *
     * @return array
     */
    public function getData();

    /**
     * @todo description
     *
     * @return bool
     */
    public function ignoresRules();

    /**
     * @todo description
     *
     * @return bool
     */
    public function stopsSiblings();
}
