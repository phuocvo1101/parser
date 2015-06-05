<?php

namespace Controllers;


class BaseController {
    protected  $template;
    public function __construct()
    {
        global $smarty;
        $this->template= $smarty;
    }

} 