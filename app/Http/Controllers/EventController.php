<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventStoreRequest;
use Carbon\Carbon;
use Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware(['jwt.auth', 'clearance']);
    }

    public function store(EventStoreRequest $request) {
        return response()->json(Event::create($request->all()));
    }

    public function list($userId, Request $request) {

        $eventlist = Event::whereDate('starttime', '>=', Carbon::today())->get();

        foreach($eventlist as $event)
        {
            $event['enrolled'] = count($event->users()->where('user_id', $userId)->get());
            $event['participants'] = count($event->users()->get());
        };
        return response()->json($eventlist);
    }

    public function update($id, EventStoreRequest $request){
        $user = Auth::user();
        $event = Event::findOrFail($id);
        $input = $request->validated();
        $event->fill($input)->save();
        $event['enrolled'] = count($event->users()->where('user_id', $user->id)->get());
        $event['participants'] = count($event->users()->get());
        return response()->json($event);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json($event);
    }

    public function enroll($eventId, Request $request){
        $event = Event::findOrFail($eventId);
        $current_user = Auth::user();
        $participating = count($event->users()->where('user_id', $current_user->id)->get());
        $event['participants'] = count($event->users()->get());

        if ($event['participants'] >= $event['maxparticipants'] && $participating === 0 && $event['maxparticipants'] != 0 ){
            return response()->json(['message' => 'No more participants allowed, please contact the Actie for enrollment possibilities'], 406);
        }

        if ($event['enrolltime'] >= Carbon::now()){
            if ($participating === 0) {
                $event->users()->attach($current_user->id);
                $enrolled = 1;
            } elseif ($participating > 0) {
                $event->users()->detach($current_user->id);
                $enrolled = 0;
            }
        } else {
            return response()->json(['message'=>'Enrollment is closed'], 406);
        }
        $event['participants'] = count($event->users()->get());
        $event['enrolled'] = $enrolled;
        return response()->json($event);
    }

    public function load($eventId){
        $event = Event::findorFail($eventId);
        $current_user = Auth::user();

        $event['enrolled'] = count($event->users()->where('user_id', $current_user->id)->get());
        $event['participants'] = $event->users()->get();
        $event['countParticipants'] = count($event->users()->get());
        return response()->json($event);
    }
}
