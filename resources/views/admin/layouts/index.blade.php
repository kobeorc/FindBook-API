@extends('admin.layouts.base')

@section('title','FindBook Admin')

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@endsection

@section('body')
    <div class="container-fluid">
        <div  class="row">
            <div class="col">
                <nav class="navbar navbar-dark bg-dark">
                    <a href="#" class="navbar-brand">
                        <img src="/logo.png" width="50px" height="50px" alt=""/>
                        FindBook AdminPanel
                    </a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{ route('users') }}" class="list-group-item list-group-item-action @if(request()->is('users*')) active @endif">Users <span class="badge badge-pill badge-dark">{{ \App\Models\User::where('role','!=',\App\Models\User::ROLE_GUEST)->count() }}</span> <span class="badge badge-pill badge-secondary">Guest {{ \App\Models\User::where('role','=',\App\Models\User::ROLE_GUEST)->count() }}</span></a>
                    <a href="{{ route('books') }}" class="list-group-item list-group-item-action @if(request()->is('books*')) active @endif ">Books <span class="badge badge-pill badge-dark">{{ \App\Models\Book::count() }}</span></a>
                    <a href="{{ route('category') }}" class="list-group-item list-group-item-action @if(request()->is('category*')) active @endif ">Category <span class="badge badge-pill badge-dark">{{ \App\Models\Category::count() }}</span></a>
                </div>
            </div>
            <div class="col-sm">
                @yield('content')
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection