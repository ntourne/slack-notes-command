<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NotesController extends Controller {

    /**
     * Handle all requests
     * @param  Request $request
     * @return JSON
     */
    public function execute(Request $request) {

        $text = $request->input('text');

        // if no param, empty
        if ($text == null)
            return response()->json('Error: No text');

        // convert text as array
        $text = explode(' ', $text);

        // if text is "list", return list of Notes
        if ($text[0] == "list")
            return $this->listing($text, $request);

        else
            return $this->create($text, $request);
    }


    /**
     * Return list of notes
     * @param  array $text
     * @param  Request $request
     * @return JSON
     */
    private function listing($text, $request) {

        // parse params
        $q_param = [];
        $by_param = NULL;

        if (count($text) > 1) {
            for ($i = 1; $i < count($text); $i++) {
                // reported by
                if (substr($text[$i], 0, 3) === "by:")
                    $by_param = explode(':', $text[$i])[1];

                // $query
                else
                    $q_param[] = $text[$i];
            }
        }

        // create new query
        $query = Note::select();

        // filter by note text
        if (count($q_param) > 0) {
            for ($i = 0; $i < count($q_param); $i++)
                $query->where('text', 'LIKE', '%' . $q_param[$i] . '%');
        }

        // filter by author
        if ($by_param)
            $query->where('user_name', $by_param);

        // filter by channel
        if ($request->input('channel_id'))
            $query->where('channel_id', $request->input('channel_id'));

        $query->orderBy('created_at', 'desc');
        $query->take(10);
        $notes = $query->get();

        return response()->json($notes);
    }


    /**
     * Create new note
     * @param  array $text
     * @param  Request $request
     * @return JSON
     */
    private function create($text, $request) {
        $note = new Note();
        $note->team_id = $request->input('team_id');
        $note->team_domain = $request->input('team_domain');
        $note->channel_id = $request->input('channel_id');
        $note->channel_name = $request->input('channel_name');
        $note->user_id = $request->input('user_id');
        $note->user_name = $request->input('user_name');
        $note->text = $text;

        if ($note->save())
            return response()->json($note);
        else
            return response()->json("Error: Saving note");
    }



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

}
