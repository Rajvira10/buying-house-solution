<?php

namespace App\Models;

use App\Models\User;
use App\Models\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_id',
        'user_id',
        'message',
        'attachment',
        'status',
        'type',
        'read_at',
        'delivered_at',
        'sent_at',
    ];
    
    protected $casts = [
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function queryModel()
    {
        return $this->belongsTo(Query::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAttachment()
    {
        return $this->type === 'attachment';
    }

    public function isText()
    {
        return $this->type === 'text';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isRead()
    {
        return $this->status === 'read';
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    public function markAsDelivered()
    {
        $this->status = 'delivered';
        $this->delivered_at = now();
        $this->save();
    }

    public function markAsRead()
    {
        $this->status = 'read';
        $this->read_at = now();
        $this->save();
    }
    
}
