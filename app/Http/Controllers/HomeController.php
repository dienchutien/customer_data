<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class HomeController extends Eloquent
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = DB::connection('mongodb')->collection('downloadnews')->get();
        echo'<pre>';
        print_r($user);
        echo'</pre>';
        die;
        return view('home');
    }
}
