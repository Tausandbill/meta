<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::paginate(2);
        return response()->json($books);
    }

    public function store($isbn)
    {
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&jscmd=data&format=json";
        $response = Http::get($url);
        
        if ($response->json() == []) {
            return response()->json(['Error' => 'ISBN no encontrado']);
        }
        elseif ($response->status() == 200) {
            $title = $response['ISBN:' . $isbn]['title'];
            
            //Verificando si existe caratula o poniendo una default
            $cover = array_key_exists('cover', $response['ISBN:' . $isbn]) ? $response['ISBN:' . $isbn]['cover']["large"] : "https://islandpress.org/sites/default/files/default_book_cover_2015.jpg";
            $authors = $response['ISBN:' . $isbn]['authors'];

            //Helper function para crear string de autores     
            $authorString = authorString($authors);

            $data = [
                'isbn' => $isbn,
                'title' => $title,
                'authors' => $authorString,
                'cover' => $cover
            ];

            $book = Book::create($data);
            return response()->json(['Status' => 'Exito', $book]);
        }  
    }

    public function show(Book $isbn)
    {
        return response()->xml(['book' => $isbn->toArray()]);
    }

    public function destroy($isbn)
    {
        $book = Book::where('isbn', $isbn)->delete();

        if ($book == 0) {
            return response()->json(['Status' => 'Libro no existe']);
        }
        else {
            if (strpos(url()->previous(), 'list') == true) {
                return redirect('/list');
            }
            else {
                return response()->json(['Status' => 'Exito']);
            }            
        }        
    }

    public function list()
    {
        $request = Request::create('/api/books', 'GET');
        $response = Route::dispatch($request)->content();
        $data = json_decode($response);
        $books = $data->data;
        return view('books.index', compact('books', 'data'));
    }
}
