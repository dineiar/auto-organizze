<?php
namespace POCFW\Model;

interface IDao {
    public function get($id);
    public function list();
    public function delete($id);
}
