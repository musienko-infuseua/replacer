<?php

namespace InSegment\Replacer;

class ReplacerFacade
{
    /**
     * Replace string(s) with base rules
     *
     * @param $strings
     *
     * @return array|string
     */
    public static function replaceWithBaseRules($strings)
    {
        if (!is_array($strings)) {
            $toReplace[] = $strings;
        } else {
            $toReplace = $strings;
        }

        $rules = self::retrieveBaseRules();

        foreach ($toReplace as $str) {
            $replaced[] = (new Replacer($rules))->replace($str);
        }

        return !is_array($strings) ? array_pop($replaced) : $replaced;
    }

    /**
     * Get Replacer instance with already attached Base rules.
     *
     * Then you can attach custom rule(s), and perform replacement.
     *
     * @return Replacer
     */
    public static function getWithBaseRules() : Replacer
    {
        $db_rules = self::retrieveBaseRules();
        return new Replacer($db_rules);
    }

    /**
     * @return ReplaceRule[]
     */
    protected static function retrieveBaseRules() : array
    {
        $base_rules = [];

        // @todo: retrieve from DB in Laravel
        $db_rules = [
            ['pattern' => 'e', 'replacement' => 'i', 'description' => 'Some desc1'],
            ['pattern' => '1', 'replacement' => '2', 'description' => 'Some desc2'],
        ];

        foreach ($db_rules as $db_rule) {
            $base_rules[] = new ReplaceRule($db_rule['pattern'], $db_rule['replacement'], $db_rule['description']);
        }

        return $base_rules;
    }
}