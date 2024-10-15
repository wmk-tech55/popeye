<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token'];

    const UPDATED_AT = false;
    public $incrementing = false;
    protected $primaryKey = null;
}
