<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Expires;
use AsyncPHP\Doorman\Process;

class ProcessCallbackTask extends CallbackTask implements Expires, Process
{
    /**
     * @var null|int
     */
    protected $id;

    /**
     * @var null|int
     */
    protected $startTime;

    /**
     * @var null|int
     */
    protected $expiredAt;

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

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getExpiresIn()
    {
        return -1;
    }

    /**
     * @inheritdoc
     *
     * @param int $startedAt
     *
     * @return bool
     */
    public function shouldExpire($startedAt)
    {
        $this->expiredAt = time();

        return true;
    }

    /**
     * Checks whether this task has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        return is_int($this->expiredAt);
    }
}
