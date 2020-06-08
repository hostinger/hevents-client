<?php

namespace Hostinger\Hevents;

interface RequestHandlerInterface
{
    /**
     * @param array $event
     * @return bool
     */
    public function send(array $event);
}
