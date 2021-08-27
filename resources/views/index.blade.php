@extends('_layouts._layout')

@section('form')
<form action="{{ route('messages.store') }}" method="POST" id="id-form_messages" class="border border-left-0 border-right-0 py-4 my-4">
    
@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('sessionMessage'))
<div class="alert alert-success" role="alert">
    {{ session('sessionMessage') }}
</div>
@endif


@csrf
    {{-- @method('PUT') --}}
    <div class="form-group">
        <label for="name">Имя: *</label>
        <input class="form-control" placeholder="Имя" name="name" value="{{ old('name') }}" type="text" id="name">
    </div>

    <div class="form-group">
        <label for="message">Сообщение: *</label>
        <textarea class="form-control" rows="5" placeholder="Тект сообщения" name="message" cols="50" id="message">{{ old('message') }}</textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="Добавить"/>
    </div>
</form>
@endsection


@section('messages_count')
<div class="text-right mb-4"><b>Всего сообщений:</b> <i class="badge badge-secondary">{{ $countOfMessages }}</i></div>
@endsection


@section('messages_section')
<section class="messages mb-4">

    @foreach ($allMessages as $oneMessage)
    <a name='{{ $oneMessage->id }}'></a>
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <a href="{{ route('messages.show', $oneMessage->id) }}">
                        #{{ $oneMessage->id }} {{ $oneMessage->name }}
                    </a>
                </div>
                <div class="col text-right label label-info">{{ $oneMessage->created_at }}<!-- 17:11:11 / 01.02.2001 --></div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-10 col-lg-11 border border-left-0 border-bottom-0 border-top-0">
                    {{ $oneMessage->message }}
                </div>
                <div class="col-2 col-lg-1 text-center">
                    <a class="btn btn-info mb-3" href="{{ route('messages.edit', $oneMessage->id) }}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>

                    @include('_parts._deleteButtonConfirmation', array('message' => $oneMessage))

                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{ $allMessages->render() }}

</section>
@endsection