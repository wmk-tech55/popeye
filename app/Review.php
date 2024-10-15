<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Review extends Model
{
    use UsingUuid ,UsingSerializeDate ,  SoftDeletes;

    protected $fillable = ['name', 'email', 'review', 'rate', 'product_id', 'customer_id'];


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

}
