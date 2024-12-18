<?php

namespace App\Jobs;

use App\Mail\TaskReminder;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Sprawdzenie, czy zadanie nadal istnieje
        if (!$this->task->exists) {
            return;
        }

        // Sprawdzenie, czy użytkownik ma przypisanego e-maila
        if (!$this->task->user || !$this->task->user->email) {
            return;
        }

        // Wysyłanie e-maila
        Mail::to($this->task->user->email)->send(new TaskReminder($this->task));
    }
}

