<?php

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
    echo "You send this text: " . $text;

});
