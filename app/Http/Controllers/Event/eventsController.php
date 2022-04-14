<?php

namespace App\Http\Controllers\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\events;



class eventsController extends Controller
{
    public function index(Request $req)
    {
    	$events = Events::select('*')->orderBy('id', 'DESC')
    			->get();
    	return view('User.Events.index', compact('events'));
    }

    public function add(Request $req)
    {
    	if($req->isMethod('post')){
    		date_default_timezone_set('Asia/Kolkata');
    		$date = date('Y-m-d H:i:s');
    		$check_if_exist = Events::where('name', $req->eventname)->count();
    		if($check_if_exist == TRUE){
    			return redirect()->back()->withErrors(['Already exists.']);
    		}else{
    			$params = ['name' => $req->eventname,
	    		'created_at' => $date];
	    		$query = Events::insert($params);
	    		return redirect('events/')->with('success', 'Added successfully.');
	    		
    		}
    	}
    	return view('User.Events.add');
    	
    }
    public function edit(Request $req)
    {
    	if($req->isMethod('post')){
    		date_default_timezone_set('Asia/Kolkata');
    		$date = date('Y-m-d H:i:s');
			$params = ['name' => $req->eventname,
    		'updated_at' => $date];
    		$query = Events::where('id', $req->id)->update($params);
    		return redirect('events/')->with('success', 'Updated successfully.');
    	}
    	$event = Events::where('id', $req->id)->get();
    	return view('User.Events.edit', compact('event'));
    }

    public function delete(Request $req)
    {
    	$delete = Events::where('id', $req->id)->delete();
    	if($delete == true){
    		$message = "Deleted successfully.";
    		return $message;
    	} 
    }


}
