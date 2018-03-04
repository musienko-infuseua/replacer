<?php

namespace InSegment;

use PHPUnit\Framework\TestCase;

class ReplaceRuleTest extends TestCase
{

    public function testShouldCreateRuleIfPatternIsValidRegexp()
    {
        $pattern = '^ Some . Valid Regexp Without Quotes $';

        try {
            $rule = new ReplaceRule($pattern, '');
        } catch (NotValidRegexpException $e) {}

        $this->assertNotFalse(preg_match($rule->getRegexp(), ''));
    }

    public function testShouldThrownNotValidRegexpExceptionIfPatternIsNotValidRegexp()
    {
        $pattern = 'Sorry, but it\'s not valid attern ((';

        $this->expectException(NotValidRegexpException::class);
        $this->expectExceptionMessage('The string you provided as regexp_pattern is not a valid regular expression');

        new ReplaceRule($pattern, '');
    }
}
