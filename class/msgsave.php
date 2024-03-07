<?php

class Msgsave extends Template {

    private int $id_theme;

    public function __construct(Database $connect, User $user)
    {
        parent::__construct($connect, $user);
        $this->id_theme = (int) $_POST['idtheme'];

        if (!$this->checkThemeId($this->id_theme)) {
            die('theme id:'.$this->id_theme. ' not found');
        }

        $this->tpl['messageSave'] = $this->loadTpl('message.save.html');
        $this->showMessageSave();
    }
    /**
     * @return void
     */
    public function showMessageSave(): void
    {

        $title = 'Запись нового сообщения';

        $arrBreadcrumb = [
            [
                'title' => 'Гостевая книга',
                'url' => '/'
            ],
            ['title' => 'Темы'],
            ['title' => $title]
        ];

        $layout = str_replace('%header%', $this->tpl['header'], $this->tpl['messageSave']);
        $layout = str_replace('%footer%', $this->tpl['footer'], $layout);
        $layout = str_replace('%headerTitle%', $title, $layout);
        $layout = str_replace('%breadcrumb%', $this->makeBreadcrumb($arrBreadcrumb), $layout);

        echo $layout;
    }

    /**
     * @return string
     */
    public function getMessagesList(int $idTheme): string
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
            $htmlList .= '<table border="1">';

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
        $htmlRow = str_replace('%date%', $this->renderMessageTime($data->m_time), $htmlRow);
        $htmlRow = str_replace('%name%', mb_substr($data->m_text, 0, 20).'...', $htmlRow);

        return $htmlRow;
    }
}