<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\States\PendingState;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Gắn State Pattern vào Model để lấy nhãn trạng thái trực quan
    public function getStateLabel()
    {
        $state = match($this->status) {
            'pending' => new PendingState(),
            'delivering' => new \App\States\DeliveringState(),
            'completed' => new \App\States\CompletedState(),
            default => new PendingState(),
        };
        return $state->getLabel();
    }
}