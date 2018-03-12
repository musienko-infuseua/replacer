### The string replacer.


#### Install:
Add to composer.json :
```
  "require": {
    "musienko-infuseua/replacer": "dev-master"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/musienko-infuseua/replacer"
    }
  ]
```

#### How to use:
```
// 1. Create replacement rules (case sensitive mode): 
$rules = [
    new ReplaceRule('o', 'i'),
    new ReplaceRule('H', 'D'),
    new ReplaceRule('1', '2'),
];

// 1a. Or create replacement rules (case insensitive mode): 
$rules = [
    new ReplaceRule('o', 'i', false),
    new ReplaceRule('H', 'D', false),
    new ReplaceRule('1', '2', false),
];

// 2. Create replacer instance
$replacer = new Replacer($rules);

// 2a. Add another rule (Optional)
$replacer->attach(new ReplaceRule('e', 'u'));

// 3. Get replaced text
$replaced_str = $replacer->replace('Hello-1'); // Dulli-2

// 3a. If provide array, get replaced text array
$replaced_strs = $replacer->replace(['Hello-1', 'Hello-1']); // ['Dulli-2', 'Dulli-2']

//3b. You get also get applied rules during the last execution
$applied_rules = $replacer->appliedRules(); // returns $rules, because all of them was appplied in this example  
```

Please, see tests for more detail usage.



