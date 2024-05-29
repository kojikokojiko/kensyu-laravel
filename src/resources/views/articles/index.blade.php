@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Articles</h1>
        <a href="{{ route('articles.create') }}" class="btn btn-primary mb-3">Create New Article</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Body</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>
                        @if ($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail->url) }}" alt="Thumbnail" width="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->body }}</td>
                    <td><a href="{{ route('users.show', $article->user->id) }}">{{ $article->user->name }}</a></td>
                    <td>
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display:inline;">
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
