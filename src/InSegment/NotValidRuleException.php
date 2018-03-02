<?php

namespace InSegment;


class NotValidRuleException extends \Exception
{
    protected $message = 'Not valid rule provided. Except only ReplaceRule instances';
}