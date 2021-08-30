<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
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

        
        $input = $request->all();
        $validationResult = $this->_validation($input);
        
        
        if (!is_null($validationResult)) {
           
            return $validationResult;
        } // if



        $message = new Message();
        
        $message->name = $input['name'];
        $message->message = $input['message'];
        $message->email = $input['email'];
        $message->yourwebsite = $input['yourwebsite'];
        
        if ($message->save()) {
            return redirect()
                            ->route('messages')
                            ->with('sessionMessage', 'Запись добавлена.');
        } // if
        
        abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message) {
        
        $previus = Message::where('name', '<', $message->name)  
                ->select('name')  
                ->orderby('name', 'desc')  
                ->first(); 
        $previusID = ($previus != NULL) ? $previus->name : NULL; // 
       
        $next = Message                             // from `messages`
                ::where('name', '>', $message->name)    // where `name` > $message->id
                ->select('name')                      // select `name`
                ->orderby('name', 'asc')              // order by `name` asc
                ->first();                          
        $nextID = ($next != NULL) ? $next->name : NULL;

       
        $data = array(
            'title' => 'Гостевая книга на Laravel <br> Просмотр сообщения.',
            'metaTitlePrefix' => 'Просмотр одного сообщения. ',
            'message' => $message,
            'previusName' => $previusName,
            'nextName' => $nextName
        );
        
        return view('show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message) {
        
        $data = array(
            'title' => 'Исправление сообщения.',
            'metaTitlePrefix' => 'Исправление сообщения. ',
            'message' => $message,
        );
        
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
        
        $input = $request->all();
        $validationResult = $this->_validation($input, $message->id);
        if (!is_null($validationResult)) {
            
            return $validationResult;
        } // if
        $message->name = $input['name'];
        $message->message = $input['message'];
        

       
        if ($message->save()) {
            return redirect()
                            ->route('messages', array('#' . $message->id))
                            ->with('sessionMessage', 'Запись изменена.');
        } // if
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
     * @param  array  $input
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function _validation($input, $id = NULL) {
        
        $validatorErrorMessages = array(
            'required' => 'Поле :attribute обязательно к заполнению',
        );

       
        $validator = Validator::make(
                        $input, 
                        array(
                            'name' => 'required|max:255',
                            'message' => 'required',
                            'email' => 'required|max:255',
                        ),
                        $validatorErrorMessages);
        
        if ($validator->fails()) {
            $redirectURL = ($id == NULL) ?
                    route('messages.index') :
                    route('messages.edit', $id);

           
            return redirect($redirectURL) 
                            ->withErrors($validator) 
                            ->withInput(); 
        } // if
        return NULL;
    }

}