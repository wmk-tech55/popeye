<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Favorite extends Model
{
    use UsingUuid ,UsingSerializeDate ;

    protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

    public function favorited()
    {
        return $this->morphTo();
    }
 
} 
