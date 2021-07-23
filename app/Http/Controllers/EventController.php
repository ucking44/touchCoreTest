<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class EventController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $this->user = DB::table('events')
        //                        ->join('users', 'events.user_id', '=', 'users.id')
        //                        ->select('events.*', 'users.id', 'users.login_id', 'users.avatar_url')
        //                        ->paginate(5);

        $events = Event::with('user:id')
                       ->with('repo')
                       ->latest()
                       ->get();

        return response()->json([
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event_type' => 'required',
        ]);

        $event = new Event();
        $event->event_type = $request->event_type;
        //$event->created_at = Carbon::now();

        if ($this->user->event()->save($event))

            return response()->json([
                'success' => true,
                'event' => $event,
            ], 200);

        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, event could not be added'
            ], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $eventId = $this->user->event()->find($event);

        if (!$eventId)
        {
            return response()->json([
                'success' => false,
                'message' => 'Event with id ' . $event . ' cannot be found',
            ], 400);
        }

        return $eventId;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $eventId = $this->user->event()->find($event);

        if (!$eventId)
        {
            return response()->json([
                'success' => false,
                'message' => 'Event with id ' . $event . ' cannot be found',
            ], 400);
        }

        $eventUp = $eventId->fill($request->all())->save();

        if ($eventUp)
        {
            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully !!!',
            ], 201);
        }

        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Event could not be updated (:',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = $this->user->event()->find($id);

        if (!$event)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, event with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($event->delete())
        {
            return response()->json([
                'success' => true
            ], 200);
        }

        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Event could not be deleted'
            ], 500);
        }
    }
}
