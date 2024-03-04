<?php

class Render {

    private
        $db,
        $tpl;


    /**
     * @param $connect
     */
    public function __construct($connect)
    {
        $this->db = $connect;
        $this->tpl['themesList'] = $this->loadTpl('themes.list.html');
        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
    }

    /**
     * @return void
     */
    public function showThemesList()
    {
        $layout = str_replace('%themeslist%',
            $this->getThemesList(), $this->tpl['themesList']);
        echo $layout;
    }

    /**
     * @return string
     */
    public function getThemesList()
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
     * @return false|string
     */
    public function loadTpl($filename)
    {
        return
            file_get_contents('tpl/'.$filename);
    }

    /**
     * @param $idTheme
     * @param $data
     * @return array|string|string[]
     */
    public function drawThemesRow($idTheme, $data)
    {
        $htmlRow = str_replace('%id%', $data->theme_id, $this->tpl['themesRow']);
        $htmlRow = str_replace('%name%', $data->theme_name, $htmlRow);

        return $htmlRow;
    }
}