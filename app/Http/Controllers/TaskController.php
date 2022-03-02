<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tsk_row' => 'nullable|int|min:0',
            'tsk_name' => 'nullable|string|max:200',
            'tsk_code' => 'nullable|string|max:20',
            'tsk_level' => 'required|int|min:0',
            'tsk_start_at' => 'required|date_format:Y-m-d',
            'tsk_end_at' => 'required|date_format:Y-m-d|after_or_equal:tsk_start_at',
            'tsk_start_ms' => 'required|boolean',
            'tsk_end_ms' => 'required|boolean',
            'tsk_status' => 'required|string|in:STATUS_ACTIVE,STATUS_DONE,STATUS_FAILED,STATUS_SUSPENDED,STATUS_WAITING,STATUS_UNDEFINED',
            'tsk_relevance' => 'required|int|min:0',
            'tsk_duration' => 'required|int|min:1',
            'tsk_progress' => 'required|numeric|between:0,100',
            'tsk_description' => 'nullable|string|max:500'
        ], $this->validationErrorMessages());

        $tasks = session('tasks', []);
        $new = [
            'id' => $request->tsk_id,
            'code' => $request->tsk_code,
            'name' => $request->tsk_name,
            'start_at' => $request->tsk_start_at,
            'end_at' => $request->tsk_end_at,
            'start_ms' => $request->tsk_start_ms,
            'end_ms' => $request->tsk_end_ms,
            'status' => $request->tsk_status,
            'progress' => $request->tsk_progress,
            'description' => $request->tsk_description,
            'relevance' => $request->tsk_relevance,
            'duration' => $request->tsk_duration,
            'level' => $request->tsk_level
        ];

        if (substr($request->tsk_id,0,4) === 'tmp_') {
            if ($request->tsk_row)
                array_splice($tasks, $request->tsk_row, 0, [$new]);
            else
                $tasks[] = $new;
        }

        session(['tasks' => $tasks]);
        return json_encode($tasks);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tasks = session('tasks', []);

        if ($id < 0 || count($tasks) <= $id)
            return response()->json(['success' => 'false', 'errors' => 'El índice ingresado es inválido.'], 400);
        
        $item = $tasks[$id];
        $task = [
            'id' => $item['id'],
            'code' => $item['code'],
            'name' => $item['name'],
            'start_at' => $item['start_at'],
            'end_at' => $item['end_at'],
            'start_ms' => $item['start_ms'],
            'end_ms' => $item['end_ms'],
            'status' => $item['status'],
            'progress' => $item['progress'],
            'description' => $item['description'],
            'relevance' => $item['relevance'],
            'duration' => $item['duration'],
            'level' => $item['level']
        ];
        return json_encode($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tsk_name' => 'nullable|string|max:200',
            'tsk_code' => 'nullable|string|max:20',
            'tsk_level' => 'required|int|min:0',
            'tsk_start_at' => 'required|date_format:Y-m-d',
            'tsk_end_at' => 'required|date_format:Y-m-d|after_or_equal:tsk_start_at',
            'tsk_start_ms' => 'required|boolean',
            'tsk_end_ms' => 'required|boolean',
            'tsk_status' => 'required|string|in:STATUS_ACTIVE,STATUS_DONE,STATUS_FAILED,STATUS_SUSPENDED,STATUS_WAITING,STATUS_UNDEFINED',
            'tsk_relevance' => 'required|int|min:0',
            'tsk_duration' => 'required|int|min:1',
            'tsk_progress' => 'required|numeric|between:0,100',
            'tsk_description' => 'nullable|string|max:500'
        ], $this->validationErrorMessages());

        $tasks = session('tasks', []);

        if ($id < 0 || count($tasks) <= $id)
            return response()->json(['success' => 'false', 'errors' => 'El índice ingresado es inválido.'], 400);
        
        $tasks[$id] = [
            'id' => $request->tsk_id,
            'code' => $request->tsk_code,
            'name' => $request->tsk_name,
            'start_at' => $request->tsk_start_at,
            'end_at' => $request->tsk_end_at,
            'start_ms' => $request->tsk_start_ms,
            'end_ms' => $request->tsk_end_ms,
            'status' => $request->tsk_status,
            'progress' => $request->tsk_progress,
            'description' => $request->tsk_description,
            'relevance' => $request->tsk_relevance,
            'duration' => $request->tsk_duration,
            'level' => $request->tsk_level
        ];
        session(['tasks' => $tasks]);
        return json_encode($tasks);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasks = session('tasks', []);

        if ($id < 0 || count($tasks) <= $id)
            return response()->json(['success' => 'false', 'errors' => 'El índice ingresado es inválido.'], 400);
        
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        session(['tasks' => $tasks]);
        return json_encode($tasks);
    }

    /**
     * Move up the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveUp(Request $request, $id)
    {
        $tasks = session('tasks', []);

        if ($id < 0 || count($tasks) <= $id)
            return response()->json(['success' => 'false', 'errors' => 'El índice ingresado es inválido.'], 400);
        
        if ($id == 0)
            return response()->json(['success' => 'false', 'errors' => 'No puedes mover la totalidad del proyecto.'], 400);

        if ($tasks[$id - 1]['level'] < $tasks[$id]['level'])
            return response()->json(['success' => 'false', 'errors' => 'No puedes intercambiar tareas de diferente profundidad.'], 400);

        $prv = self::prevBrother($tasks, $id);
        $fam = self::getFamily($tasks, $id);
        array_splice($tasks, $prv, 0, $fam);
        
        session(['tasks' => $tasks]);
        return json_encode($tasks);
    }

    /**
     * Move down the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveDown(Request $request, $id)
    {
        $tasks = session('tasks', []);

        if ($id < 0 || count($tasks) <= $id)
            return response()->json(['success' => 'false', 'errors' => 'El índice ingresado es inválido.'], 400);
        
        if ($id == 0)
            return response()->json(['success' => 'false', 'errors' => 'No puedes mover la totalidad del proyecto.'], 400);

        $nxt = self::nextBrother($tasks, $id);
    
        if (!$nxt)
            return response()->json(['success' => 'false', 'errors' => 'No puedes mover más abajo la tarea seleccionada.'], 400);
        
        $fam = self::getFamily($tasks, $id);
        array_splice($tasks, $nxt, 0, $fam);
        
        session(['tasks' => $tasks]);
        return json_encode($tasks);
    }

    protected function prevBrother($tasks, $id)
    {
        $level = $tasks[$id]['level'];
        $prv = 0;
        foreach ($tasks as $index => $task) {
            if ($index == $id)
                return $prv;
            if ($task['level'] == $level)
                $prv = $index;
        }
        return $prv;
    }

    protected function getFamily(&$tasks, $id)
    {
        $level = $tasks[$id]['level'];
        $fam = [$tasks[$id]];
        unset($tasks[$id]);
        foreach ($tasks as $index => $task) {
            if ($index > $id) {
                if ($task['level'] > $level) {
                    $fam[] = $task;
                    unset($tasks[$index]);
                }
                else return $fam;
            }
        }
        return $fam;
    }

    protected function nextBrother($tasks, $id)
    {
        $level = $tasks[$id]['level'];
        foreach ($tasks as $index => $task) {
            if ($index > $id) {
                if ($task['level'] == $level)
                    return $index;
                else if ($task['level'] < $level) 
                    return null;
            }
        }
        return null;
    }

    /**
     * Get the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByParent($id)
    {
        $parent = Task::find($id);

        if (!$parent)
            return response()->json(['success' => 'false', 'errors' => 'La tarea solicitada no ha sido encontrada.'], 400);
        
        $tasks = [];
        foreach ($parent->project->tasks as $task)
            if ($task->row > $parent->row) {
                if ($task->level <= $parent->level)
                    break;
                if ($task->level == $parent->level + 1)
                    $tasks[] = [
                        'id' => $task->id,
                        'name' => $task->code.' '.$task->name
                    ];
            }
        return $tasks;
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'tsk_row.int' => 'La fila ingresada no tiene un formato válido.',
            'tsk_row.min' => 'La fila debe ser un entero no negativo.',

            'tsk_name.required' => 'Debes ingresar obligatoriamente un nombre.',
            'tsk_name.max' => 'El nombre debe tener un máximo de doscientos (200) caracteres.',

            'tsk_code.max' => 'El código debe tener un máximo de veinte (20) caracteres.',

            'tsk_start_at.required' => 'Debes ingresar obligatoriamente una fecha inicio.',
            'tsk_start_at.date_format' => 'La fecha inicio ingresada no tiene un formato válido.',

            'tsk_end_at.required' => 'Debes ingresar obligatoriamente una fecha fin.',
            'tsk_end_at.date_format' => 'La fecha fin ingresada no tiene un formato válido.',
            'tsk_end_at.after_or_equal' => 'La fecha inicio no puede ser posterior a la fecha fin.',

            'tsk_start_ms.required' => 'Debes indicar obligatoriamente si la fecha inicio es un hito.',
            'tsk_start_ms.boolean' => 'El indicador si la fecha inicio es un hito no tiene un formato válido.',

            'tsk_end_ms.required' => 'Debes indicar obligatoriamente si la fecha fin es un hito.',
            'tsk_end_ms.boolean' => 'El indicador si la fecha fin es un hito no tiene un formato válido.',

            'tsk_status.required' => 'Debes ingresar obligatoriamente un estado.',
            'tsk_status.in' => 'El estado ingresado no es válido.',

            'tsk_progress.required' => 'Debes ingresar obligatoriamente un progreso.',
            'tsk_progress.numeric' => 'El progreso ingresado no tiene un formato válido.',
            'tsk_progress.between' => 'El progreso debe estar comprendido entre cero (0) y cien (100).',

            'tsk_description.max' => 'La descripción debe tener un máximo de quinientos (500) caracteres.',

            'tsk_relevance.required' => 'Debes ingresar obligatoriamente una prioridad.',
            'tsk_relevance.int' => 'La prioridad ingresada no tiene un formato válido.',
            'tsk_relevance.min' => 'La prioridad debe ser un entero no negativo.',

            'tsk_duration.required' => 'Debes ingresar obligatoriamente una duración.',
            'tsk_duration.int' => 'La duración ingresada no tiene un formato válido.',
            'tsk_duration.min' => 'La duración debe ser un entero positivo.',

            'tsk_level.required' => 'Debes ingresar obligatoriamente un nivel.',
            'tsk_level.int' => 'El nivel ingresado no tiene un formato válido.',
            'tsk_level.min' => 'El nivel debe ser un entero no negativo.',
        ];
    }
}