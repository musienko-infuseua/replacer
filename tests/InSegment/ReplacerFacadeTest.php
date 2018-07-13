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
    const HOST = 'maria10.2-common';
    const DB_NAME = 'data_storage';
    const RULES_TABLE = 'replace_rules';

    public function testShouldInstansiateReplacerWithBaseRules()
    {
        $dsn = 'mysql:host=' . self::HOST . ';port=33060;dbname=' . self::DB_NAME;
        $username = 'root';
        $password = 'root_pwd';
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        $connect = new \PDO($dsn, $username, $password, $options);
        $db_rules_count = $connect->query("SELECT count(*) FROM " . self::RULES_TABLE)->rowCount();

        $replacer = ReplacerFacade::getWithBaseRules();

        $this->assertInstanceOf(Replacer::class, $replacer);
        $this->assertInternalType('array', $replacer->getAttachedRules());
        $this->assertCount($db_rules_count, $replacer->getAttachedRules());
    }

    public function testShouldReplaceWithBaseRules()
    {
        $str = 'Hello1';

        $replaced = ReplacerFacade::replaceWithBaseRules($str);

        $this->assertEquals('Hillo2', $replaced);
    }
}
