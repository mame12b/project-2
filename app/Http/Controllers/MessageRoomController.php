<?php

namespace App\Http\Controllers;

use App\Models\MessageRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MessageRoomController extends Controller
{
    /**
     * current route root extractor
     *
     * @var string $current_route
     */
    public ?string $current_route = null;

    public function __construct() {
        $this->current_route = explode('.', Route::currentRouteName())[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function departmentIndex()
    {
        /**
         * get all single rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $single_rooms = MessageRoom::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->where('type', 'single')->get();

        /**
         * get all group rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $group_rooms = MessageRoom::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->where('type', 'group')->get();

        return view('pages.department.message.main', ['group_rooms'=>$group_rooms, 'single_rooms'=>$single_rooms]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex()
    {
        /**
         * get all single rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $single_rooms = MessageRoom::where('user_id', auth()->user()->id)->get();

        /**
         * get all group rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $group_rooms = MessageRoom::whereIn('id', function($query) {
            $query->select('message_room_id')
            ->from('message_room_groups')
            ->where('user_id', auth()->user()->id);
        })->get();

        return view('pages.user.message.main', ['group_rooms'=>$group_rooms, 'single_rooms'=>$single_rooms]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'internship_id' => 'required|exists:\App\Models\Internship,id',
            'user_id' => 'required|exists:\App\Models\User,id',
            'type' => 'required|string'
        ]);

        // creating room
        $room = new MessageRoom([
            'internship_id' => $request->internship_id,
            'user_id' => $request->user_id,
            'type' => $request->type
        ]);

        if($room->save()){
            return redirect()->route('department.message.view', $room->id);
        }else{
            return redirect()->route('department.application.view')->with('error', 'Something went wrong, please try again!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageRoom  $messageRoom
     * @return \Illuminate\Http\Response
     */
    public function departmentShow(MessageRoom $message_room)
    {
        /**
         * get all single rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $single_rooms = MessageRoom::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->where('type', 'single')->get();

        /**
         * get all group rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $group_rooms = MessageRoom::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->where('type', 'group')->get();

        if($message_room->internship->department->id == auth()->user()->department->id){
            return view('pages.department.message.main', ['message_room' => $message_room, 'group_rooms'=>$group_rooms, 'single_rooms'=>$single_rooms]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MessageRoom  $messageRoom
     * @return \Illuminate\Http\Response
     */
    public function userShow(MessageRoom $message_room)
    {
        /**
         * get all single rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $single_rooms = MessageRoom::where('user_id', auth()->user()->id)->get();

        /**
         * get all group rooms
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $group_rooms = MessageRoom::whereIn('id', function($query) {
            $query->select('message_room_id')
            ->from('message_room_groups')
            ->where('user_id', auth()->user()->id);
        })->get();

        if($message_room->user_id == auth()->user()->id){
            return view('pages.user.message.main', ['message_room' => $message_room, 'group_rooms'=>$group_rooms, 'single_rooms'=>$single_rooms]);
        }else{
            if($message_room->hasMember(auth()->user()->id)){
                return view('pages.user.message.main', ['message_room' => $message_room, 'group_rooms'=>$group_rooms, 'single_rooms'=>$single_rooms]);
            }else{
                return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
            }
        }
    }


}
