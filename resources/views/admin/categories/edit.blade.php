@extends('admin.layouts.index')

@section('content')
    <form action="{{ route('category.update',['categoryId'=>$category->id]) }}" method="POST">
        {{ csrf_field() }}
        <input name="name" value="{{ $category->name ?? '' }}">
        <input type="submit"/>
    </form>
@endsection