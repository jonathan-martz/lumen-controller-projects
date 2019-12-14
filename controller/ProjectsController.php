<?php

namespace App\Http\Controllers;

use \http\Env\Response;
use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;

class ProjectsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function projects(Request $request)
    {
        $validation = $this->validate($request, [
            'auth.username' => 'required',
            'auth.password' => 'required|min:8',
            'auth.email' => 'required|min:8|email'
        ]);



        return $this->getResponse();
    }
}
