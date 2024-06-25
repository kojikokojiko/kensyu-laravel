<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class GetLoginPageController extends Controller
{
    public function __invoke()
    {
        return view('auth.login');
    }
}
