<?php

namespace MixCode\Utils\Exceptions;

use Exception;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class MultiLangFieldNotRegisteredException extends Exception implements ProvidesSolution
{
    protected $field;
    protected $message;

    public function __construct($field)
    {
        $this->field = $field;
        $this->message = "Field \"({$this->field})\" not registered";

        parent::__construct($this->message);
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create($this->message)
            ->setSolutionDescription("Field **({$this->field})** not registered in **`multiLangFields()`** you must register it first.")
            ->setDocumentationLinks([
                'MixCode Docs' => 'https://guide.mix-code.com',
            ]);
    }
}