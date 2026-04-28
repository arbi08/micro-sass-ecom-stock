<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory, HasTenant;
    
    protected $fillable = [
        'tenant_id',
        'type',
        'title',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
// 1. Direct chat (user ↔ admin)
// 2. Support chat
// 3. Group chat (team/warehouse)