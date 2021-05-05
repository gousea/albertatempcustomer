<?php

namespace App\Http\Controllers;

use App\Model\Template;
use Illuminate\Http\Request;
use App\User;
use App\Model\UserDynamic;

class TimeclockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = UserDynamic::orderBy('iuserid', 'DESC')->paginate(20);
        return view('timeclock.index', compact('users'));
    }
}