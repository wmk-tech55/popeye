<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class ProductAddition extends Pivot
{
    use UsingUuid, UsingSerializeDate;

    protected $table    = 'product_additions';

    protected $fillable = ['price', 'en_name', 'ar_name', 'product_id', 'group_id'];

    protected $appends = ['name_by_lang'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function group()
    {
        return $this->belongsTo(ProductGroupAddition::class, 'group_id');
    }

    public function getNameByLangAttribute()
    {
        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_name';

        return $this->{$field};
    }


    public function updateBulk($request)
    {

        if ($request->has('additions') &&  count($request->additions) > 0) {

            foreach ($request->additions as $addition) {
                $productAddition = ProductAddition::find($addition['id']);
                if ($productAddition) {
                    $data            = resolveDataValues($addition);
                    $productAddition->update($data);
                }
            }
        }
    }


    public function deleteBulk($request)
    {

        if ($request->has('additions') &&  count($request->additions) > 0) {

            foreach ($request->additions as $addition) {
                $productAddition = ProductAddition::find($addition);
                if ($productAddition) {

                    $productAddition->delete();
                }
            }
        }
    }


    /**
     * return lang key if exists in request or fall back to "ar"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'ar';
    }
}
