<?php

namespace App\Jobs;

use App\Models\Internship;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInternshipStartedMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Internship $internship)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // sending individual message
        $interns = $this->internship->getInterns();
        foreach($interns as $intern){

            $data = view('email.start', ['internship'=>$this->internship]);

            $message = new Message([
                'message_room_id' => $intern->getRoom($this->internship->id),
                'sender_type' => 'Internship',
                'sender_id' => $this->internship->id,
                'text' => $data
            ]);

            $message->save();

        }

        // creating internship group
        $room_id = $this->internship->createGroup();

        // sending message to group
        if($room_id){
            $data = view('email.start', ['internship'=>$this->internship]);

            $message = new Message([
                'message_room_id' => $room_id,
                'sender_type' => 'Internship',
                'sender_id' => $this->internship->id,
                'text' => $data
            ]);

            $message->save();
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        dd($exception->getMessage());
        Log::error($exception->getMessage());
    }
}
