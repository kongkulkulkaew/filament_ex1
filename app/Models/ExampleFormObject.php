<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleFormObject extends Model
{
    /** @use HasFactory<\Database\Factories\ExampleFormObjectFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'address',
        'avatar',
        'department',
        'skills',
        'salary',
        'start_date',
        'documents',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'documents' => 'array',
            'salary' => 'decimal:2',
            'start_date' => 'date',
        ];
    }
}
