<?php

namespace InSegment\Replacer;


class ReplaceRule
{
    /**
     * @var string
     */
    protected $pattern;

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
     * Regexp modificators
     *
     * @var string
     */
    protected $modificators = 'u';

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
        if (false === $is_case_sensitive) {
            $this->modificators .= 'i';
        }

        if (false === preg_match($this->delimiter.$pattern.$this->delimiter, '')) {
            throw new NotValidRegexpException();
        }

        $this->pattern = $pattern;
        $this->replacement = $replacement;
        $this->description = $description;
    }

    /**
     * Returns regexp pattern to use in PHP side (spec. characters slashes as is)
     *
     * @return string
     */
    public function getRegexp() : string
    {
        return $this->delimiter.$this->pattern.$this->delimiter.$this->modificators;
    }

    /**
     * Adds 2 extra slashed to spec. character to use in SQL queries with REGEXP* functions
     *
     * @return string
     */
    public function getSqlRegexp() : string
    {
        return preg_replace('/(\\\[A-Za-z]){1}/', '\\\\\\\\${1}', $this->pattern);
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

    /**
     * Returns description of rule
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }
}