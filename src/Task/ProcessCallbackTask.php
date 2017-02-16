<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Expires;
use AsyncPHP\Doorman\Process;

final class ProcessCallbackTask extends CallbackTask implements Expires, Process
{
    /**
     * @var null|int
     */
    private $id;

    /**
     * @var null|int
     */
    private $expiredAt;

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
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getExpiresIn(): int
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
    public function shouldExpire(int $startedAt): bool
    {
        $this->expiredAt = time();

        return true;
    }

    /**
     * Checks whether this task has expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        return is_int($this->expiredAt);
    }
}
