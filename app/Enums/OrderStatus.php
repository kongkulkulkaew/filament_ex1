<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    
    /**
     * Get the label for display
     */
    public function label(): string
    {
        return match($this) {
            self::Pending => 'รอดำเนินการ',
            self::Confirmed => 'ยืนยันแล้ว',
            self::Shipped => 'จัดส่งแล้ว',
            self::Delivered => 'ส่งถึงแล้ว',
            self::Cancelled => 'ยกเลิก',
        };
    }
    
    /**
     * Get the color for Filament badges
     */
    public function color(): string
    {
        return match($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::Shipped => 'primary',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }
    
    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return !in_array($this, [self::Shipped, self::Delivered, self::Cancelled]);
    }
}
