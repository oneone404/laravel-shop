<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShowPopupEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;
    public $userId;

    public function __construct($userId, $message)
    {
        $this->userId = $userId;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // Mỗi user 1 channel riêng
        return new PrivateChannel('popup-channel.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'show-popup';
    }
}
