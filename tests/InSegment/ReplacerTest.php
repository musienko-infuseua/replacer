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

    public function testshouldAttachArrayOfRulesToExistedInstance()
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
}
