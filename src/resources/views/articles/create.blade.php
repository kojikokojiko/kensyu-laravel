@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Create Article</h1>

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
    <form action="{{ route('articles.create_article') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title">
            @if ($errors->has('title'))
                <span class="text-danger">{{ $errors->first('title') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="body">Body:</label>
            <textarea class="form-control" id="body" name="body" rows="5" ></textarea>
            @if ($errors->has('body'))
                <span class="text-danger">{{ $errors->first('body') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="thumbnail">Thumbnail:</label>
            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail">
            @if ($errors->has('thumbnail'))
                <span class="text-danger">{{ $errors->first('thumbnail') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="images">Images:</label>
            <input type="file" class="form-control-file" id="images" name="images[]" multiple>
            @if ($errors->has('images.*'))
                <span class="text-danger">{{ $errors->first('images.*') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label for="tags">Tags:</label>
            @foreach ($tags as $tag)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="tag{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}">
                    <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                </div>
            @endforeach
            @if ($errors->has('tags'))
                <span class="text-danger">{{ $errors->first('tags') }}</span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
