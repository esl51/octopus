<?php

namespace App\Http\Controllers;

use App\Models\JoditRestApplication;
use Illuminate\Http\Request;

class JoditController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fileBrowser = new JoditRestApplication(config('jodit'));
        try {
            $fileBrowser->checkAuthentication();
            $fileBrowser->execute();
        } catch(\ErrorException $e) {
            $fileBrowser->exceptionHandler($e);
        }
    }
}
