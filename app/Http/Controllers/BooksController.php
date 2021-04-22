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

            //Creando Array
            $data = [
                'isbn' => $isbn,
                'title' => $title,
                'authors' => $authorString,
                'cover' => $cover
            ];

            //Guardando libro
            $book = Book::create($data);
            return response()->json(['Status' => 'Exito', $book]);
        }  
    }

    public function show($isbn)
    {
        //Obteniendo libro con base en ISBN
        $book = Book::where('isbn', $isbn)->get();
        
        //Determinado si el libro existe y crendo repuesta
        if (count($book) == 0) {
            $array = ['Libro' => 'Error Libro no encontrado'];
            $value = response()->xml($array);
        } else {
            $value = response()->xml(['Libro' => $book[0]->toArray()]);
        }

        return $value;
    }

    public function destroy($isbn)
    {
        //Obteniendo libro con base en ISBN
        $book = Book::where('isbn', $isbn)->delete();

        //Determinado si el libro existe y crendo repuesta
        if ($book == 0) {
            $value = response()->json(['Status' => 'Libro no existe']);
        }
        else {
            //Determinando si se uso API o interfaz de usuario
            if (strpos(url()->previous(), 'list') == true) {
                $value = redirect('/list');
            }
            else {
                $value = response()->json(['Status' => 'Exito']);
            }            
        }  
        
        return $value;
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
