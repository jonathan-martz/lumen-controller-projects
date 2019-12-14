<?php

namespace App\Http\Controllers;

use \http\Env\Response;
use Illuminate\Http\JsonResponse;
use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class ProjectsController
 * @package App\Http\Controllers
 */
class ProjectsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function projects(Request $request)
    {
        $validation = $this->validate($request, [

        ]);

        $projects = DB::table('projects')->where('UID', '=',$request->user()->getAuthIdentifier())->get();

        $this->addResult('projects',$projects);

        return $this->getResponse();
    }

    public function get(Request $request)
    {
        $validation = $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $id = $request->input('id');

        $project = DB::table('projects')
            ->where('UID', '=',$request->user()->getAuthIdentifier())
            ->where('id', '=',$id)
            ->get();

        $this->addResult('project',$project[0]);

        return $this->getResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function remove(Request $request)
    {
        $validation = $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $id = $request->input('id');

        $delete = DB::table('projects')
            ->where('id', '=',$id)
            ->delete();

        if($delete){
            $this->addMessage('success', 'Project removed.');
        }
        else{
            $this->addMessage('error', 'Project with id '.$id.' doesnt exists.');
        }

        return $this->getResponse();
    }

    public function add(Request $request)
    {
        $validation = $this->validate($request, [
            'project.name' => 'required|string',
            'project.token' => 'required|string'
        ]);

        $name = $request->input('project.name');
        $token = $request->input('project.token');

        $list['name'] = DB::table('projects')->where('name', '=',$name)->where('UID', '=',$request->user()->getAuthIdentifier())->get();
        $list['token'] = DB::table('projects')->where('token', '=',$token)->where('UID', '=',$request->user()->getAuthIdentifier())->get();
        $list['both'] = DB::table('projects')->where('token', '=',$token)->where('UID', '=',$request->user()->getAuthIdentifier())->where('name', '=',$name)->get();

        if($list['name']->count() === 0 && $list['token']->count() === 0 && $list['both']->count() === 0){

            $insert = DB::table('projects')->insert([
                'UID' => $request->user()->getAuthIdentifier(),
                'name' => $name,
                'token' => $token
            ]);

            if($insert){
                $this->addMessage('success', 'Project added.');
            }
            else{
                $this->addMessage('warning', 'Project something went wrong while adding this project.');
            }
        }
        else if($list['name']->count() !== 0){
            $this->addMessage('warning', 'Project with name already exists.');
        }
        else if($list['token']->count() !== 0){
            $this->addMessage('warning', 'Project with token already exists.');
        }
        else if($list['both']->count() !== 0){
            $this->addMessage('warning', 'Project with name/token already exists.');
        }
        else{
            $this->addMessage('error', 'Project something went wrong while adding this project 2.');
        }

        return $this->getResponse();
    }

    public function update(Request $request)
    {
        $validation = $this->validate($request, [
            'project.name' => 'required|string',
            'project.token' => 'required|string',
            'project.id' => 'required|integer'
        ]);

        $name = $request->input('project.name');
        $token = $request->input('project.token');
        $id = $request->input('project.id');

        $list['both'] = DB::table('projects')->where('id', '=',$id)->where('UID', '=',$request->user()->getAuthIdentifier())->get();

        if($list['both']->count() === 1){

            $update = DB::table('projects')->update([
                'name' => $name,
                'token' => $token
            ]);

            if($update){
                $this->addMessage('success', 'Project updated.');
            }
            else{
                $this->addMessage('warning', 'Project not updated.');
            }
        }
        else if($list['both']->count() === 0){
            $this->addMessage('warning', 'Project doesnt exists.');
        }
        else{
            $this->addMessage('error', 'Project with id '.$id.' doesnt exists.');
        }

        return $this->getResponse();
    }
}
