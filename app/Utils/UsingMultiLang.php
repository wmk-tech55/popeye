<?php

namespace MixCode\Utils;

use MixCode\MultiLanguageManager;
use MixCode\Utils\Exceptions\MultiLangFieldNotRegisteredException;

trait UsingMultiLang
{
    public static function getFieldsMultiLangOptions(string $fieldName = null): array
    {
        $options = [];
        $multiLangFields = static::multiLangFields();

        foreach ($multiLangFields as $field) {
            $options[$field] = static::multiLangFieldsDefaultOptions();
        }

        $options = array_replace_recursive($options, static::multiLangFieldsCustomOptions());

        if (is_string($fieldName)) {

            if (isset($options[$fieldName])) return $options[$fieldName];

            throw new MultiLangFieldNotRegisteredException($fieldName);
        }

        return $options;
    }

    protected static function multiLangFieldsDefaultOptions(): array
    {
        $systemLanguages = MultiLanguageManager::languagesCodes();

        $defaultOptions = [
            'rules_for_request' => [],
            'html_attributes'   => [],
            'trans'             => null,
            'required'          => true,
        ];

        $multiLangDefaultOptions = [];

        foreach ($systemLanguages as $systemLanguage) {
            $multiLangDefaultOptions[$systemLanguage] = $defaultOptions;
        }

        return $multiLangDefaultOptions;
    }

    protected static function multiLangFieldsCustomOptions(): array
    {
        return [];
    }

    /**
     * Return lang key based on if request wants json response for api
     *
     * @return string
     */
    protected function getLang()
    {
        $request = request();
        return ($request->wantsJson() && !$request->ajax()) ? $this->hasLang() : app()->getLocale();
    }

    /**
     * return lang key if exists in request or fall back to "en"
     *
     * @return string
     */
    protected function hasLang()
    {
        $request = request();
        return ($request->has('lang') && $request->filled('lang')) ? $request->lang : 'ar';
    }
}
