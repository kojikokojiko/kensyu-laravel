@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Edit Article</h1>
        <!-- エラーメッセージの表示 -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('articles.update', $article->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $article->title }}">
                @if ($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="body">Body:</label>
                <textarea class="form-control" id="body" name="body" rows="5" >{{ $article->body }}</textarea>
                @if ($errors->has('body'))
                    <span class="text-danger">{{ $errors->first('body') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="tags">Tags:</label>
                @foreach ($tags as $tag)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tag{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                               @if($article->tags->contains($tag->id)) checked @endif>
                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                @endforeach
                @if ($errors->has('tags'))
                    <span class="text-danger">{{ $errors->first('tags') }}</span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
