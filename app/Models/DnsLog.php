<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DnsLog extends Model
{
    protected $fillable = ['user_id', 'domain', 'client_ip', 'queried_at', 'classification'];

    protected $casts = [
        'queried_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}