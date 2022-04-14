<?php

namespace App\Http\Controllers\Dashboard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect, DB, Session;
use App\Models\events;

class dashboardController extends Controller
{
	public function index(Request $req)
	{
		if(Session::get('username')){
			$events = Events::count();
			return view('User.Dashboard.index', compact('events'));
		}else{
			return view('User.Auth.login');
		}
	}
}
