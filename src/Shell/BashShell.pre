<?php

namespace AsyncPHP\Doorman\Shell;

use AsyncPHP\Doorman\Shell;

final class BashShell implements Shell
{
    /**
     * @inheritdoc
     *
     * @param string $format
     * @param array $parameters
     *
     * @return array
     */
    public function exec($format, array $parameters = []): array
    {
        $parameters = array_map("escapeshellarg", $parameters);

        array_unshift($parameters, $format);

        $command = call_user_func_array("sprintf", $parameters);

        exec($command, $output);

        return $output;
    }
}
