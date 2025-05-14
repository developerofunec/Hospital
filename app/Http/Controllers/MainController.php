<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function salamla(){
        return response()->json('salam ay brat');
    }
}
