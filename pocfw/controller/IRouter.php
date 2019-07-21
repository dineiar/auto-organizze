<?php
namespace POCFW\Controller;

interface IRouter {
    public function invokeController();
    public function invokeNotFoundController();
}