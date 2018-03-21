<?php

namespace InSegment\Replacer;

use ForceUTF8\Encoding;

class Replacer
{
    /**
     * Replace rules
     *
     * @var ReplaceRule[]
     */
    protected $rules = [];

    /**
     * Rules were applied
     *
     * @var array
     */
    protected $applied_rules = [];

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
     * We can't use an array as parameter, because we need to check if Rule did apply.
     *
     * @param string|array $str
     *
     * @return string|array If null was provided as $str - empty string will return.
     */
    public function replace($str)
    {
        // Clear applied rules because method might be executed before
        $this->applied_rules = [];

        if (!is_array($str)) {
            $subjects[] = $str ?? '';
        } else {
            $subjects = $str;
        }

        $replaced_batch = [];
        foreach ($subjects as $subject) {
            $subject = Encoding::toUTF8($subject);
            foreach ($this->rules as $rule) {
                $replaced = preg_replace($rule->getRegexp(), $rule->getReplacement(), $subject);
                if ($replaced !== $subject && null !== $replaced) {
                    $this->applied_rules[] = $rule;
                }
                $subject = $replaced ?? $subject;// next iteration apply replaced as subject for cascade reason
            }
            $replaced_batch[] = $replaced;
        }

        if (0 === count($replaced_batch)) {
            $result = '';
        } elseif (1 === count($replaced_batch)) {
            $result = array_pop($replaced_batch);
        } else {
            $result =  $replaced_batch;
        }

        return $result;
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
     * Rules were applied
     *
     * @return ReplaceRule[]
     */
    public function appliedRules() : array
    {
        return $this->applied_rules;
    }

    /**
     * Retrieve attached rules
     *
     * @return ReplaceRule[]
     */
    public function getAttachedRules()
    {
        return $this->rules;
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