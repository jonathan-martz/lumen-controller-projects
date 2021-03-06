<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class ProjectsController
 * @package App\Http\Controllers
 */
class ProjectsController extends Controller
{

    /**
     * @return mixed
     */
    public function select()
    {
        $validatedData = $this->request->validate([
            'limit' => 'integer',
            'highlight' => 'string'
        ]);
        $projects = DB::table('projects');

        $this->addData('projects', $projects->get());

        return $this->getResponse();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $validation = $this->validate($this->request, [
            'id' => 'required|integer'
        ]);

        $id = $this->request->input('id');

        $project = DB::table('projects')
            ->where('UID', '=', $this->request->user()->getAuthIdentifier())
            ->where('id', '=', $id)
            ->get();

        $this->addData('project', $project[0]);

        return $this->getResponse();
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function remove()
    {
        $validation = $this->validate($this->request, [
            'id' => 'required|integer'
        ]);

        $id = $this->request->input('id');

        $delete = DB::table('projects')
            ->where('id', '=', $id)
            ->delete();

        if ($delete) {
            $this->addMessage('success', 'Project removed.');
        } else {
            $this->addMessage('error', 'Project with id ' . $id . ' doesnt exists.');
        }

        return $this->getResponse();
    }

    /**
     * @return mixed
     */
    public function add()
    {
        $validation = $this->validate($this->request, [
            'project.name' => 'required|string',
            'project.prefix' => 'required|string'
        ]);

        $name = $this->request->input('project.name');
        $prefix = $this->request->input('project.prefix');

        $list['name'] = DB::table('projects')->where('name', '=', $name)->where('UID', '=', $this->request->user()->getAuthIdentifier())->get();
        $list['prefix'] = DB::table('projects')->where('prefix', '=', $prefix)->where('UID', '=', $this->request->user()->getAuthIdentifier())->get();
        $list['both'] = DB::table('projects')->where('prefix', '=', $prefix)->where('UID', '=', $this->request->user()->getAuthIdentifier())->where('name', '=', $name)->get();

        if($list['name']->count() === 0 && $list['prefix']->count() === 0 && $list['both']->count() === 0) {

            $insert = DB::table('projects')->insert([
                'UID' => $this->request->user()->getAuthIdentifier(),
                'name' => $name,
                'prefix' => $prefix
            ]);

            if($insert) {
                $this->addMessage('success', 'Project added.');
            }
            else {
                $this->addMessage('warning', 'Project something went wrong while adding this project.');
            }
        } else if($list['name']->count() !== 0) {
            $this->addMessage('warning', 'Project with name already exists.');
        }
        else if($list['prefix']->count() !== 0) {
            $this->addMessage('warning', 'Project with prefix already exists.');
        }
        else if($list['both']->count() !== 0) {
            $this->addMessage('warning', 'Project with name/prefix already exists.');
        }
        else {
            $this->addMessage('error', 'Project something went wrong while adding this project 2.');
        }

        return $this->getResponse();
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $validation = $this->validate($this->request, [
            'project.name' => 'required|string',
            'project.prefix' => 'required|string',
            'project.id' => 'required|integer'
        ]);

        $name = $this->request->input('project.name');
        $prefix = $this->request->input('project.prefix');
        $id = $this->request->input('project.id');

        $list['both'] = DB::table('projects')->where('id', '=', $id)->where('UID', '=', $this->request->user()->getAuthIdentifier())->get();

        if ($list['both']->count() === 1) {

            $update = DB::table('projects')->update([
                'name' => $name,
                'prefix' => $prefix
            ]);

            if ($update) {
                $this->addMessage('success', 'Project updated.');
            } else {
                $this->addMessage('warning', 'Project not updated.');
            }
        } else if ($list['both']->count() === 0) {
            $this->addMessage('warning', 'Project doesnt exists.');
        } else {
            $this->addMessage('error', 'Project with id ' . $id . ' doesnt exists.');
        }

        return $this->getResponse();
    }
}
