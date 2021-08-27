@extends('_layouts._layout')

@section('form')
<a href="{{ route('messages') }}">Вернуться к просмотру всех сообщений</a>

<form action="{{ route('messages.update', $message->id) }}" method="POST" id="formid" class="border border-left-0 border-right-0 py-4 my-4">

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Имя: *</label>
        <input class="form-control" placeholder="Имя" name="name" value="{{ old('name') ?? $message->name }}" type="text" id="name">
    </div>

    <div class="form-group">
        <label for="message">Сообщение: *</label>
        <textarea class="form-control" rows="5" placeholder="Тект сообщения" name="message" cols="50" id="message">{{ old('message') ?? $message->message }}</textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="Обновить"/>
    </div>
</form>
@endsection



@section('jQueryInline')
<script type="text/javascript">
    // #formid
    $(window).on("beforeunload", function () {
        return "Are you sure? You didn't finish the form!";
    });
    $(document).ready(function () {
        $("#formid").on("submit", function (e) {
            //check form to make sure it is kosher
            //remove the ev
            $(window).off("beforeunload");
            return true;
        });
    });
</script>
@endsection