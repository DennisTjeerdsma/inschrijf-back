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

    public function enroll($eventId, Request $request){
        $event = Event::findOrFail($eventId);
        $current_user = Auth::user();
        $participating = count($event->users()->where('user_id', $current_user->id)->get());

        if ($event['enrolltime'] >= Carbon::now()){
            if ($participating == 0) {
                $event->users()->attach($current_user->id);
                $enrolled = 1;
            } elseif ($participating > 0) {
                $event->users()->detach($current_user->id);
                $enrolled = 0;
            }
        } else {
            return response()->json(['message'=>'Enrollment is closed'], 406);
        }
                $arr = json_decode($event, true);
        $arr['enrolled']=$enrolled;
        $arr['participants'] = count($event->users()->get());
        $event = json_encode($arr);
        return response()->json($arr);
    }
}
