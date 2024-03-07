<?php

class User {
    private int $id;
    private string $hash;
    private Database $db;

    /**
     * @param Database $connect
     */
    public function __construct(Database $connect)
    {
        $this->db = $connect;
        if ( isset($_COOKIE['user']) && ($_COOKIE['user'] != '')
            && isset($_COOKIE['hash']) && ($_COOKIE['hash'] != '')) {

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

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserHash(): string
    {
        return $this->hash;
    }
}