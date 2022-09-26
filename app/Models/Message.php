<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    // Ignore time stamp columns updated_at and created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'thread_id',
        'body'
    ];

    public function thread() {
        return $this->belongsTo(Thread::class);
    }
}
