<?php

namespace AsyncPHP\Doorman;

interface Process
{
    /**
     * @return null|int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);
}
