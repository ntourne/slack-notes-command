<?php

use App\Models\Note;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

// Slack command params - https://api.slack.com/slash-commands
// token=gIkuvaNzQIHg97ATvDxqgjtO
// team_id=T0001
// team_domain=example
// channel_id=C2147483705
// channel_name=test
// user_id=U2147483697
// user_name=Steve
// command=/weather
// text=94070
// response_url=https://hooks.slack.com/commands/1234/5678

// Handle API
$app->post('api/v1/notes', function(Request $request) use ($app) {

    $text = $request->input('text');

    // if no param, empty
    if ($text == null)
        return response()->json('Error: No text');

    // if text is "list", return list of Notes
    else if ($text == "list") {
        $notes = Note::where('user_id', '3')
                        ->where('channel_id', '2')
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
       return response()->json($notes);
    }

    // save a new note
    else {
        $note = new Note();
        $note->team_id = $request->input('team_id');
        $note->channel_id = $request->input('channel_id');
        $note->user_id = $request->input('user_id');
        $note->text = $request->input('text');

        if ($note->save())
            return response()->json($note);
        else
            return response()->json("Error: Saving note");
    }

});
