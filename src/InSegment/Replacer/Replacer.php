<?php

namespace InSegment\Replacer;


class Replacer
{
    /**
     * Replace rules
     *
     * @var ReplaceRule[]
     */
    protected $rules = [];

    /**
     * Replacer constructor.
     *
     * @param ReplaceRule[]
     */
    public function __construct(array $rules)
    {
        $this->attachRules($rules);
    }

    /**
     * Cascade replaces string characters according to rules
     *
     * @param string|array $str
     *
     * @return string|array
     */
    public function replace($str)
    {
        $replaced = $str;
        foreach ($this->rules as $rule) {
            $replaced = preg_replace($rule->getRegexp(), $rule->getReplacement(), $replaced);
        }

        return $replaced;
    }

    /**
     * Attach rule to existed ones
     *
     * @param array|ReplaceRule $rules
     * @param bool $is_append
     */
    public function attachRules($rules, bool $is_append = true) : Replacer
    {
        if (is_array($rules)) {
            $rules_to_attach = $rules;
        } else {
            $rules_to_attach[] = $rules;
        }

        $this->validateAndAttachRules($rules_to_attach, $is_append);

        return $this;
    }

    /**
     * Validate rules to ReplaceRule class, and attached it to existing ones
     *
     * @param array|ReplaceRule $rules
     * @param bool $is_append
     */
    protected function validateAndAttachRules(array $rules, $is_append = true)
    {
        array_walk($rules, function($rule) {
            if (false === $rule instanceof ReplaceRule) {
                throw new NotValidRuleException();
            }
        });

        if (true === $is_append) {
            $this->rules = array_merge($this->rules, $rules);
        } else {
            $this->rules = array_merge($rules, $this->rules);
        }
    }

}