<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Repo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class RepoController extends Controller
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
        $repos = DB::table('repos')
                               ->join('users', 'repos.user_id', '=', 'users.id')
                               ->join('events', 'repos.event_id', '=', 'events.id')
                               ->select('repos.*', 'users.id', 'users.login_id', 'users.avatar_url', 'events.id', 'events.event_type')
                               ->paginate(5);

        return response()->json([
            'repos' => $repos,
        ]);

        // $repos = Repo::with('user:id, login_id, avatar_url')
        //              ->with('event:id, event_type')
        //              ->latest()
        //              ->get();

        // return response()->json([
        //     'repos' => $repos,
        // ]);
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
    public function store(Request $request, Event $event)
    {
        $this->validate($request, [
            'repo_name' => 'required',
            'url' => 'required',
        ]);

        $repo = new Repo();
        $repo->repo_name = $request->repo_name;
        $repo->url = $request->url;
        $repo->user_id = Auth::user()->id;
        $repo->event_id = $request->event_id;
        $repo->save();

        //if ($event->repo()->save($repo))

        return response()->json([
            'success' => true,
            'message' => 'Repo added successfully !!!',
            'repos' => $repo,
        ], 200);

        // else
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Sorry, repo could not be added'
        //     ], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repo  $repo
     * @return \Illuminate\Http\Response
     */
    public function show(Repo $repo)
    {
        $repoID = $this->user->repo()->find($repo);

        if (!$repoID)
        {
            return response()->json([
                'success' => false,
                'message' => 'Repo with id ' . $repo . ' cannot be found',
            ], 400);
        }

        return $repoID;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Repo  $repo
     * @return \Illuminate\Http\Response
     */
    public function edit(Repo $repo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repo  $repo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repo $id)
    {
        $this->validate($request, [
            'repo_name' => 'required',
            'url' => 'required',
        ]);

        $repo = Repo::findOrFail($id);
        $repo->repo_name = $request->repo_name;
        $repo->url = $request->url;
        $repo->save();

        return response()->json([
            'success' => true,
            'message' => 'Repo Updated successfully !!!',
            'repos' => $repo,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repo  $repo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $repo = $this->user->repo()->find($id);

        if (!$repo)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, repo with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($repo->delete())
        {
            return response()->json([
                'success' => true
            ]);
        }

        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Repo could not be deleted'
            ], 500);
        }

    }
}
