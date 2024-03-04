<?php

class User {
    private
        $id,
        $hash,
        $db;

    public function __construct(Database $connect)
    {
        $this->db = $connect;
        if (isset($_COOKIE['user']) && ($_COOKIE['user'] != '')) {
            $this->id = $_COOKIE['user'];
            $this->hash = $_COOKIE['hash'];
        }
        else {
            $this->hash = md5( time());
            $this->db->query('insert into users(`user_hash`) values (:hash)');
            $this->db->bind(":hash", $this->hash );
            $this->db->execute();
            $this->id = $this->db->lastInsertId();
            setcookie('user', $this->id, 0);
            setcookie('hash', $this->hash, 0);
        }
    }

    public function getUserId()
    {
        return $this->id;
    }

    public function getUserHash()
    {
        return $this->hash;
    }
}