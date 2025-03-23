@extends('layouts.app')

@section('content')
<div class="container">
    <h2>メッセージを編集</h2>

    <form action="{{ route('messages.update', $message->id) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea name="content" rows="5" style="width:100%;">{{ old('content', $message->content) }}</textarea>

        <div style="margin-top: 10px;">
            <button type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection