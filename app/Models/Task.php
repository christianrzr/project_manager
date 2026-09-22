<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'task_name',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'Completed'
            && $this->due_date !== null
            && $this->due_date->lt(Carbon::today());
    }

    public function isDueToday(): bool
    {
        return $this->status !== 'Completed'
            && $this->due_date !== null
            && $this->due_date->isToday();
    }

    public function isUpcoming(): bool
    {
        return $this->status !== 'Completed'
            && $this->due_date !== null
            && $this->due_date->gt(Carbon::today());
    }

    public function getSubtaskProgressAttribute(): int
    {
        $total = $this->subtasks->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->subtasks->where('is_completed', true)->count();
        return (int) round(($completed / $total) * 100);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'Pending');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'In Progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'Completed');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', 'Completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today());
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->where('status', '!=', 'Completed')
            ->whereDate('due_date', Carbon::today());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', '!=', 'Completed')
            ->whereDate('due_date', '>', Carbon::today());
    }
}
