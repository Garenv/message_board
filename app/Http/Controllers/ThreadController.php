<?php

namespace App\Http\Controllers;
use App\Models\Thread;

class ThreadController extends Controller
{
    /**
     * @return Thread
     */
    public function createThread() {
        /**
         * Doug - No validation, what if title is missing, empty, etc.
         */
        $threadModel = new Thread();
        $title = request()->get('title');

        $threadModel->title = $title;
        $threadModel->save();

        return $threadModel;
    }

    /**
     * @param $threadId
     */
    public function getThreadMessages($threadId) {
        $givenThread = Thread::findOrFail($threadId);

        return $givenThread->message;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserThreads($userId) {
        return Thread::whereHas('message', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }

    /**
     * @param $threadId
     * @return mixed
     */
    public function createThreadMessage($threadId) {
        /**
         * Doug - No input validation
         */
        // Example endpoint below
        // http://127.0.0.1:8000/api/createThreadMessage/4?userId=2&body=someTest
        $userId = request()->query('userId');
        $body = request()->query('body');
        $givenThread = Thread::findOrFail($threadId);

        return $givenThread->message()->create([
            'user_id' => $userId,
            'thread_id' => $threadId,
            'body' => $body
        ]);
    }

    /**
     * @param $threadId
     * @return mixed
     */
    public function searchThreadMessages($threadId) {
        $searchText = request()->query('searchText');
        $givenThread = Thread::findOrFail($threadId);

        return $givenThread->message()
            ->where('body', 'LIKE', '%' . $searchText . '%')
            ->get();
    }

}
