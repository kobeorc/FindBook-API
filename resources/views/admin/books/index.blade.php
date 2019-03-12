@extends('admin.layouts.index')

@section('content')
    <table class="table table-dark table-striped">
        <thead>
        <th scope="col">#</th>
        <th scope="col">name</th>
        <th scope="col">description</th>
        <th scope="col">year</th>
        <th scope="col">owner</th>
        <th scope="col">position</th>
        <th scope="col"></th>
        </thead>
        <tbody>
        @foreach($books as $book)
            <tr>
                <td>{{ $book->id ?? '' }}</td>
                <td>{{ $book->name ?? ''}}</td>
                <td>{{ $book->description ?? '' }}</td>
                <td>{{ $book->year ?? '' }}</td>
                <td>{{ $book->users->first()->name }}</td>
                <td>{{ $book->latitude. ' '. $book->longitude }}</td>
                <td><a href="{{ route('books.edit',['bookId'=>$book->id]) }}" class="btn btn-light">UPDATE</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        {{ $books->links() }}
    </div>
@endsection