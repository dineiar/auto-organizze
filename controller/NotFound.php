<?php
namespace AutoOrganizze\Controller;

use POCFW\Controller\Controller;

/**
 * @author Dinei
 */
class NotFound extends Controller {
    public function index() {
        return [
            'owner' => 'email@site.com'
        ];
    }
}