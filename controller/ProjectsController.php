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

        ]);

        $projects = DB::table('projects')->where('UID', '=',$request->user()->getAuthIdentifier())->get();

        $this->addResult('projects',$projects);

        return $this->getResponse();
    }
}
