<?php

namespace App\Jobs\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CleanupOldLogsJob implements ShouldQueue
{
    use Queueable;
    public function handle(): void {}
}
