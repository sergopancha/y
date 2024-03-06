<?php

class Message extends Template {

    private
        int $id_theme, $id_message;
    public function __construct(Database $connect, User $user)
    {
        parent::__construct($connect, $user);
        $this->id_message = (int) $_GET['id'];

        if (!$this->checkMessageId($this->id_message) || $this->id_message<1) {
            die('message id:'.$this->id_message. ' not found');
        }

        $this->tpl['message'] = $this->loadTpl('message.html');
        $this->showMessage($this->id_message);
    }
    /**
     * @return void
     */
    public function showMessage(int $idMessage): void
    {
       // $themeName = $this->getThemeName($idTheme);

        $message = $this->getMessage($idMessage);


        if ( !empty($message)) {

            $messageTitle =
                'Сообщение '.$idMessage.' '.$message->user_id.' '.$this->renderMessageTime($message->m_time);

            $arrBreadcrumb = [
                [
                    'title' => 'Гостевая книга',
                    'url' => '/'
                ],
                ['title' => 'Темы'],
                ['title' => "<a href='/?mode=theme&id={$message->theme_id}'>{$message->theme_name}</a>"],
                ['title' => $idMessage.' '.$message->user_id.' '.$this->renderMessageTime($message->m_time)]
            ];

            $layout = str_replace('%header%', $this->tpl['header'], $this->tpl['message']);
            $layout = str_replace('%footer%', $this->tpl['footer'], $layout);
            $layout = str_replace('%headerTitle%', $messageTitle, $layout);
            $layout = str_replace('%themeName%', $message->theme_name, $layout);
            $layout = str_replace('%breadcrumb%', $this->makeBreadcrumb($arrBreadcrumb), $layout);
            $layout = str_replace('%idUser%', $message->user_id, $layout);
            $layout = str_replace('%user%', $message->user_id, $layout);
            $layout = str_replace('%messageTime%', $this->renderMessageTime($message->m_time), $layout);
            $layout = str_replace('%messageText%', $message->m_text, $layout);




            echo $layout;

        }
        else {
            $html = 'Сообщений на эту тему ещё нет';
        }}

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