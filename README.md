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

// 3b. If provide ISO-8859-1 OR CP1252 encoding, the broken chars will recover
$str_iso = mb_convert_encoding('L’Oréal', 'ISO-8859-1); // L?Or�al
$replacer->attach(new ReplaceRule('é', 'e'));
$replaced_str = $replacer->replace($str_iso); // L?oreal , because � will recover to é

//4. You get also get applied rules during the last execution
$applied_rules = $replacer->appliedRules(); // returns $rules, because all of them was appplied in this example  
```

Also you can get properly escaped regexp for MySQL to use in REGEXP* functions.
```
$rule1 = new ReplaceRule('\w', '');
$rule2 = new ReplaceRule('\\\x{3d}', '');

$sql_regexp1 = $rule1->getSqlRegexp(); // return '\\\w'
$sql_regexp2 = $rule2->getSqlRegexp(); // return '\\\\x{3d}'

Then you can use it in SQL queries:
$sql1 = "SELECT 'Hello World' REGEXP ('" . $sql_regexp1 . "') "; // return 1
$sql2 = "SELECT 'Hello=World' REGEXP ('" . $sql_regexp2 . "') "; // return 1

```
Please, see tests for more detail usage.



