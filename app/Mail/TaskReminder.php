<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class TaskReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $taskUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->taskUrl = URL::route('tasks.show', $task->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $taskUrl = config('app.url') . route('tasks.show', $this->task->id, false);

        return $this->subject('Przypomnienie o zadaniu: ' . $this->task->name)
            ->markdown('emails.task_reminder')
            ->with(['taskUrl' => $taskUrl]);
    }

}
