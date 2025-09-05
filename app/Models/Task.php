<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    const STATUS_TODO = 'To-Do';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_DONE = 'Done';

    public static function getStatuses()
    {
        return [
            self::STATUS_TODO,
            self::STATUS_IN_PROGRESS,
            self::STATUS_DONE,
        ];
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_TODO => 'secondary',
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_DONE => 'success',
            default => 'secondary',
        };
    }
}
