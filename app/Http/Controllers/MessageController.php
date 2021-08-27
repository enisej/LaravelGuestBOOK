<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Message; // Model Message
use Validator; // Validator

class MessageController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array(
            'title' => 'Гостевая книга на Laravel',
            'metaTitlePrefix' => 'Обзор сообщений. ',
            'countOfMessages' => Message::count(),
            'allMessages' => Message::latest()->paginate(5),
        );
        return view('index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * /
      
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Из всего $request сохраняем в виде архива только те данные, которые мы послали через формуляр.
        $input = $request->all();
        // Проверяем полученные данные
        $validationResult = $this->_validation($input);

        // Если данные прошли проверку, тогда в $validationResult будет NULL.
        if (!is_null($validationResult)) {
            // Если данные не прошли проверку, тогда в $validationResult находится заполненый Response с адресом "редиректа" и ошибками.
            return $validationResult;
        } // if
        // Создаём новый объект Message
        $message = new Message();
        // Заполняем объект Message данными
        $message->name = $input['name'];
        $message->message = $input['message'];

        // Сохраняем новые данные в базе данных
        if ($message->save()) {
            return redirect()
                            ->route('messages')
                            ->with('sessionMessage', 'Запись добавлена.');
        } // if
        // Если в процессе записи нового объекта произойдёт ошибка, отобразим ошибку 500.
        abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message) {
        // Поиск name предыдущей записи
        $previus = Message::where('name', '<', $message->name) // только те записи, у которых name меньше чем у нынешней.
                ->select('name') // лишь поле name
                ->orderby('name', 'desc') // сортировка данных по убыванию содержания ячейки 'name'
                ->first(); // найти лишь первый элемент (эта же команда выполняет запрос в базу данных.
        $previusID = ($previus != NULL) ? $previus->name : NULL; // Проверка. У первой записи нет предыдущей.
       
        $next = Message                             // from `messages`
                ::where('name', '>', $message->name)    // where `name` > $message->id
                ->select('name')                      // select `name`
                ->orderby('name', 'asc')              // order by `name` asc
                ->first();                          // limit 1, а так же отправляет запрос базе данных, должно быть последним.
        $nextID = ($next != NULL) ? $next->name : NULL;

        // объединяем данные в один массив, чтобы их передать шаблонам.
        $data = array(
            'title' => 'Гостевая книга на Laravel <br> Просмотр сообщения.',
            'metaTitlePrefix' => 'Просмотр одного сообщения. ',
            'message' => $message,
            'previusName' => $previusName,
            'nextName' => $nextName
        );
        // Обрабатываем шаблоны и заканчиваем работу
        return view('show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message) {
        // объединяем данные в один массив, чтобы их передать шаблонам.
        $data = array(
            'title' => 'Исправление сообщения.',
            'metaTitlePrefix' => 'Исправление сообщения. ',
            'message' => $message,
        );
        // Обрабатываем шаблоны и заканчиваем работу
        return view('edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message) {
        // Из всего $request сохраняем в виде архива только те данные, которые мы послали через формуляр.
        $input = $request->all();
        // Проверяем полученные данные
        $validationResult = $this->_validation($input, $message->id);

        // Если данные прошли проверку, тогда в $validationResult будет NULL.
        if (!is_null($validationResult)) {
            // Если данные не прошли проверку, тогда в $validationResult находится заполненый Response с адресом "редиректа" и ошибками.
            return $validationResult;
        } // if
        // Заполняем объект Message данными
        $message->name = $input['name'];
        $message->message = $input['message'];

        // Сохраняем новые данные в базе данных
        if ($message->save()) {
            return redirect()
                            ->route('messages', array('#' . $message->id))
                            ->with('sessionMessage', 'Запись изменена.');
        } // if
        // Если в процессе записи нового объекта произойдёт ошибка, отобразим ошибку 500.
        abort(500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message) {
        $message->delete();
        return redirect()
                        ->route('messages')
                        ->with('sessionMessage', 'Запись удалена.');
    }

    /**
     * Проверяет данные, которые были введены в форму.$message
     * 
     * Если данные не проходят валидацию, то в выходной массив добавляется поле 'response'.
     *
     * @param  array  $input
     * @param  int  $id - ID записи, которую мы меняем. Если не задан, то считается, что мы создаём новую запись.
     * @return \Illuminate\Http\Response
     */
    private function _validation($input, $id = NULL) {
        // Поскольку не загрузили файлы русской локализации, то вручную прописываем сообщение для ошибок.
        $validatorErrorMessages = array(
            'required' => 'Поле :attribute обязательно к заполнению',
        );

        // Проверяем данные
        $validator = Validator::make(
                        $input, // Данные, которые мы получили из формы
                        array(// Описываем требования к данным
                            'name' => 'required|max:255',
                            'message' => 'required',
                        ),
                        $validatorErrorMessages); // Подключаем словарь с возможными ошибками.
        // Определяем прошла ли проверка удачно или нет.
        if ($validator->fails()) {
            // Проверка провалилась.
            // Теперь генерируем куда переадресовать страничку. Если $id задан, то мы обновляем уже существующие данные. Если же $id не задан, то мы создаём новую запись.
            $redirectURL = ($id == NULL) ?
                    route('messages.index') :
                    route('messages.edit', $id);

            // Подготавливаем "редирект"
            return redirect($redirectURL) // Куда
                            ->withErrors($validator) // Сообщения об ошибках
                            ->withInput(); // Введённые данные
        } // if
        // Проверка прошла удачно.
        return NULL;
    }

}