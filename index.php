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

$superSecretKey = 'Senha123';

use POCFW\Util\Security;
use AutoOrganizze\Model\Dao\AccountDao;
use AutoOrganizze\Model\Entity\Account;

// echo("List:\n");
// $query = AccountDao::getInstance()->list();
// var_dump($query);
// foreach($query as $row) {
//     var_dump($row);
// }

// echo("Get single:\n");
// $account = AccountDao::getInstance()->get(1);
// var_dump($account);
// var_dump('Check account password:', Security::checkPassword($account->password, $superSecretKey));

// $hashedPw = Security::hashPassword($superSecretKey);
// var_dump($hashedPw, Security::checkPassword($hashedPw, $superSecretKey));

// $account->password = $hashedPw;
// var_dump(AccountDao::getInstance()->update($account));

// echo("Insert:\n");
// $account = new Account;
// $account->email = 'Teste de email';
// $account->api_key = 'Chave da API';
// $accountId = AccountDao::getInstance()->insert($account);
// var_dump('Inserted account of ID: ' . $account->id . ' == ' . $accountId);

// echo("Delete:\n");
// $num = AccountDao::getInstance()->delete($accountId);
// var_dump('Removed '.$num.' accounts');
