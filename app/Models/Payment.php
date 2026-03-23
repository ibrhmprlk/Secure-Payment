<?php
// app/Models/Payment.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Payment extends Model
{
    protected $fillable = [
        'stripe_payment_intent_id',
        'amount_cents',
        'currency',
        'status',
        'card_holder',
        'user_id',
    ];
 
    protected $casts = [
        'amount_cents' => 'integer',
        'user_id'      => 'integer',
    ];
 
    /**
     * Amount in USD (for display).
     */
    public function getAmountUsdAttribute(): float
    {
        return $this->amount_cents / 100;
    }
 
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}