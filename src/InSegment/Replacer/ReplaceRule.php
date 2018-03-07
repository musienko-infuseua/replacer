<?php

namespace InSegment\Replacer;


class ReplaceRule
{
    /**
     * @var string
     */
    protected $regexp;

    /**
     * @var string
     */
    protected $replacement;

    /**
     * @var string
     */
    protected $description;

    /**
     * Delimiter
     *
     * @var string
     */
    protected $delimiter = '/';

    /**
     * ReplaceRule constructor.
     *
     * @param string $pattern
     * @param string $replacement
     * @param bool $is_case_sensitive
     * @param string $description
     *
     * @throws NotValidRegexpException
     */
    public function __construct(string $pattern, string $replacement, bool $is_case_sensitive = true, string $description = '')
    {
        $regexp = $this->delimiter.$pattern.$this->delimiter;

        if (false === preg_match($regexp, '')) {
            throw new NotValidRegexpException();
        }

        if (false === $is_case_sensitive) {
            $regexp .= 'i';
        }

        $this->regexp = $regexp;
        $this->replacement = $replacement;
        $this->description = $description;
    }

    /**
     * Returns regexp pattern
     *
     * @return string
     */
    public function getRegexp() : string
    {
        return $this->regexp;
    }

    /**
     * Returns substitution
     *
     * @return string
     */
    public function getReplacement() : string
    {
        return $this->replacement;
    }
}