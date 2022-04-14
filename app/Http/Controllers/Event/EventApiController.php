<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\events;
use Validator;

class EventApiController extends Controller
{
    public function index()
    {
    	return events::limit(5)->get();
      /* $result = events::where('name', 'LIKE', '%'. $req->name. '%')->get();
        if(count($result)){
         return Response()->json($result);
        }
        else
        {
        return response()->json(['Result' => 'No Data not found'], 404);
      }*/
    }
    /*************for add event********************/
    public function add(request $req)
    {
    	if($req->isMethod('post')){

            $rule = ['name' => "required|regex:/^[a-zA-Z-' ]*$/|"
                ];
            $validator = Validator::make($req->all(), $rule);
            if($validator->fails()){
                return response()->json([
                'message' => "Name is required",
               ]);
            }
    	        $event = new events;
    	        $event->name = $req->name;
    	        $event->save(); 
        	    return response()->json([
    		            'message' => " $req->name event register successfully.",
    	        	]);
        }
    }

    /*****************editEvent**************************/
    public function edit(Request $req)
    {        
        $params = ['name' => $req->name,
                    ];
                
                $query = events::where('id', $req->id)->update($params);
                return response()->json([
                        'message' => "event update.",
                    ]);

    }
    /**********************deleteEvent*********************/
    public function delete(Request $req)
    {
         $query = events::where('id', $req->id)->delete();
         return response()->json([
                        'message' => "delete event",
                    ]);
    }
}