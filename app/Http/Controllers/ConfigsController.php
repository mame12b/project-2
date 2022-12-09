<?php

namespace App\Http\Controllers;

use App\Models\Configs;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    /**
     * start the node server and record information
     *
     * @param ]Illuminate\Http\Request $request
     *
     * @return array
     */
    public function startServer(Request $request)
    {
        // check if the user is admin
        if(auth()->user()->type != 'admin'){
            return [
                'status' => 'error',
                'message' => 'You are not authorized for this action!'
            ];
        }

        $is_node_on = Configs::where('name', 'is_node_on')->first();
        $status = Configs::where('name', 'status')->first();
        $name = Configs::where('name', 'name')->first();
        $mode = Configs::where('name', 'mode')->first();

        // check if currently there is server running
        if($is_node_on->value == '1' && $status->value == 'online'){
            return [
                'status' => 'error',
                'message' => 'Server is already Up and Running!'
            ];
        }

        $exc = shell_exec('cd ../ && pm2 start server.js --name websocket');

        if($exc){
            $is_node_on->update(['value'=> 1]);
            $status->update(['value'=> 'online']);
            $name->update(['value'=> 'websocket']);
            $mode->update(['value'=> 'fork']);

            return [
                'status' => 'success',
                'message' => 'Server started successfully!',
                'data' => [
                    'is_node_on' => ($is_node_on->value == '1') ? 'YES' : 'NO',
                    'status' => ($status->value) ? $status->value : 'offline',
                    'name' => ($name->value) ? $name->value : '-',
                    'mode' => ($mode->value) ? $mode->value : '-',
                    'date' =>  \Carbon\Carbon::parse($status->updated_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i:s')
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => 'unable to start the server!'
        ];
    }

    /**
     * stop the node server and record information
     *
     * @param ]Illuminate\Http\Request $request
     *
     * @return array
     */
    public function stopServer(Request $request)
    {
        // check if the user is admin
        if(auth()->user()->type != 'admin'){
            return [
                'status' => 'error',
                'message' => 'You are not authorized for this action!'
            ];
        }

        $is_node_on = Configs::where('name', 'is_node_on')->first();
        $status = Configs::where('name', 'status')->first();
        $name = Configs::where('name', 'name')->first();
        $mode = Configs::where('name', 'mode')->first();

        // check if currently there is server running
        if($is_node_on->value == '1' && $status->value == 'online'){
            $exc = shell_exec('cd ../ && pm2 delete websocket');

            if($exc){
                $is_node_on->update(['value'=> 0]);
                $status->update(['value'=> null]);
                $name->update(['value'=> null]);
                $mode->update(['value'=> null]);

                return [
                    'status' => 'success',
                    'message' => 'Server stopped successfully!'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'unable to stop the server!'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Server is already Down!'
        ];
    }

    /**
     * get the node config values
     *
     * @return array
     */
    public function getValues()
    {
        // check if the user is admin
        if(auth()->user()->type != 'admin'){
            return [
                'status' => 'error',
                'message' => 'You are not authorized for this action!'
            ];
        }

        $is_node_on = Configs::where('name', 'is_node_on')->first();
        $status = Configs::where('name', 'status')->first();
        $name = Configs::where('name', 'name')->first();
        $mode = Configs::where('name', 'mode')->first();

        return [
            'is_node_on' => ($is_node_on->value == '1') ? 'YES' : 'NO',
            'status' => ($status->value) ? $status->value : 'offline',
            'name' => ($name->value) ? $name->value : '-',
            'mode' => ($mode->value) ? $mode->value : '-',
            'date' => \Carbon\Carbon::parse($status->updated_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i:s')
        ];
    }
}
