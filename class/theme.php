<?php

class Theme extends Template {

    private $id_theme;
    public function __construct(Database $connect, User $user)
    {
        parent::__construct($connect, $user);
        $this->id_theme = (int) $_GET['id'];

        if (!$this->checkThemeId($this->id_theme)) {
            die('theme id:'.$this->id_theme. ' not found');
        }

       // $messagesList = $this->getMessagesList($this->id_theme);

        $this->tpl['messagesList'] = $this->loadTpl('messages.list.html');
        $this->tpl['messagesRow'] = $this->loadTpl('messages.row.html');
//        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
        $this->showMessagesList($this->id_theme);
    }
    /**
     * @return void
     */
    public function showMessagesList(Int $idTheme): void
    {
        $themeName = $this->getThemeName($idTheme);

        $arrBreadcrumb = [
          [
              'title' => 'Гостевая книга',
              'url' => '/'
          ],
          ['title' => 'Темы',],
          ['title' => $themeName]
        ];
        $layout = str_replace('%header%', $this->tpl['header'], $this->tpl['messagesList']);
        $layout = str_replace('%footer%', $this->tpl['footer'], $layout);
        $layout = str_replace('%headerTitle%', $themeName, $layout);
        $layout = str_replace('%themeName%', $themeName, $layout);
        $layout = str_replace('%messagesList%', $this->getMessagesList($this->id_theme), $layout);
        $layout = str_replace('%breadcrumb%', $this->makeBreadcrumb($arrBreadcrumb), $layout);

        echo $layout;
    }

    public function checkThemeId(Int $id): bool
    {
        $this->db->query('select theme_id from theme where theme_id=:theme_id');
        $this->db->bind(":theme_id", $id );
        $data = $this->db->single();

        return
            !empty($data);
    }

    public function getThemeName(Int $id): string
    {
        $this->db->query('select theme_name from theme where theme_id=:theme_id');
        $this->db->bind(":theme_id", $id );
        $data = $this->db->single();

        return
            (empty ($data) || empty($data->theme_name) || $data->theme_name == '')?
                'без названия' : $data->theme_name;
    }

    /**
     * @return string
     */
    public function getMessagesList(Int $idTheme): string
    {
        $this->db->query('
            select m.message_id as m_id, m.message_text as m_text, 
            m.message_time as m_time,
            u.user_hash
            from messages m 
            left join users u on u.user_id=m.user_id
            where theme_id=:theme_id');

        $this->db->bind(":theme_id", $idTheme );
        $data = $this->db->resultset();

        if ( !empty($data)) {

            $htmlList ='';
            $htmlList .= '<table>';

            foreach ($data as $k => $message) {
                $htmlList .= $this->drawMessagesRow($k, $message);
            }
            $htmlList .= '</table>';
        }
        else {
            $htmlList = 'Сообщений на эту тему ещё нет';
        }

        return $htmlList;
    }


    /**
     * @param $idTheme
     * @param $data
     * @return string
     */
    public function drawMessagesRow($idTheme, $data): string
    {
        $htmlRow = str_replace('%id%', $data->m_id, $this->tpl['messagesRow']);
        $htmlRow = str_replace('%user%', $data->user_hash, $htmlRow);
        $htmlRow = str_replace('%date%', date("d:m:Y H:m", $data->m_time), $htmlRow);
        $htmlRow = str_replace('%name%', mb_substr($data->m_text, 0, 20).'...', $htmlRow);

        return $htmlRow;
    }
}