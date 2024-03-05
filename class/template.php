<?php

class Template {

    public
        $db,
        $user,
        $tpl;

    /**
     * @param Database $connect
     * @param User $user
     */
    public function __construct(Database $connect, User $user)
    {
        $this->db = $connect;
        $this->user = $user;

        $this->tpl['header'] = $this->loadTpl('header.html');

    }
    /**
     * @param $filename
     * @return string
     */
    public function loadTpl($filename): string
    {
        return
            file_get_contents('tpl/'.$filename);
    }
}
