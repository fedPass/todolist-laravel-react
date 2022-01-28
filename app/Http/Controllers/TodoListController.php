<?php

namespace App\Http\Controllers;

//Convert the object to its JSON representation
use Illuminate\Contracts\Support\Jsonable;

use App\Models\TodoList;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // se ti arriva dalla request un input per_page prendilo come limit per la paginazione 
        // altrimenti setta limit a 10
        $limit = $request->per_page ?? 10;
        return TodoList::select(['id','name','user_id'])
                        ->orderBy('id','DESC')
                        ->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTodoListRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //nuova istanza
        $list = new TodoList();
        //nome nuova lista = name in arrivo da request
        $list->name = $request->name;
        //TODO: leggere user_id dalla sessione, perora lo uso statico
        $list->user_id = 1;
        //salva
        $res = $list->save();
        //faccio return utilizzando la private function che ho creato per strutturare la risposta
        //data è la lista creata, success lo ricevo dal salvataggio, message da mostrare
        return $this->getResult($list, $res, 'Lista creata');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $list)
    {
        //faccio return usando getResult con il todoList della request
        //success sarà 1 perchè se non esiste mi restituisce 404
        return $this->getResult($list, 1, 'Lista numero '.$list->id); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTodoListRequest  $request
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TodoList $list)
    {
        //aggiornare il nome della lista
        $list->name = $request->name;
        $res = $list->save();
        return $this->getResult($list, $res, 'Lista aggiornata');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $list,Request $request)
    {
        //prevedo che la possibilità di effettuare una soft o force Delete
        //se nella request è presente forceDelete=1
        if($request->forceDelete){
            //con la private func forceDestroy elimina fisicamente
        }
        //altrimenti softDelete
        $res = $list->delete();
        return $this->getResult($list, $res, 'Lista eliminata');
    }

    //creo una funzione privata per strutturare una risposta più leggibile dei dati
    private function getResult(Jsonable $data,$success=true,$message='') {
        return [
            'data'=> $data,
            'success' => $success,
            'message' => $message
        ];
    }
}
