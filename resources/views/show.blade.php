@extends('_layouts._layout')


@section('content')
@if (session('sessionMessage'))
<div class="alert alert-success" role="alert">
    {{ session('sessionMessage') }}
</div>
@endif


<a href="{{ route('messages') }}">Вернуться к просмотру всех сообщений</a>


<div class="card my-5">
    <div class="card-header">
        <div class="row">
            <div class="col">Сообщение от <strong>{{ $message->name }}</strong></div>
            <div class="col text-right">№ сообщения: {{ $message->id }}</div>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">Текст сообщения:</h5>
        <p class="card-text border border-left-0 border-right-0 border-top-0 mb-3 pb-3">{{ $message->message }}</p>

        <div class="text-center">
            @if ($previusID != NULL)
            <a class="btn btn-info mx-1" href="{{ route('messages.show', $previusID) }}" title="предыдущее сообщение">
                <i class="fas fa-arrow-left"></i>
            </a>
            @endif

            <a class="btn btn-info mx-1" href="{{ route('messages.edit', $message->id) }}">
                <i class="fas fa-pencil-alt"></i>
            </a>

            @include('_parts._deleteButtonConfirmation')

            @if ($nextID != NULL)
            <a class="btn btn-info mx-1" href="{{ route('messages.show', $nextID) }}" title="следующее сообщение">
                <i class="fas fa-arrow-right"></i>
            </a>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col label label-info">Создано: {{ $message->created_at }}</div>
            <div class="col text-right label label-info">
                @if ($message->created_at != $message->updated_at)
                Обновлено: {{ $message->updated_at }}
                @else
                Изменений не было.
                @endif
            </div>
        </div>
    </div>
</div>
@endsection