<?php

namespace App\Http\Controllers;

//Convert the object to its JSON representation
use Illuminate\Contracts\Support\Jsonable;

use App\Models\Todo;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //recupero dalla request list id e limit se presenti
        $list_id = $request->list_id ?? 1;
        $limit = $request->per_page ?? 10;
        return Todo::select(['id','name','list_id'])
                    //recupero i todos di una lista specifica
                    ->where('list_id',$list_id)
                    ->orderBy('id','DESC')
                    ->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTodoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo();
        $todo->name = $request->name;
        $todo->list_id = $request->list_id;
        //$todo->completed = $request->completed;
        $todo->duedate = $request->dueDate ?? Carbon::now();
        $res = $todo->save();
        return $this->getResult($todo,$res,'Todo creato');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return $this->getResult($todo,1,'Todo numero '.$todo->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTodoRequest  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //verifico quale campo mi viene inviato nella request
        //aggiorno di conseguenza oppure lascio il valore prefissato
        $todo->name = $request->name ?? $todo->name;
        $todo->duedate = $request->duedate ?? $todo->duedate;
        $todo->list_id = $request->list_id ?? $todo->list_id;
        //$todo->completed = $request->completed;
        $res = $todo->save();
        return $this->getResult($todo,$res,'Todo aggiornato');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */

    //modo alternativo (a quello usato in TodoListContr) per gestire sia la soft che force delete
    public function destroy(Todo $todo, Request $request)
    {
        $res = $request->forceDelete ? $todo->forceDelete() : $todo->delete();
        $message = $request->forceDelete? 'Todo eliminato definitivamente!' : 'Todo eliminato';
        return $this->getResult($todo, $res, $message);
    }

    //funzione privata per strutturare una risposta più leggibile dei dati
    private function getResult(Jsonable $data,$success=true,$message='') {
        return [
            'data'=> $data,
            'success' => $success,
            'message' => $message
        ];
    }
}
