<?php

namespace InSegment;

use InSegment\Replacer\NotValidRegexpException;
use InSegment\Replacer\ReplaceRule;
use PHPUnit\Framework\TestCase;

class ReplaceRuleTest extends TestCase
{

    public function testShouldCreateRuleInCaseSensitiveModeIfPatternIsValidRegexp()
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

    public function testShouldCreateRuleInCaseInsensitiveMode()
    {
        $rule = new ReplaceRule('\w', '', false);

        $this->assertInstanceOf(ReplaceRule::class, $rule);
    }
}
