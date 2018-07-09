<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Match;
use App\Models\Message;

class MatchController extends AdminController
{
    public function view(Match $match)
    {
        $messages = Message::where('match_id', $match->id)
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('Admin::match.view', compact('messages', 'match'));
    }
}