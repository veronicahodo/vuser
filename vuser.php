<?php

// vuser.php

// User management class
// Version 0.3.0s

require_once('vcrud.php');

class VUSER
{
    private $fields;    // fields for the data
    /*private $userId;
    private $username;
    private $passwordHash;      // sha512 hash of plain text password + salt
    private $salt;*/


    function __construct()
    {
        $this->fields['userId'] = 0;
        $this->fields['username'] = '';
        $this->fields['passwordHash'] = '';
        $this->fields['salt'] = '';
    }


    function create($username, $password, VCRUD $c)
    {
        $this->fields['salt'] = random_bytes(32);
        $c->create('users', $this->fields);
    }


    function read($userId, VCRUD $c)
    {
        $this->fields = $c->read('users', [['userId', '=', $userId]]);
    }


    function update(VCRUD $c)
    {
        $c->update('users', $this->fields, [['userId', '=', $this->fields['userId']]]);
    }

    function delete(VCRUD $c)
    {
        $c->delete('users', [['userId', '=', $this->fields['userId']]]);
    }



    function getUserData(VCRUD $c)
    {
        $data = $c->read('userdata', [['userId', '=', $this->fields['userId']]]);
        // lol so not failsafe [TODO]
        return $data[0];
    }


    function validatePassword($username, $password, VCRUD $c)
    {
        // I'm conflicted on leaving this to where it doesn't
        // modify our internal user. I don't know why we would ever NOT
        // want current user data in this situation but ... why waste cycles?
        // I'm obviously open to suggestions
        $user = $c->read('users', [['username', '=', $username]]);
        if ($user) {
            if ((hash('sha512', $password . $user[0]['salt'])) === $user[0]['passwordHash']) {
                return $user[0]['userId'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
