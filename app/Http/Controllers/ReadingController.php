<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//use App\Models\Book;

class ReadingController extends Controller
{
    public function index()
    {
        /*
        $books = Book::select('id', 'testament', 'title')
            ->with('chapters')
            ->where('chapters.users', auth()->user()->id)
            ->orderBy('id')
            ->get();
        */

        $books = [];
        $chapters = [];
        $auth_user_id = env('APP_ENV') == 'local'? 1: Auth::user()->id;

        $res = DB::select(
            "
            SELECT
                b.id,
                b.testament,
                b.title,
                c.chapter,
                c.id AS chapter_id,
                IF(r.id IS NULL, 0, 1) AS chk_read
            FROM `books` AS b
                JOIN `chapters` AS c ON b.id = c.book_id
                LEFT JOIN `reads` AS r ON c.id = r.chapter_id AND r.user_id = {$auth_user_id} AND r.deleted_at IS NULL
            ORDER BY b.id, c.id
        "
        );

        foreach ($res as $v) {
            $books[$v->testament][$v->id] = $v->title;
            $chapters[$v->id][$v->chapter] = [
                'chapter_id' => $v->chapter_id,
                'chk_read' => $v->chk_read
            ];
        }

        return view(
            'app/reading',
            [
                'books' => $books,
                'chapters' => $chapters,
                'user_id' => auth()->user()->id,
                'manager' => auth()->user()->manager == 'y'
            ]
        );
    }
}
