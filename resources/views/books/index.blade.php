@extends('app')
@section('content')
    <div class=" col-md-8 col-sm-10 card mt-5">
        <div class="card-header d-flex justify-content-center">
            <h2>Lista de Libros</h2>
        </div>
        <div class="card-body">
            @foreach ($books as $book)
            <div class="row">
                <div class="col-3">
                    <img class="img-thumbnail w-100" src="{{$book->cover}}" alt="Book Cover">
                </div>
                <div class="col-4 offset-1">
                    <h3 class="mb-3">{{$book->title}}</h3>
                    <p id="ptest"><strong>Autores: </strong>{{$book->authors}}</p>
                    <p><strong>ISBN: </strong>{{$book->isbn}}</p>
                </div>
                <div class="col-1 offset-1">
                    <form action="/api/books/delete/{{$book->isbn}}" method="POST">
                        <button class="btn btn-sm btn btn-outline-danger mr-5" type="submit">Eliminar</button>
                    </form>
                    
                </div>
            </div>
            <hr>
            @endforeach  
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item {{$data->current_page == 1 ? 'disabled' : ''}}">
                        <a class="page-link" href="{{$data->prev_page_url}}">Anterior</a>
                    </li>
                    
                    <li class="page-item {{$data->current_page == $data->last_page ? 'disabled' : ''}}">
                        <a class="page-link" href="{{$data->next_page_url}}">Siguiente</a>
                    </li>
                </ul>
            </nav>          
        </div>
        
    </div>
@endsection