<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Task extends Model
{
    use HasFactory, BelongsToTenant;
    protected $guarded = ["id"];
    protected $with = ["user"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
