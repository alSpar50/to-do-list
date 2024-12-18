<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Task extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'status',
        'due_date',
        'user_id',
        'share_token',
    ];

    protected $casts = [
        'due_date' => 'date', // Dodaj tę linię
    ];

    /**
     * Zdefiniuj opcje logowania.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'priority', 'status', 'due_date'])
            ->useLogName('task')
            ->setDescriptionForEvent(fn(string $eventName) => "Task has been {$eventName}");
    }

    /**
     * Relacja do użytkownika.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

