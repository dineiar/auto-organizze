<?php
namespace POCFW\Model;

interface IDao {
    public function get($id);
    public function list();
    public function delete($id);
    public function insert($entity);
    public function update($entity);
}
