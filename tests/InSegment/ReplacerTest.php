<?php
namespace InSegment;

use InSegment\Replacer\NotValidRegexpException;
use InSegment\Replacer\NotValidRuleException;
use InSegment\Replacer\Replacer;
use InSegment\Replacer\ReplaceRule;
use PHPUnit\Framework\TestCase;

class ReplacerTest extends TestCase
{
    public function testShouldReplaceStringBasedOnRulesInCaseSensitiveMode()
    {
        $str = 'Hello1';
        try {
            $rules = [
                new ReplaceRule('o', 'i'),
                new ReplaceRule('H', 'D'),
                new ReplaceRule('1', '2'),
            ];

            $replaced = (new Replacer($rules))->replace($str);
        } catch (NotValidRegexpException $e) {}


        $this->assertEquals('Delli2', $replaced);
    }

    public function testShouldReplaceStringBasedOnRulesInCaseInsensitiveMode()
    {
        $str = 'Hello1';
        try {
            $rules = [
                new ReplaceRule('h', 'D', false),
                new ReplaceRule('O', 'i', false),
                new ReplaceRule('1', '2', false),
            ];

            $replaced = (new Replacer($rules))->replace($str);
        } catch (NotValidRegexpException $e) {}

        $this->assertEquals('Delli2', $replaced);
    }

    public function testSouldConvertISO8859AndCP1252CharsToUtf8BeforeReplace()
    {
        $str = 'L’Oréal';

        $strs = [];
        foreach (['ISO-8859-1', 'CP1252', 'UTF-8'] as $encoding) {
            $strs[] = mb_convert_encoding($str, $encoding);
        }

        try {
            $rules = [
                new ReplaceRule('é', 'e', false),
            ];

            $replaced = (new Replacer($rules))->replace($strs);
        } catch (NotValidRegexpException $e) {
        }

        $this->assertNotEmpty($replaced);
        foreach ($replaced as $item) {
            $this->assertTrue(in_array($item, $expected = ['L?Oreal', 'L’Oreal'], true),
                "$item is not in " . implode(',', $expected));
        }
    }


    public function testShouldReplaceUtf8String()
    {
        $str = 'Àfggh';
        try {
            $rules = [
                new ReplaceRule('À', 'A', false),
            ];

            $replaced = (new Replacer($rules))->replace($str);
        } catch (NotValidRegexpException $e) {}

        $this->assertEquals('Afggh', $replaced);
    }

    public function testShouldReplaceArrayOfStringsBasedOnRules()
    {
        $strs = ['Hello1', 'Hello1', 'Hello1'];

        try {
            $rules = [
                new ReplaceRule('o', 'i'),
                new ReplaceRule('H', 'D'),
                new ReplaceRule('1', '2'),
            ];

            $replaced = (new Replacer($rules))->replace($strs);
        } catch (NotValidRegexpException $e) {}


        $this->assertInternalType('array', $replaced);
        $this->assertCount(count($strs), $replaced);
        foreach ($replaced as $item) {
            $this->assertEquals('Delli2', $item);
        }
    }

    public function testShouldReturnAppliedRulesNamesForEachReplace()
    {
        $str = 'Hello1';
        try {
            $rules = [
                new ReplaceRule('o', 'i'),
                new ReplaceRule('H', 'D'),
                new ReplaceRule('1', '2'),
            ];

            $replacer = new Replacer($rules);
            $replaced = $replacer->replace($str);
            $applied_rules = $replacer->appliedRules();
            $replaced = $replacer->replace($str);
            $applied_rules2 = $replacer->appliedRules();

        } catch (NotValidRegexpException $e) {}

        $this->assertEquals('Delli2', $replaced);
        $this->assertInternalType('array', $applied_rules);
        $this->assertEquals($rules, $applied_rules);
        $this->assertEquals($rules, $applied_rules2);
    }

    public function testShouldThrownNotValidRuleExceptionIfNotValidRuleProvided()
    {
        $rules = ['Some string instead of ReplaceRule class'];

        $this->expectException(NotValidRuleException::class);
        $this->expectExceptionMessage('Not valid rule provided. Except only ReplaceRule instances');

        (new Replacer($rules))->replace('');
    }

    public function testShouldAttachSingleRuleToExistedInstance()
    {
        $str = 'Hello1';
        try {
            $rules = [
                new ReplaceRule('o', 'i'),
                new ReplaceRule('H', 'D'),
            ];

            $replacer = new Replacer($rules);
            $replacer->attachRules(new ReplaceRule('1', '2'));

            $replaced = $replacer->replace($str);
        } catch (NotValidRegexpException $e) {}


        $this->assertEquals('Delli2', $replaced);
    }

    public function testShouldAttachArrayOfRulesToExistedInstance()
    {
        $str = 'Hello1';
        try {
            $rules = [
                new ReplaceRule('o', 'i'),
            ];

            $replacer = new Replacer($rules);
            $replacer->attachRules(
                [
                    new ReplaceRule('1', '2'),
                    new ReplaceRule('H', 'D'),
                ]
            );

            $replaced = $replacer->replace($str);
        } catch (NotValidRegexpException $e) {}


        $this->assertEquals('Delli2', $replaced);
    }

    public function testShouldReturnAttachedRules()
    {
        try {
            $rules = [
                new ReplaceRule('h', 'D', false),
                new ReplaceRule('O', 'i', false),
                new ReplaceRule('1', '2', false),
            ];

            $replacer = new Replacer($rules);

        } catch (NotValidRegexpException $e) {}

        $this->assertArraySubset($replacer->getAttachedRules(), $rules);
    }
}
