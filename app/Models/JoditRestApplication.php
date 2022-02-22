<?php

namespace App\Models;

use Jodit\Application;

class JoditRestApplication extends Application
{
    public function checkAuthentication()
    {
        return true;
    }
}
