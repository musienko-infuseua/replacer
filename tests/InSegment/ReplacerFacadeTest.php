<?php
/**
 * Created by PhpStorm.
 * User: e
 * Date: 3/2/18
 * Time: 3:01 PM
 */

namespace InSegment;

use InSegment\Replacer\Replacer;
use InSegment\Replacer\ReplacerFacade;
use PHPUnit\Framework\TestCase;

class ReplacerFacadeTest extends TestCase
{

    public function testShouldReplaceWithBaseRules()
    {
        $str = 'Hello1';

        $replaced = ReplacerFacade::replaceWithBaseRules($str);

        $this->assertEquals('Hillo2', $replaced);
    }

    public function testshouldInstansiateReplacerWithBaseRules()
    {
        $replacer = ReplacerFacade::getWithBaseRules();

        $this->assertInstanceOf(Replacer::class, $replacer);
    }
}
