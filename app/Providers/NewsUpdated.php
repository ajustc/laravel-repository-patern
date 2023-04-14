<?php

namespace App\Providers;

use App\Providers\NewsHistoryUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NewsUpdated
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
     * @param  \App\Providers\NewsHistoryUpdated  $event
     * @return void
     */
    public function handle(NewsHistoryUpdated $event)
    {
        DB::table('posts_updated_history')->insert([
            'user_id'    => $event->user->id,
            'post_id'    => $event->event->id,
            'name'       => $event->user->name,
            'email'      => $event->user->email,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
