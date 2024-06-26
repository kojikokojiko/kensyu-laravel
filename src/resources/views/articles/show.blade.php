@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>{{ $article->title }}</h1>
        <p>{{ $article->body }}</p>

        <!-- 投稿者情報の表示 -->
        <div class="mb-4">
            <h5>Posted by: {{ $article->user->name }}</h5>
            @if ($article->user->profile_image)
                <img src="{{ asset('storage/' . str_replace('public/', '', $article->user->profile_image)) }}" alt="Profile Image" class="img-fluid" style="max-width: 100px; border-radius: 50%;">
            @endif
        </div>
        <!-- サムネイル画像の表示 -->
        @if ($article->thumbnail)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $article->thumbnail->path) }}" alt="Thumbnail" class="img-fluid">
            </div>
        @endif
        <!-- 本文中の画像の表示 -->
        @if ($article->images)
            <div class="row">
                @foreach ($article->images as $image)
                    <div class="col-md-4 mb-4">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="img-fluid">
                    </div>
                @endforeach
            </div>
        @endif

        <!-- タグの表示 -->
        @if ($article->tags)
            <div class="mt-3">
                <h5>Categories:</h5>
                <ul>
                    @foreach ($article->tags as $tag)
                        <li>{{ $tag->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('home') }}" class="btn btn-primary">Back to Articles</a>
    </div>
@endsection
