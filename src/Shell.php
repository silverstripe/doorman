<?php

namespace AsyncPHP\Doorman;

interface Shell
{
    /**
     * @param string $command
     * @param array  $parameters
     *
     * @return string
     */
    public function exec($command, array $parameters = array());
}
