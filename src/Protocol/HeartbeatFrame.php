<?php

namespace RPurinton\Bunny\Protocol;

use RPurinton\Bunny\Constants;

/**
 * Heartbeat AMQP frame.
 *
 * Heartbeat frames are empty.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class HeartbeatFrame extends AbstractFrame
{

    public function __construct()
    {
        parent::__construct(Constants::FRAME_HEARTBEAT, Constants::CONNECTION_CHANNEL, 0, "");
    }
}
