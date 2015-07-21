<?php

namespace AsyncPHP\Doorman;

interface Expires
{
    /**
     * @todo description
     *
     * @return int
     */
    public function getExpiresIn();

    /**
     * @todo description
     *
     * @param int $startedAt
     *
     * @return bool
     */
    public function shouldExpire($startedAt);
}
