<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    /**
     * Doug - It's not wrong per-se, but why fight what Laravel expects here? Why not just use 'threads'?
     */
    // Need to explicitly state table name to not throw errors during insertion
    // since Laravel adds plurals to table names by default
    protected $table = 'thread';

    // Ignore time stamp columns updated_at and created_at
    public $timestamps = false;

    protected $fillable = [
        'title'
    ];

    public function message() {
        return $this->hasMany(Message::class);
    }
}
