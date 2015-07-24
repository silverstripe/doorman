<?php

namespace AsyncPHP\Doorman;

interface Process
{
    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return null|int
     */
    public function getId();
}
