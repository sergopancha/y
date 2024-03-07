<?php

class Msgadd extends Template {

    private
        int $id_theme;

    public function __construct(Database $connect, User $user)
    {
        parent::__construct($connect, $user);
        $this->id_theme = (int) $_GET['idtheme'];

        if (!$this->checkThemeId($this->id_theme)) {
            die('theme id:'.$this->id_theme. ' not found');
        }

        $this->tpl['msgform'] = $this->loadTpl('message.form.html');
        $this->showMessageForm($this->id_theme);
    }
    /**
     * @return void
     */
    public function showMessageForm(int $idTheme): void
    {

        $themeName = $this->getThemeName($idTheme);
        $title = 'Новое сообщение в теме '.$themeName;

            $arrBreadcrumb = [
                [
                    'title' => 'Гостевая книга',
                    'url' => '/'
                ],
                ['title' => 'Темы'],
                ['title' => "<a href='/?mode=theme&id={$idTheme}'>{$themeName}</a>"],
                ['title' => $title]
            ];

            $layout = str_replace('%header%', $this->tpl['header'], $this->tpl['msgform']);
            $layout = str_replace('%footer%', $this->tpl['footer'], $layout);
            $layout = str_replace('%headerTitle%', $title, $layout);
            $layout = str_replace('%breadcrumb%', $this->makeBreadcrumb($arrBreadcrumb), $layout);
            $layout = str_replace('%idtheme%', $idTheme, $layout);
            $layout = str_replace('%iduser%', $this->user->getUserId(), $layout);
            $layout = str_replace('%token%', md5(mt_rand(11111,99999)), $layout);
            $layout = str_replace('%action%', '?mode=msgsave', $layout);

            echo $layout;
    }

    public function checkMessageId(int $id): bool
    {
        $this->db->query('select message_id from messages where message_id=:message_id');
        $this->db->bind(":message_id", $id );
        $data = $this->db->single();

        return
            !empty($data);
    }

    /**
     * @return string
     */
    public function getMessage(int $idMessage): mixed
    {
        $this->db->query('
            select m.message_text as m_text, 
            m.message_time as m_time,
            u.user_id, t.theme_name, t.theme_id
            from messages m 
            left join users u on u.user_id=m.user_id
            left join theme t on t.theme_id=m.theme_id
            where message_id=:message_id');

        $this->db->bind(":message_id", $idMessage );
        $data = $this->db->single();

        return $data;
    }

    public function drawMessage(object $data): mixed
    {
        if ( !empty($data)) {

            $html ='';

            $html = str_replace('%idUser%', $data->user_id, $this->tpl['message']);
            $html = str_replace('%user%', $data->user_id, $html);
            $html = str_replace('%messageTime%', $this->renderMessageTime($data->m_time), $html);
            $html = str_replace('%messageText%', $data->m_text, $html);
        }
        else {
            $html = 'Сообщений на эту тему ещё нет';
        }

        return $html;
    }
}