<?php

namespace InSegment\Replacer;

/**
 * Class NotValidRegexpException
 *
 * @package InSegment
 */
class NotValidRegexpException extends \Exception
{
    protected $message = 'The string you provided as regexp_pattern is not a valid regular expression';
}