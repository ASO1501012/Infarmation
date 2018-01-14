<?php

class sec{
    //エスケープ処理のための関数
    public function hsc($string) {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }
}

?>