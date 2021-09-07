<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\DB;

class GiftCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('giftcard.index');
    }
}
