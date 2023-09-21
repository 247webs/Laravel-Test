<?php

namespace App\Models;

use App\Events\ProductPurchasedEvent;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'roles',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'roles' => 'array',
    ];

    /**
     * Transction is belonging to the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transctions()
    {
        return $this->hasMany(Transction::class, 'user_id', 'id');
    }

    /**
     * Check if the user is admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(Constants::ROLE_ADMIN, $this->roles);
    }

    public static function List()
    {
        return self::where("roles", "not like", "%".Constants::ROLE_ADMIN."%")->get();
    }

    /**
     * Added transction after payment
     * 
     * @param mixed $data
     */
    public function addTransction(mixed $data)
    {
        $transaction = new Transction();
        $transaction->fill([
            'user_id' => $this->id,
            'amount' => $data['amount'] ?? 0,
            'currency' => $data['currency'] ?? null,
            'payment_intent_id' => $data['id'] ?? null,
            'customer_id' => $data['customer'] ?? null,
            'payment_method_id' => $data['payment_method'] ?? null,
            'status' => $data['status'] ?? 'failed',
            'product_id' => $data['product_id'] ?? null,
            'product_name' => $data['product_name'] ?? null,
        ]);

        $transaction->save();
        ProductPurchasedEvent::dispatch($this);
    }

    /**
     * Added payment method before payment
     * 
     * @param string $paymentMethod
     * @return PaymentMethod
     */
    public function attachPaymentMethod(string $paymentMethod): PaymentMethod
    {
        $method = new PaymentMethod();
        $data = $this->findPaymentMethod($paymentMethod)->toArray();
        $method->fill([
            'user_id' => $this->id,
            'refrence_id' => $data['id'] ?? null,
            'type' => $data['type'] ?? null,
            'last4degit' => $data[$data['type']]['last4'] ?? null,
        ]);

        $method->save();
        return $method;
    }

    /**
     * Adding the role of user
     * 
     * @param string $role
     */
    public function addRole(string $role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles = array_merge($this->roles, [$role]);
            $this->save();
        }
    }
}
