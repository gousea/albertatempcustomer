<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\ZeroMovement;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use PDF;
use DateTime;
use Illuminate\Support\Facades\DB;

class WhatsnewController extends Controller{
      public function index(Request $request) {
          return view('whatsnew');
      }
      
}