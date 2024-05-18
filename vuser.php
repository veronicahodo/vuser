<?php

require_once('vobj.php');

class Vuser extends Vobj
{
    private $infoTable;
    function __construct(Vcrud $crud)
    {
        parent::__construct($crud);
        $this->table = 'users';
        $this->index = 'userId';
        $this->infoTable = 'userInfo';
    }

    function checkPassword($password)
    {
        if (hash('sha512', $password . $this->fields['salt']) == $this->fields['passwordHash']) {
            return true;
        }

        return false;
    }

    function updatePassword($password)
    {
        $newSalt = hash('sha512', random_bytes(64));
        $this->fields['passwordHash'] = hash('sha512', $password . $newSalt);
        $this->save();
    }

    function getUserInfo()
    {
        $info = $this->crud->read($this->infoTable, [['userId', '=', $this->fields['userId']]]);
        if (count($info) != 1) {
            return false;
        }

        return $info[0];
    }
}
