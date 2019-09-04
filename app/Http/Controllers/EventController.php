<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventStoreRequest;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function store(EventStoreRequest $request) {
        return response()->json(Event::create($request->all()));
    }

    public function list($userId, Request $request) {
        $eventlist = Event::all();

        foreach($eventlist as $event)
        {
            $event['enrolled'] = count($event->users()->where('user_id', $userId)->get());
        };
        return response()->json($eventlist);
    }

    public function update($id, EventStoreRequest $request){
        $event = Event::findOrFail($id);
        $input = $request->validated();
        $event->fill($input)->save();

        return response()->json($event);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json($event);
    }

    public function enroll($eventId, $userId, Request $request){
        $event = Event::findOrFail($eventId);
        $participating = count($event->users()->where('user_id', $userId)->get());

        if ($participating == 0) {
            $event->users()->attach($userId);
            $enrolled = 1;
        } elseif ($participating > 0) {
            $event->users()->detach($userId);
            $enrolled = 0;
        }
        
        $arr = json_decode($event, true);
        $arr['enrolled']=$enrolled;
        $event = json_encode($arr);
        return response()->json($arr);
    }
}
