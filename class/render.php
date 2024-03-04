<?php

class Render {

    private
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
        $this->tpl['themesList'] = $this->loadTpl('themes.list.html');
        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
    }

    /**
     * @return void
     */
    public function showThemesList(): void
    {
        $layout = str_replace('%themesList%',
            $this->getThemesList(), $this->tpl['themesList']);
        echo $layout;
    }

    /**
     * @return string
     */
    public function getThemesList(): string
    {
            $this->db->query('select * from theme');
            $data = $this->db->resultset();

            $htmlList = '<table>';

        foreach ($data as $k=>$theme) {
            $htmlList .= $this->drawThemesRow($k, $theme);
        }
        $htmlList .= '</table>';

        return $htmlList;
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
     * @param $idTheme
     * @param $data
     * @return string
     */
    public function drawThemesRow($idTheme, $data): string
    {
        $htmlRow = str_replace('%id%', $data->theme_id, $this->tpl['themesRow']);
        $htmlRow = str_replace('%name%', $data->theme_name, $htmlRow);

        return $htmlRow;
    }
}