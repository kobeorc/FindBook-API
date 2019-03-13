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
                <td><a href="{{ route('users.edit',['userId'=>$book->users->first()->id]) }}">{{ $book->users->first()->name }}</a></td>
                <td>{{ $book->latitude. ' '. $book->longitude }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        {{ $books->links() }}
    </div>
@endsection