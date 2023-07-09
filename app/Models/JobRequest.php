<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;
    protected $table = 'job_requests';
    protected $primaryKey="id";
    protected $fillable = [
        'status', 'job_id', 'user_id', 'company_id'
    ];
}
