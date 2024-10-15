<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate
;class ProductColor extends Pivot
{
    use UsingUuid ,UsingSerializeDate ;
}
