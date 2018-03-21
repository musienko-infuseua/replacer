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

        $this->assertEquals('/' . $pattern . '/', $rule->getRegexp());
    }

    public function testShouldRetrieveSqlSlashedRegexp()
    {
        $rule1 = new ReplaceRule('\w', '', false);
        $rule2 = new ReplaceRule('\\\x{2a}', '', false);
        $rule3 = new ReplaceRule('\w\.', '', false);

        // expected = \\\w
        $this->assertEquals('\\\\\w', $rule1->getSqlRegexp());
        // expected = \\\\x{2a} , because MySQL needs for unicode char double slash
        $this->assertEquals('\\\\\\\x{2a}', $rule2->getSqlRegexp());
        $this->assertEquals('\\\\\w\\\\\.', $rule3->getSqlRegexp());
    }

    public function testShouldGetRegexpInCaseSensivityMode()
    {
        $rule1 = new ReplaceRule('\w', '', true);
        $rule2 = new ReplaceRule('\d', '', false);

        $this->assertEquals('/\w/', $rule1->getRegexp());
        $this->assertEquals('(?-i)\\\\\w', $rule1->getSqlRegexp());

        $this->assertEquals('/\d/i', $rule2->getRegexp());
        $this->assertEquals('\\\\\d', $rule2->getSqlRegexp());
    }
}
