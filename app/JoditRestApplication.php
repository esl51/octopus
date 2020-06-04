<?php

namespace App;

use Jodit\Application;

class JoditRestApplication extends Application {

    function checkAuthentication() {
        return true;
    }
}
