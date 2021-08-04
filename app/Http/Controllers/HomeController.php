<?php
namespace App\Http\Controllers;

use App\Models\UsersPhoneNumber;
use App\Models\UsersPhoneEmail;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class HomeController extends Controller
{
    /**
     * Show the forms with users phone number details.
     *
     * @return Response
     */
    public function show()
    {
        $sid = getenv("TWILIO_SID");
$token = getenv("TWILIO_AUTH_TOKEN");
$twilio = new Client($sid, $token);

$messages = $twilio->messages
                   ->read([], 20);

foreach ($messages as $record) {
    print($record->sid);

    
        $users = UsersPhoneNumber::all();
        $users2 = UsersPhoneEmail::all();

        return view('welcome', compact("users"),compact("users2"));
    }
    /**
     * Store a new user phone number.
     *
     * @param  Request  $request
     * @return Response
     */

        $user_phone_number_model = new UsersPhoneNumber($request->all());
        $user_phone_number_model->save();

        $user_phone_number_email = new UsersPhoneEmail($request->all());
        $user_phone_number_email->save();
        $this->sendMessage('User registration successful!!', $request->phone_number);
        return back()->with(['success' => "{$request->phone_number} registered"]);
    }
    /**
     * Send message to a selected users
     */
    public function sendCustomMessage(Request $request)
    {
        $validatedData = $request->validate([
            'users' => 'required|array',
            'body' => 'required',
        ]);
        $recipients = $validatedData["users"];
        // iterate over the array of recipients and send a twilio request for each
        foreach ($recipients as $recipient) {
            $this->sendMessage($validatedData["body"], $recipient);
        }
        return back()->with(['success' => "Messages on their way!"]);
    }
    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients Number of recipient
     */
    private function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
    }
}