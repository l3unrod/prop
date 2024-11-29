<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }
}
