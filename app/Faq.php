<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingStatus;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Faq extends Model
{
    use UsingUuid ,UsingSerializeDate ,
        UsingStatus,
        UsingApiScopes,
        RefreshCache,
        SoftDeletes;

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'disable';

    const CREATOR_RELATION_KEY = 'creator_id';

    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $appends = ['question_by_lang', 'answer_by_lang'];

    protected $fillable = [
        'en_question',
        'ar_question',
        'en_answer',
        'ar_answer',
        'status',
        'creator_id',
    ];

    public function path()
    {
        return route('dashboard.faqs.show', $this);
    }

    public function apiPath()
    {
        return route('api.faqs.show', $this);
    }

    public function viewPath()
    {
        return route('faqs.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function getQuestionByLangAttribute()
    {
        $field = $this->getLang() . '_question';

        return $this->{$field};
    }

    public function getAnswerByLangAttribute()
    {
        $field = $this->getLang() . '_answer';

        return $this->{$field};
    }

    /**
     * Return lang key based on if request wants json response for api
     *
     * @return string
     */
    protected function getLang()
    {
        return request()->wantsJson() ? $this->hasLang() : app()->getLocale();
    }

    /**
     * return lang key if exists in request or fall back to "en"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'en';
    }
}
