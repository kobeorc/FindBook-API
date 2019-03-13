@extends('admin.layouts.index')

@section('content')
    <form action="{{ route('users.update',['userId'=>$user->id]) }}" method="POST">
        @csrf
        <div>
            <fieldset disabled>
                <div class="form-group">
                    <label for="disabledId">ID</label>
                    <input class="form-control" id="disabledId" type="text" placeholder="{{ $user->id }}" disabled>
                </div>
                <div class="form-group">
                    <label for="disabledName">Name</label>
                    <input class="form-control" id="disabledName" type="text" placeholder="{{ $user->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="disabledEmail">Email</label>
                    <input class="form-control" id="disabledEmail" type="text" placeholder="{{ $user->email }}" disabled>
                </div>
                <div class="form-group">
                    <label for="disabledRole">Role</label>
                    <input class="form-control" id="disabledRole" type="text" placeholder="{{ $user->role }}" disabled>
                </div>
                <div class="form-group">
                    <label for="disabledStatus">Status</label>
                    <input class="form-control" id="disabledStatus" type="text" placeholder="{{ $user->status }}" disabled>
                </div>
            </fieldset>
            <div class="form-group">
                <label for="newPassword">Установить новый пароль</label>
                <input class="form-control" type="text" id="newPassword" name="newPassword" />
            </div>
            <button type="submit" class="btn btn-primary">PUSH</button>
        </div>
    </form>
    <hr/>
    <div class="col">
        <label for="bookList">Книги <span class="badge badge-info">{{ $user->inventory()->count() }}</span></label>
        @foreach($user->inventory as $book)
            <ul class="list-group" id="bookList">
                <li class="list-group-item">#{{ $book->id }}: {{ $book->name ?? '' }}</li>
            </ul>
        @endforeach
    </div>
@endsection