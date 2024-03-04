<?php

class Route {

    private array $list;

    public function add(String $path): void
    {
        $this->list[] = $path;
    }

    public function list(): void
    {
        foreach ($this->list as $path) {
            echo "<p>{$path}</p>";
        }
    }

    public function check(String $path): bool
    {
        return in_array($path, $this->list);
    }

    public function addItems(array $list): void
    {
        if (!empty($list)) {
            foreach ($list as $k=>$item) {
                $this->add($item);
            }
        }
    }
}