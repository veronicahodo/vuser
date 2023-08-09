<?php

// vuser.php

// User management class

require_once('vcrud.php');

class VUSER {
  private $userId;
  private $username;
  private $hash;
  private $salt;


  function __construct() {

  }


  function create($username,$password, VCRUD $c) {
    $this->salt = random_bytes(32);
    $c->create('users',[
               'username'=>$username,
               'hash'=>hash('sha512',$password.$this->salt),
               'salt' => $this->salt
               ]);
  }

  function load($userId, VCRUD $c) {
    $data = $c->read('users',[['userId','=',$userId]]);
    if ($data) {
      $this->userId = $data[0]['userId'];
      $this->username = $data[0]['username'];
      $this->hash = $data[0]['hash'];
      $this->salt = $data[0]['salt'];
      return $data[0];
    } else {
      return false;
    }
  }

  function save() {

  }


  function getUserData($userId) {

    
  }

  function validatePassword($username,$password, VCRUD $c) {
    $user = $c->read('users',[['username','=',$username]]);
    if ($user) {
      if (hash('sha512',$password.$user[0]['salt'])) {
        return $user[0]['userId'];
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}




