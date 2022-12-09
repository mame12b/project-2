<?php

use App\Models\Internship;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * send the application accepted mail to applicant's
 *
 * @param \App\Models\User $user
 * @return bool
 */
function sendApplicationAcceptedMail(User $user): bool
{
    try {
        Mail::to($user->email)->send(new \App\Mail\ApplicationAccepted());
        return true;
    } catch (\Throwable $exception) {
        Log::error($exception->getMessage());
        return false;
    }
}

/**
 * send the application accepted message to applicant's
 *
 * @param int|bool $room_id
 * @param \App\Models\Internship $internship
 * @return bool
 */
function sendApplicationAcceptedMessage(int|bool $room_id, Internship $internship): bool
{
    try {
        $data = view('email.accept');

        $message = new Message([
            'message_room_id' => $room_id,
            'sender_type' => 'Internship',
            'sender_id' => $internship->id,
            'text' => $data
        ]);

        if($message->save()) return true;

        return false;
    } catch (\Throwable $exception) {
        Log::error($exception->getMessage());
        return false;
    }
}

/**
 * send the internship started mail to intern's
 *
 * @param \App\Models\Internship $internship
 * @return bool
 */
function sendInternshipStartedMail(Internship $internship): bool
{
    try {
        $interns = $internship->getInterns();
        foreach($interns as $intern){
            Mail::to($intern->email)->send(new \App\Mail\InternshipStart($internship));
        }
        return true;
    } catch (\Throwable $exception) {
        Log::error($exception->getMessage());
        return false;
    }
}

/**
 * send the internship started message to intern's
 *
 * @param \App\Models\Internship $internship
 * @return bool
 */
function sendInternshipStartedMessage(Internship $internship): bool
{
    try {
        // sending individual message
        $interns = $internship->getInterns();
        foreach($interns as $intern){

            $data = view('email.start', ['internship'=>$internship]);

            $message = new Message([
                'message_room_id' => $intern->getRoom($internship->id),
                'sender_type' => 'Internship',
                'sender_id' => $internship->id,
                'text' => $data
            ]);

            $message->save();

        }

        // creating internship group
        $room_id = $internship->createGroup();

        // sending message to group
        if($room_id){
            $data = view('email.start', ['internship'=>$internship]);

            $message = new Message([
                'message_room_id' => $room_id,
                'sender_type' => 'Internship',
                'sender_id' => $internship->id,
                'text' => $data
            ]);

            $message->save();
        }

        return true;
    } catch (\Throwable $exception) {
        Log::error($exception->getMessage());
        return false;
    }
}
