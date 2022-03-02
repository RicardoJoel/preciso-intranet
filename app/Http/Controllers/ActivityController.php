<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Customer;
use App\Parameter;
use App\Project;
use App\User;
use Carbon\Carbon;
use Auth;
use DB;
/* Export data */
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dates = Parameter::where('name','DATES')->get()->first()->value;
        $items = Activity::where('user_id',Auth::user()->id)->orderBy('start_at','ASC')->get();
        $activities = [];
        foreach ($items as $item) {
            $activities[] = [
                'id' => $item->id,
                'project' => $item->project,
                'start_at' => $item->start_at,
                'end_at' => $item->end_at,
                'description' => $item->description,
                'comment' => $item->comment,
                'color' => $item->color,
            ];
        }
        return view('activities.timesheet', compact('dates','activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activities.create');
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
            'project_id' => 'required|int|min:1',
            'task_id' => 'required|int|min:1',
            'start_at' => 'required|date|date_format:Y-m-d H:i:s|before:end_at',
            'end_at' => 'required|date|date_format:Y-m-d H:i:s|after:start_at',
            'description' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
            'color' => 'nullable|string|size:7',
        ]);
        return Activity::create($request->all() + ['user_id' => Auth::user()->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::find($id);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Activity::find($id);
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int|min:1',
            'project_id' => 'required|int|min:1',
            'task_id' => 'required|int|min:1',
            'start_at' => 'required|date|date_format:Y-m-d H:i:s|before:end_at',
            'end_at' => 'required|date|date_format:Y-m-d H:i:s|after:start_at',
            'description' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
            'color' => 'nullable|string|size:7',
        ]);
        return Activity::find($request->id)->update($request->all() + ['user_id' => Auth::user()->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Activity::find($id)->delete();
    }

    public function projects(Request $request)
    {
        $customer_id = $request->customer_id;

        $projects = Project::distinct()
        ->select(['id','name','code'])
        ->where('code','not like','OTR000000000%')
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('customer_id',$customer_id);
            return $query;
        })
        ->orderByRaw('name','code')
        ->get();
        
        $others = Project::distinct()
        ->select(['id','name','code'])
        ->where('code','like','OTR000000000%')
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('customer_id',$customer_id);
            return $query;
        })
        ->orderByRaw('name','code')
        ->get();

        $array = [];
        foreach ($projects as $project)
            $array[] = [
                'id' => $project->id,
                'name' => $project->nameCode
            ];
        foreach ($others as $other)
            $array[] = [
                'id' => $other->id,
                'name' => $other->name
            ];
        return json_encode($array);
    }

    public function status(Request $request)
    {
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;

        $status = Project::distinct()
        ->select([
            DB::raw('status as code'),
            DB::raw('if(status="O","Abierto",if(status="C","Cancelado","Cerrado")) as name')
        ])
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('id',$project_id);
            return $query;
        })
        ->orderByDesc('status')
        ->get();

        $array = [];
        foreach ($status as $stat)
            $array[] = [
                'id' => $stat->code,
                'name' => $stat->name
            ];
        return json_encode($array);
    }

    public function users(Request $request)
    {
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $status = $request->status;

        $users = Activity::distinct()
        ->select(['users.id','users.name','users.lastname'])
        ->leftJoin('users','users.id','activities.user_id')
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('customers','customers.id','projects.customer_id')
        ->where('activities.user_id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->orderByRaw('users.lastname','users.name')
        ->get();

        $array = [];
        foreach ($users as $user) {
            $array[] = [
                'id' => $user->id,
                'name' => $user->lastname.', '.$user->name
            ];
        }
        return json_encode($array);
    }

    public function report()
    {
        $customer_id = $project_id = $user_id = $start_at = $end_at = $status = $title = '';
        $items = [];

        return view('activities.report', compact('items','title','customer_id','project_id','user_id','start_at','end_at','status'));
    }

    public function generate(Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());
        
        ini_set('memory_limit','128M');
        
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $user_id = $request->user_id;
        $status = $request->status;
        $start_at = Carbon::parse($request->start_at);
        $end_at = Carbon::parse($request->end_at);
        $title = 'Reporte de actividades realizadas '.($start_at == $end_at ? 'el '.$start_at->format('d/m/Y') : 'entre el '.$start_at->format('d/m/Y').' y el '.$end_at->format('d/m/Y'));

        $items = Activity::select([
            DB::raw('concat(customers.name," (",customers.code,")") as customer'),
            DB::raw('concat(projects.name," (",projects.code,") [",if(projects.status="O","Abierto",if(projects.status="F","Cerrado","Cancelado")),"]") as project'), 
            DB::raw('concat(users.lastname,", ",users.name) as user'),
            DB::raw('customers.id as customer_id'),
            DB::raw('projects.id as project_id'),
            DB::raw('users.id as user_id'),
            DB::raw('sum(TIMESTAMPDIFF(MINUTE,activities.start_at,activities.end_at)) as minutes')
        ])
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('customers','customers.id','projects.customer_id')
        ->leftJoin('users','users.id','activities.user_id')
        ->where('activities.user_id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('activities.user_id',$user_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->where(function ($query) use ($start_at) {
            if ($start_at)
                $query->where('activities.start_at','>=',$start_at);
            return $query;
        })
        ->where(function ($query) use ($end_at) {
            if ($end_at)
                $query->where('activities.end_at','<=',$end_at->tomorrow());
            return $query;
        })
        ->groupBy([
            DB::raw('concat(customers.name," (",customers.code,")")'), 
            DB::raw('concat(projects.name," (",projects.code,") [",if(projects.status="O","Abierto",if(projects.status="F","Cerrado","Cancelado")),"]")'), 
            DB::raw('concat(users.lastname,", ",users.name)'),
            DB::raw('customers.id'),
            DB::raw('projects.id'),
            DB::raw('users.id')
        ])
        ->orderByRaw(
            DB::raw('concat(customers.name," (",customers.code,")")'), 
            DB::raw('concat(projects.name," (",projects.code,") [",if(projects.status="O","Abierto",if(projects.status="F","Cerrado","Cancelado")),"]")'), 
            DB::raw('concat(users.lastname,", ",users.name)'),
            DB::raw('customers.id'),
            DB::raw('projects.id'),
            DB::raw('users.id')
        )
        ->get();
        
        return view('activities.report', compact('items','title','customer_id','project_id','user_id','start_at','end_at','status'));
    }
    
    public function getByProjUser(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|int|min:1',
            'user_id' => 'required|int|min:1',
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());

        $activities = Activity::select([
            'start_at','end_at','description','comment',
            DB::raw('TIMESTAMPDIFF(MINUTE,activities.start_at,activities.end_at) as minutes')
        ])
        ->where('project_id',$request->project_id)
        ->where('user_id',$request->user_id)
        ->where('start_at','>=',Carbon::parse($request->start_at))
        ->where('end_at','<=',Carbon::parse($request->end_at)->tomorrow())
        ->orderBy('start_at')
        ->get();

        return json_encode($activities);
    }

    public function download(Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());
        
        ini_set('memory_limit','128M');

        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $user_id = $request->user_id;
        $status = $request->status;
        $start_at = Carbon::parse($request->start_at);
        $end_at = Carbon::parse($request->end_at);

        $items = Activity::select([
            DB::raw('concat(customers.name," (",customers.code,")") as customer'),
            DB::raw('concat(projects.name," (",projects.code,")") as project'), 
            DB::raw('concat(users.lastname,", ",users.name) as user'),
            DB::raw('concat(projects.name," (",projects.code,")") as project'), 
            DB::raw('if(projects.status="O","Abierto",if(projects.status="F","Cerrado","Cancelado")) as status'),             
            DB::raw('activities.start_at'),
            DB::raw('activities.end_at'),
            DB::raw('TIMESTAMPDIFF(MINUTE,activities.start_at,activities.end_at) as minutes'),
            DB::raw('activities.description'),
            DB::raw('activities.comment')
        ])
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('customers','customers.id','projects.customer_id')
        ->leftJoin('users','users.id','activities.user_id')
        ->where('activities.user_id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('activities.user_id',$user_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->where(function ($query) use ($start_at) {
            if ($start_at)
                $query->where('activities.start_at','>=',$start_at);
            return $query;
        })
        ->where(function ($query) use ($end_at) {
            if ($end_at)
                $query->where('activities.end_at','<=',$end_at->tomorrow());
            return $query;
        })
        ->orderByRaw(
            DB::raw('concat(customers.name," (",customers.code,")")'), 
            DB::raw('concat(projects.name," (",projects.code,")")'), 
            DB::raw('concat(users.lastname,", ",users.name)'),
            DB::raw('concat(projects.name," (",projects.code,") [",if(projects.status="O","Abierto",if(projects.status="F","Cerrado","Cancelado")),"]")'), 
            DB::raw('activities.start_at')
        )
        ->get();
        
        $customer = $customer_id ? Customer::find($customer_id) : null;
        $project = $project_id ? Project::find($project_id) : null;
        $user = $user_id ? User::find($user_id) : null;

        $customer = $customer ? $customer->name.' ('.$customer->code.')' : 'Todos';
        $project = $project ? $project->name.' ('.$project->code.')' : 'Todos';
        $user = $user ? $user->lastname.', '.$user->name : 'Todos';
        $start_at = $request->start_at ? Carbon::parse($request->start_at)->format('d/m/Y') : 'Sin definir';
        $end_at = $request->end_at ? Carbon::parse($request->end_at)->format('d/m/Y') : 'Sin definir';
        $status = $status === 'O' ? 'Abierto' : ($status === 'F' ? 'Cerrado' : ($status === 'C' ? 'Cancelado' : 'Todos'));

        $export = new InvoicesExport($items->toArray(), $customer, $project, $user, $status, $start_at, $end_at);
        return Excel::download($export,'Reporte_tiempos_'.Carbon::now()->format('d-m-Y').'.xlsx');
    }

    protected function validationErrorMessages()
    {
        return [
            'start_at.required' => 'Debe ingresar una fecha inicial.',
            'start_at.before_or_equal' => 'La fecha inicial no puede ser posterior a la fecha final.',
            'end_at.required' => 'Debe ingresar una fecha final.',
            'end_at.before_or_equal' => 'La fecha final no puede ser posterior a la fecha actual.'
        ];
    }
}
