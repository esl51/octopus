<?php

namespace App\Models;

use Jodit\Application;

class JoditRestApplication extends Application {

    function checkAuthentication() {
        return true;
    }
}
