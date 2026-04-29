<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Constante para não espalhar strings pelo código
    const STATUSES = ['pending', 'in_progress', 'completed'];

    // Scopes atualizados
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper útil nas views
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
