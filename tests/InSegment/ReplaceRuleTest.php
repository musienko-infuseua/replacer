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

    public function testShouldRetrieveRuleDescription()
    {
        $rule = new ReplaceRule('\w', '', false, $desc = 'my rule');

        $this->assertEquals($desc, $rule->getDescription());
    }

    public function testShouldRetrieveRegexp()
    {
        $rule = new ReplaceRule($pattern = '\w', '');

        $this->assertEquals('/'.$pattern.'/u', $rule->getRegexp());
    }

    public function testShouldRetrieveTriipleSlashedRegexp()
    {
        $rule1 = new ReplaceRule('\w', '');
        $rule2 = new ReplaceRule('\\\x{2a}', '');

        // expected = \\\w
        $this->assertEquals('\\\\\w', $rule1->getSqlRegexp());
        // expected = \\\\x{2a} , because MySQL needs for unicode char double slash
        $this->assertEquals('\\\\\\\x{2a}', $rule2->getSqlRegexp());
    }
}
