<?php

namespace App\Http\Controllers;

use App\Book;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getAll()
    {
        $books = Book::all()->load('tags');

        return response()-> json([
            'books' => $books
        ], 200);
    }
    public function allBooks()
    {
        $paginate = Book::with('tags')->paginate(6);

        return response()-> json([
            'paginate' => $paginate
        ], 200);
    }
    public function booksByTags(Request $request)
    {
        $tags  = $request->tags;

        $tags = explode(",", $tags);

        $paginate = Book::with('tags')->whereHas('tags', function($q) use ($tags) {
            $q->whereIn('title', $tags);
        })->paginate(6);

        return response()-> json([
            'paginate' => $paginate
        ], 200);
    }
    public function booksBySearchQuery(Request $request)
    {
        $paginate = Book::with('tags')->where('title', 'LIKE', '%'.$request->search.'%')->paginate(6);

        return response()-> json([
            'paginate' => $paginate
        ], 200);
    }
    public function getOne($id)
    {
        $book = Book::find($id)->load('tags');

        return response()-> json([
            'book' => $book
        ], 200);
    }
    public function new(Request $request)
    {
        $validateData = json_decode($request->book, true); // преобразуем json в массив(нужно для валидации)

        $validateData['cover'] = $request->cover; 

        $validator = Validator::make(
            $validateData,
            [
                'title' => 'required|max:256',
                'authors' => 'required|max:1024',
                'year' => 'required|numeric|max:9999',
                'cover' => 'required',
                'cover' => 'required|max:2048|mimes:jpg,jpeg,png'
            ],
            $messages = [
                'title.required' => 'Название - обязательное поле',
                'title.max' => 'Название слишком длинное',
                'authors.required' => 'Авторы - обязательное поле',
                'authors.max' => 'Поле авторы слишком длинное',
                'year.required' => 'Год - обязательное поле',
                'year.numeric' => 'Год - должен иметь цифровое значение',
                'year.max' => 'Некорректный год',
                'cover.required' => 'Вы не добавили обложку',
                'cover.mimes' => 'Загруженный файл должен быть изображением в формате JPEG или PNG',
                'cover.max' => 'Загруженный файл должен быть не более 2 мегабайт'
            ]
        );
       
        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->all()
            ], 200);

            return;
        }

        $tags = json_decode($request->tags);

        $book = json_decode($request->book);
        
        //для FTP
        $cover =  $request->file('cover')->store('site/wwwroot/files');
        
        $cover = env('FTP_WEB_URL').str_replace('site/wwwroot', '', $file);

        // для Local
        //$cover = $request->file('cover')->store('public/covers');

        //$cover = asset(Storage::url($cover));

        $book = Book::create([
            'title' => $book->title, 
            'authors' => $book->authors,
            'cover' => $cover,
            'year' => $book->year
        ]);
        
        if ($tags) {
            foreach ($tags as $tag) {
                $book->tags()->attach($tag);
            }
        }

        return response()->json([
            'book' => $book->load('tags')
        ], 200);        
        
    }
    public function update(Request $request, $id)
    {
        $validateBook = json_decode($request->book, true); // преобразуем json в массив(нужно для валидации)

        $validatorCover = Validator::make([],[]);

        if ($request->coverIsChanging) {

            $validateCover['cover'] = $request->cover; 

            $validatorCover = Validator::make(
                $validateCover,
                [
                    'cover' => 'required',
                    'cover' => 'required|max:2048|mimes:jpg,jpeg,png'
                ],
                $messages = [
                    'cover.required' => 'Вы не добавили обложку',
                    'cover.mimes' => 'Загруженный файл должен быть изображением в формате JPEG или PNG',
                    'cover.max' => 'Загруженный файл должен быть не более 2 мегабайт'
                ]
            );
        }        

        $validatorBook = Validator::make(
            $validateBook,
            [
                'title' => 'required|max:256',
                'authors' => 'required|max:1024',
                'year' => 'required|numeric|min:1|max:9999',
            ],
            $messages = [
                'title.required' => 'Название - обязательное поле',
                'title.max' => 'Название слишком длинное',
                'authors.required' => 'Авторы - обязательное поле',
                'authors.max' => 'Поле авторы слишком длинное',
                'year.required' => 'Год - обязательное поле',
                'year.numeric' => 'Год - должен иметь цифровое значение',
                'year.max' => 'Некорректный год',
                'year.min' => 'Некорректный год'
            ]
        );
       
        if (($validatorBook->fails()) || ($validatorCover->fails())) {

            $errors = array_merge ($validatorBook->errors()->all(), $validatorCover->errors()->all());
            
            return response()->json([
                'status' => 'error',
                'errors' => $errors
            ], 200);

            return;
        }

        $tags = json_decode($request->tags);

        $book = json_decode($request->book);

        if ($request->coverIsChanging) {

            //для FTP
            $cover =  $request->file('cover')->store('site/wwwroot/files');
        
            $cover = env('FTP_WEB_URL').str_replace('site/wwwroot', '', $file);
            
            //для LOCAL
            //$cover = $request->file('cover')->store('public/covers');

            //$cover = asset(Storage::url($cover));

            // Для FTP

            $path = 'site/wwwroot'.str_replace(env('FTP_WEB_URL'), '', $book->cover);

            Storage::delete($path);

            //для LOCAL
            //Storage::delete('public'.str_replace(asset('/').'storage', '', $book->cover));
        }       

        $updatedBook = Book::find($id);

        $updatedBook->title = $book->title;

        $updatedBook->authors = $book->authors;

        $updatedBook->year = $book->year;

        if ($request->coverIsChanging) {
            $updatedBook->cover = $cover;
        }
        
        $updatedBook->save();

        if (count($updatedBook->tags)) {
            foreach ($updatedBook->tags as $tag) {
                $toDetach[] = $tag->id;
            }
            $updatedBook->tags()->detach($toDetach);
        }

        if ($tags) {
            foreach ($tags as $tag) {
                $updatedBook->tags()->attach($tag);
            }
        }

        return response()->json([
            'book' => $updatedBook->load('tags')
        ], 200);        
        
    }
    public function remove($id)
    {
        $book = Book::find($id);

        if (count($book->tags)) {
            foreach ($book->tags as $tag) {
                $toDetach[] = $tag->id;
            }
            $book->tags()->detach($toDetach);
        }

        // Для FTP
        $path = 'site/wwwroot'.str_replace(env('FTP_WEB_URL'), '', $book->cover);
            
        Storage::delete($path);

        // Для LOCAL
        //Storage::delete('public'.str_replace(asset('/').'storage', '', $book->cover));

        //$book->delete();

        return response()->json([
            'status' => 'success'
        ], 200);
    }
}
