<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogTaskActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $action,
        public Task $task
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Task {$this->action}: ID {$this->task->id} - {$this->task->title} (Tenant: {$this->task->tenant_id})");
    }
}
