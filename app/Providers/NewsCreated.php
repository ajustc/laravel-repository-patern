<?php

namespace App\Providers;

use App\Providers\NewsHistoryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NewsCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\NewsHistoryCreated  $event
     * @return void
     */
    public function handle(NewsHistoryCreated $event)
    {
        DB::table('posts_created_history')->insert([
            'user_id'    => $event->user->id,
            'post_id'    => $event->event->id,
            'name'       => $event->user->name,
            'email'      => $event->user->email,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
