<?php
namespace AutoOrganizze\Model\Dao;

use \POCFW\Model\BaseDao;
use AutoOrganizze\Model\Entity\Account;

/**
 * @author Dinei
 */
class AccountDao extends BaseDao {
    function getEntity() {
        return new Account;
    }
}
