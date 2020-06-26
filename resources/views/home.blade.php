@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Media Pons Chat</div>

                <div id="app" class="card-body chat-card-body">
                    <chat-app :user="{{ Auth::user() }}"></chat-app>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
