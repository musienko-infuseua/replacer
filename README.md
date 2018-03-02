The string replacer.


Install:
Add to composer.json custom repository:



How to use:

// Create replacement rules:
$rules = [
  new ReplaceRule('o', 'i'),
  new ReplaceRule('H', 'D'),
  new ReplaceRule('1', '2'),
];

// Create replacer instance
$replacer = new Replacer($rules);

// Add another rule
$replacer->attach(new ReplaceRule('e', 'u'));

// Get replaced text
$replaced_str = $replacer->replace('Hello-1'); // Dulli-2


Please, see tests for more detail usage.



