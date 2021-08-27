<form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="d-inline mx-1">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{$message->id}}">
        <i class="fas fa-trash-alt"></i>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Удалить это сообщение?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</form>