<?php

class Template {

    public array $tpl;
    public User $user;
    public Database $db;

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
            $str = '<p>'.implode(' / ', $arr).'</p>';
        }

        return $str;
    }


    /**
     * Для вывода названия месяца на русском необходимо подключить к php модуль intl
     * или завести массив названий месяцев
     *
     * @param int $timestamp
     * @param string $pattern
     * @return string
     */
    public function renderMessageTime(int $timestamp, string $pattern = 'd-M-Y H:m'): string
    {
        return
            date($pattern, $timestamp);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getThemeName(int $id): string
    {
        $this->db->query('select theme_name from theme where theme_id=:theme_id');
        $this->db->bind(":theme_id", $id );
        $data = $this->db->single();

        return
            (empty ($data) || empty($data->theme_name) || $data->theme_name == '')?
                'без названия' : $data->theme_name;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkThemeId(int $id): bool
    {
        $this->db->query('select theme_id from theme where theme_id=:theme_id');
        $this->db->bind(":theme_id", $id );
        $data = $this->db->single();

        return
            !empty($data);
    }
}
