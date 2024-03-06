<?php

class ThemesList extends Template {

    public function __construct(Database $connect, User $user)
    {
        parent::__construct($connect, $user);

        $this->tpl['themesListIntro'] = $this->loadTpl('themes.list.intro.html');
        $this->tpl['themesList'] = $this->loadTpl('themes.list.html');
        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
        $this->tpl['themesRow'] = $this->loadTpl('themes.row.html');
        $this->showThemesList();
    }
    /**
     * @return void
     */
    public function showThemesList(): void
    {
        $arrBreadcrumb = [
            [ 'title' => 'Гостевая книга' ]
        ];

        $layout = str_replace('%themesList%',
            $this->getThemesList(), $this->tpl['themesList']);
        $layout = str_replace('%header%', $this->tpl['header'], $layout);
        $layout = str_replace('%footer%', $this->tpl['footer'], $layout);
        $layout = str_replace('%headerTitle%', 'Гостевая книга', $layout);
        $layout = str_replace('%themesListIntro%', $this->tpl['themesListIntro'], $layout);
        $layout = str_replace('%breadcrumb%', $this->makeBreadcrumb($arrBreadcrumb), $layout);

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