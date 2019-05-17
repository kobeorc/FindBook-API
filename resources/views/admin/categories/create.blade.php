@extends('admin.layouts.index')

@section('content')
    <form action="{{ route('category.store') }}" method="POST">
        {{ csrf_field() }}
        <input name="name"/>
        <input type="submit"/>
    </form>
@endsection