@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>User Profile</h1>
        <div class="card">
            <div class="card-body">
                <h2>{{ $user->name }}</h2>
                <p>Email: {{ $user->email }}</p>
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="User_profile" width="100">
                <!-- 他のユーザー情報をここに追加 -->
            </div>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Back to Articles</a>
    </div>
@endsection
