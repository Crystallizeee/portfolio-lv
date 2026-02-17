<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'position',
        'status',
        'applied_date',
        'salary',
        'link',
        'notes',
    ];

    protected $casts = [
        'applied_date' => 'date',
    ];

    public static function statuses()
    {
        return [
            'applied' => 'Applied',
            'interview' => 'Interview',
            'offer' => 'Offer',
            'rejected' => 'Rejected',
        ];
    }
}
