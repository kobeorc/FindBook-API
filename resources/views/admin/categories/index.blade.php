@extends('admin.layouts.index')

@section('content')

    <table class="table table-dark table-striped">
        <thead>
        <th scope="col">#</th>
        <th scope="col">name</th>
        <th scope="col"><a href="{{ route('category.create') }}" class="btn btn-info">Create New</a></th>
        <th>#</th>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id ?? '' }}</td>
                <td>{{ $category->name ?? ''}}</td>
                <td><a href="{{ route('category.edit',['categoryId'=>$category->id]) }}" class="btn btn-light">update</a></td>
                <td>
                    @if($category->deleted_at === null)
                        <form action="{{ route('category.destroy',['categoryId'=>$category->id]) }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="delete" />
                            <input class="btn btn-danger" type="submit" value="delete" />
                        </form>
                    @elseif($category->deleted_at !== null)
                        <form action="{{ route('category.restore',['categoryId'=>$category->id]) }}" method="post">
                            {{ csrf_field() }}
                            <input class="btn btn-success" type="submit" value="restore" />
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        {{ $categories->links() }}
    </div>
@endsection
