<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function likeBook(Request $request)
    {
        Log::info($request);

        $like_book = Like::create(['book_id' => $request->book_id, 'user_id' => 1]);
        if ($like_book) {

            return true;
        }
        return false;
    }
    public function dislikeBook($book_id)
    {

        $like_book = Like::where('book_id', $book_id)->where('user_id', 1);
        $like_book->delete();
        if ($like_book) {

            return true;
        }
        return false;
    }
}
