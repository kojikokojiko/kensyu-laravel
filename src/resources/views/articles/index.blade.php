@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Articles</h1>
        <a href="{{ route('articles.get_create_page') }}" class="btn btn-primary mb-3">Create New Article</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Body</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>
                        @if ($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail->path) }}" alt="Thumbnail" width="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->body }}</td>
                    <td>
                        <a href="{{ route('articles.get_detail_page', $article->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('articles.get_edit_page', $article->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('articles.delete_article', $article->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
