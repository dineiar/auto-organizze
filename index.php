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
        $file = implode('/', $names) . '.php';
        if (is_file($file)) {
            include $file;
        } else {
            throw new POCFW\Exception\ClassNotFoundException('Class ' . $name . ' not found for autoload');
        }
    }
});

$router = new POCFW\Controller\Router;
$router->invokeController();

// $superSecretKey = 'Senha123';

// use POCFW\Util\Security;
// use AutoOrganizze\Model\Dao\AccountDao;
// use AutoOrganizze\Model\Entity\Account;

// echo("List:\n");
// $query = AccountDao::getInstance()->list();
// var_dump($query);
// foreach($query as $row) {
//     var_dump($row);
// }

// echo("Get single:\n");
// $account = AccountDao::getInstance()->get(1);
// var_dump($account);

// $account->api_key = Security::encrypt($account->api_key, $superSecretKey);
// $num = AccountDao::getInstance()->update($account);
// var_dump($num);
// var_dump('Updated '.$num.' account');

// var_dump('Encrypted API key:', $account->api_key);

// var_dump('Decrypted API key:', Security::decrypt($account->api_key, $superSecretKey));

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
// var_dump('Removed '.$num.' account');
