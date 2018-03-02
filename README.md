### The string replacer.


####Install:
Add to composer.json custom repository:



####How to use:
```
// 1. Create replacement rules: 
$rules = [
    new ReplaceRule('o', 'i'),
    new ReplaceRule('H', 'D'),
    new ReplaceRule('1', '2'),
];

// 2. Create replacer instance
$replacer = new Replacer($rules);

// 2a. Add another rule (Optional)
$replacer->attach(new ReplaceRule('e', 'u'));

// 3. Get replaced text
$replaced_str = $replacer->replace('Hello-1'); // Dulli-2
```

Please, see tests for more detail usage.



