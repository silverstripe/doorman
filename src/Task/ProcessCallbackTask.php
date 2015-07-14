<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Process;

class ProcessCallbackTask extends CallbackTask implements Process
{
    /**
     * @var null|int
     */
    protected $id;

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
