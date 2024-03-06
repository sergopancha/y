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
        $this->tpl['footer'] = $this->loadTpl('footer.html');

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

    /**
     * @param array $data
     * @return string
     */
    public function makeBreadcrumb(Array $data): string
    {
        $str = '';
        $arr = [];

        if (!empty($data)) {

            foreach ($data as $k=>$item) {
                if ( empty($item['url']) ) {
                    $arr[] = $item['title'];
                }
                else {
                    $arr[] = "<a href='{$item['url']}'>{$item['title']}</a>";
                }
            }
            $str = implode(' / ', $arr);
        }

        return $str;

    }
}
