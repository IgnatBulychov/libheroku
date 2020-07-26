<?php

namespace App\Http\Controllers;

use App\Tag;
use Validator;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getAll()
    {
        $tags = Tag::all();

        return response()-> json([
            'tags' => $tags
        ], 200);
    }
    public function new(Request $request)
    {
        $validateData = json_decode($request->tag, true); // преобразуем json в массив(для валидации)
       
        $validator = Validator::make(
            $validateData,
            [
                'title' => 'required|max:256'
            ],
            $messages = [
                'title.required' => 'Тег - обязательное поле',
                'title.max' => 'Тег слишком длинный'
            ]
        );
       
        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' =>  json_encode($validator->errors()->all(), JSON_UNESCAPED_UNICODE)
            ], 400);

        } else {

            $tag = json_decode($request->tag);

            $tag = Tag::create([
                'title' => $tag->title
            ]);

            return response()->json([
                'tag' => $tag
            ], 200);
        }
        
    }
    public function remove($id)
    {
        $tag = Tag::find($id);

        if (count($tag->books)) {
            foreach ($tag->books as $book) {
                $forDetach[] = $book->id;
            } 
            $tag->books()->detach($forDetach);
        }

        $tag->delete();

        return response()->json([
            'status' => 'success'
        ], 200);
    }
}
