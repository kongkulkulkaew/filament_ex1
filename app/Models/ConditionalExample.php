<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionalExample extends Model
{
    /** @use HasFactory<\Database\Factories\ConditionalExampleFactory> */
    use HasFactory;

    protected $fillable = [
        'user_type',
        'name',
        'email',
        'phone',
        'has_discount',
        'discount_type',
        'discount_value',
        'company_name',
        'company_tax_id',
        'organization_type',
        'organization_description',
        'needs_shipping',
        'shipping_address',
        'shipping_method',
        'shipping_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'has_discount' => 'boolean',
            'needs_shipping' => 'boolean',
            'discount_value' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
        ];
    }
}
