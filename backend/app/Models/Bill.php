<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditingAuditable;

class Bill extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;

    protected $table = 'bills';
    protected $fillable = [
        'name',
        'bill_value',
        'due_date',
    ];

}
