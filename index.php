<?php

define('CONFIG', include 'config.php');

spl_autoload_register(function ($name) {
    $names = explode('\\', $name);
    if ($names) {
        if ($names[0] == CONFIG['projectNamespace']) {
            array_shift($names);
        }
        for($i = 0; $i < count($names)-1; $i++) {
            $names[$i] = strtolower($names[$i]);
        }
        include implode('/', $names) . '.php';
    }
});
echo('<pre>');

use AutoOrganizze\Model\Dao\AccountDao;

echo("List:\n");
$query = AccountDao::getInstance()->list();
var_dump($query);
foreach($query as $row) {
    print_r($row);
}
echo("Single:\n");
$account = AccountDao::getInstance()->get(1);
print_r($account);

echo('</pre>');
