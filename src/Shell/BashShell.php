<?php

namespace AsyncPHP\Doorman\Shell;

use AsyncPHP\Doorman\Shell;

class BashShell implements Shell
{
    /**
     * @inheritdoc
     *
     * @param string $command
     * @param array  $parameters
     *
     * @return string
     */
    public function exec($format, array $parameters = array())
    {
        $parameters = array_map(function($parameter) {
            return escapeshellarg($parameter);
        }, $parameters);

        array_unshift($parameters, $format);

        $command = call_user_func_array("sprintf", $parameters);

        return exec($command);
    }
}
