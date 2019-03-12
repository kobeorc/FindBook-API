@extends('admin.layouts.index')

@section('content')
    <table class="table table-dark table-striped">
        <thead>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col"></th>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id ?? '' }}</td>
                <td>{{ $user->name ?? ''}}</td>
                <td>{{ $user->email ?? ''}}</td>
                <td>{{ $user->role ?? '' }}</td>
                <td><a href="{{ route('users.edit',['userId'=>$user->id]) }}" class="btn btn-light">UPDATE</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection