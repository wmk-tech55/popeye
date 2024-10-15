<?php

namespace MixCode\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait UsingFilters
{
    public static function getFiltersKeys()
    {
        return [
            'country',
            'city',
            'categorization',
            'provider',
            'search_with_key',
            'date_from',
            'date_to',
            'price_from',
            'price_to',
            'price_low',
            'price_high',
            'most_popular',
            'price',
            'price_range',
            'most_recently',
            'general',
        ];
    }

    public static function getOrderByFiltersKeys()
    {
        return [
            'price_low',
            'price_high',
            'most_popular',
            'most_recently',
        ];
    }

    /**
     * Scoped Filter Implementation
     *
     * @param Builder $builder
     * @param Request $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, Request $request)
    {
        $filters = $this->generateFilterMethods($request->keys());

        $filters->each(function ($filter) use ($request, $builder) {
            $this->{$filter}($request, $builder);
        });

        return $builder;
    }

    /**
     * Filter products by General Data 
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function  filterByGeneral(Request $request, Builder $builder)
    {


        if ($request->has('general') && $request->filled('general') && $request->general == 'price_low') {
            $builder =  $this->orderByRaw('cast(price_after_discount as unsigned) asc');
        }

        if ($request->has('general') && $request->filled('general') && $request->general == 'price_high') {
            $builder =  $this->orderByRaw('cast(price_after_discount as unsigned) desc');
        }

        if ($request->has('general') && $request->filled('general') && $request->general == 'most_recently') {

            $builder =  $this->latest('created_at');
        }


        if ($request->has('general') && $request->filled('general') && $request->general == 'most_popular') {
            $builder =  $this->orderByDesc('views_count');
        }

        return $builder;
    }
    /**
     * Filter products by Country id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCountry(Request $request, Builder $builder)
    {
        if ($request->has('country') && $request->filled('country')) {
            $builder->where('country_id', $request->country);
        }

        return $builder;
    }

    /**
     * Filter products by City id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCity(Request $request, Builder $builder)
    {
        if ($request->has('city') && $request->filled('city')) {
            $builder->where('city_id', $request->city);
        }

        return $builder;
    }

    /**
     * Filter products by Categories Ids
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCategories(Request $request, Builder $builder)
    {

        if ($request->has('categories') && $request->filled('categories')) {
            $builder->whereHas('categories', function (Builder $b) use ($request) {
                $b->whereIn('categories.id', $request->categories);
            })
                ->orWhereHas('categories', function (Builder $b) use ($request) {
                    $b->whereIn('categories.parent_id', $request->categories);
                });
        }

        return $builder;
    }



    /**
     * Filter products by categorization Id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCategorization(Request $request, Builder $builder)
    {

        if ($request->has('categorization') && $request->filled('categorization')) {
            $builder->where('categorization_id', $request->categorization);
        }

        return $builder;
    }
    /**
     * Filter products by creator Id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByProvider(Request $request, Builder $builder)
    {

        if ($request->has('provider') && $request->filled('provider')) {
            $builder->where('creator_id', $request->provider);
        }

        return $builder;
    }

    /**
     * Filter products by title  Ids
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterBySearchWithKey(Request $request, Builder $builder)
    {
        if ($request->has('search_with_key') && $request->filled('search_with_key')) {

            $builder->where('ar_name', 'like', '%' . $request->search_with_key . '%')
                ->orWhere('en_name', 'like', '%' . $request->search_with_key . '%')
                ->orWhere('en_overview', 'like', '%' . $request->search_with_key . '%')
                ->orWhere('ar_overview', 'like', '%' . $request->search_with_key . '%')
                ->orWhereHas('creator', function ($q) use ($request) {
                    return $q->where('username', 'like', '%' . $request->search_with_key . '%')
                        ->orWhere('username', 'like', '%' . $request->search_with_key . '%');
                })->orWhereHas('categories', function ($q) use ($request) {
                    return $q->where('en_name', 'like', '%' . $request->search_with_key . '%')
                        ->orWhere('ar_name', 'like', '%' . $request->search_with_key . '%');
                });
        }

        return $builder;
    }

    /**
     * Filter products by Date From (start date)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByDateFrom(Request $request, Builder $builder)
    {
        if ($request->has('date_from') && $request->filled('date_from')) {
            $builder->whereDate('date_from', '>=', $request->date_from);
        }

        return $builder;
    }

    /**
     * Filter products by Date To (end date)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByDateTo(Request $request, Builder $builder)
    {

        if ($request->has('date_to') && $request->filled('date_to')) {
            $builder->whereDate('date_to', '<=', $request->date_to);
        }

        return $builder;
    }

    /**
     * Filter products by Price From (start price)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceFrom(Request $request, Builder $builder)
    {
        if ($request->has('price_from') && $request->filled('price_from')) {
            $builder->where('price_after_discount', '>=', floatval($request->price_from));
        }

        return $builder;
    }

    /**
     * Filter products by Price From (end price)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceTo(Request $request, Builder $builder)
    {
        if ($request->has('price_to') && $request->filled('price_to')) {
            $builder->where('price_after_discount', '<=', $request->price_to);
        }

        return $builder;
    }



    /**
     * Filter products by Price Range from 1000 to 20000
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceRange(Request $request, Builder $builder)
    {

        if ($request->has('price_range') && $request->filled('price_range')) {
            $builder->whereBetween('price_after_discount',  [0, $request->price_range]);
        }

        return $builder;
    }

    /**
     * Filter products by lower price
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceLow(Request $request, Builder $builder)
    {
        if ($request->has('price_low')) {
            // $builder->oldest('price');
            //  $builder->orderBy('price', 'asc'); // Used For More Readability
            $builder->orderByRaw('cast(price_after_discount as unsigned) asc');
        }

        return $builder;
    }

    /**
     * Filter products by higher price
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPriceHigh(Request $request, Builder $builder)
    {
        if ($request->has('price_high')) {
            // $builder->latest('price'); 
            // $builder->orderByDesc('price'); // Used For More Readability
            $builder->orderByRaw('cast(price_after_discount as unsigned) desc');
        }

        return $builder;
    }



    /**
     * Filter products by  price
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByPrice(Request $request, Builder $builder)
    {

        if ($request->has('price')) {

            if ($request->price == 'price_high') {

                $builder->orderByRaw('cast(price_after_discount as unsigned) desc');
            } elseif ($request->price == 'price_low') {

                $builder->orderByRaw('cast(price_after_discount as unsigned) asc');
            }

            return $builder;
        }
    }
    /**
     * Filter products by most popular
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByMostPopular(Request $request, Builder $builder)
    {
        if ($request->has('most_popular')) {
            // $builder->latest('views_count');
            $builder->orderByDesc('views_count'); // Used For More Readability
        }

        return $builder;
    }

    /**
     * Filter products by most recently (just created)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByMostRecently(Request $request, Builder $builder)
    {
        if ($request->has('most_recently')) {
            // created_at is the default for ordering but for more readability i wrote it.
            $builder->latest('created_at');
        }

        return $builder;
    }

    /**
     * Generate filter methods names from request input keys
     *
     * @param array $keys
     * @return \Illuminate\Support\Collection
     */
    protected function generateFilterMethods(array $keys)
    {
        return collect($keys)->map(function ($key) {
            if (in_array($key, static::getFiltersKeys())) {
                return 'filterBy' . Str::studly($key);
            }

            return null;
        })->filter()->values();
    }
}
