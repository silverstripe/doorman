<?php

namespace AsyncPHP\Doorman;

interface Shell
{
    /**
     * @todo description
     *
     * @param string $command
     * @param array  $parameters
     *
     * @return string
     */
    public function exec($command, array $parameters = array());
}
