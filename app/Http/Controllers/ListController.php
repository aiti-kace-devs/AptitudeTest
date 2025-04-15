<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Query\Builder; // Import the Query Builder
use Illuminate\Support\Facades\Schema;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $views = DB::select('SHOW FULL TABLES WHERE Table_type = "VIEW"');
        return view('lists.index', compact('views'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return view('lists.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'view_name' => 'required|string|unique:information_schema.views,TABLE_NAME', // Ensure unique view name
            'table_name' => 'required|string|in:' . implode(',', DB::connection()->getDoctrineSchemaManager()->listTableNames()),
            'columns' => 'nullable|array',
            'columns.*' => 'string',
            'where_conditions' => 'nullable|array',
            'where_conditions.*.column' => 'required|string',
            'where_conditions.*.operator' => 'required|string|in:==,!=,<,>,<=,>=,LIKE,NOT LIKE,IN,NOT IN',
            'where_conditions.*.value' => 'required',
            'order_by_column' => 'nullable|string',
            'order_by_direction' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1',
            'joins' => 'nullable|array',
            'joins.*.table' => 'required|string',
            'joins.*.first_column' => 'required|string',
            'joins.*.operator' => 'required|string|in:=,>,<,>=,<=',
            'joins.*.second_column' => 'required|string',
            'joins.*.type' => 'nullable|string|in:inner,left,right,cross',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $viewName = $request->input('view_name');
        $tableName = $request->input('table_name');
        $columns = $request->input('columns', ['*']);
        $whereConditions = $request->input('where_conditions', []);
        $orderByColumn = $request->input('order_by_column');
        $orderByDirection = $request->input('order_by_direction', 'asc');
        $limit = $request->input('limit');
        $joins = $request->input('joins', []);

        // Build the SELECT query string
        $selectQuery = DB::table($tableName)
            ->select($columns);

        // Handle joins
        foreach ($joins as $join) {
            $joinType = $join['type'] ?? 'inner';
            $operator = $join['operator'] ?? '=';
            $selectQuery->join($join['table'], $join['first_column'], $operator, $join['second_column'], $joinType);
        }
        // Handle where conditions
        foreach ($whereConditions as $condition) {
            $selectQuery->where(
                $condition['column'],
                $condition['operator'],
                $condition['value']
            );
        }

        // Handle order by
        if ($orderByColumn) {
            $selectQuery->orderBy($orderByColumn, $orderByDirection);
        }

        // Handle limit
        if ($limit) {
            $selectQuery->limit($limit);
        }
        // Get the SQL query string
        $sql = $selectQuery->toSql();

        // Create the view
        DB::statement("CREATE VIEW {$viewName} AS {$sql}");

        return redirect()->route('lists.index')->with('success', 'List view created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $viewName
     * @return \Illuminate\View\View
     */
    public function show(string $viewName): View
    {
        // Check if the view exists
        $viewExists = DB::select("SHOW TABLES LIKE '{$viewName}'");
        if (!$viewExists) {
            abort(404, 'View not found.');
        }

        // Fetch data from the view.
        $results = DB::table($viewName)->get();

        return view('lists.show', compact('results', 'viewName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //This method is not needed for the ListModel
        abort(404);
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
        //This method is not needed for the ListModel
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $viewName
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $viewName): RedirectResponse
    {
        // Check if the view exists before attempting to drop it.
        $viewExists = DB::select("SHOW TABLES LIKE '{$viewName}'");
        if (!$viewExists) {
            return redirect()->route('lists.index')->with('error', 'View does not exist.'); //Or throw an exception
        }
        DB::statement("DROP VIEW IF EXISTS {$viewName}");

        return redirect()->route('lists.index')->with('success', 'List view deleted successfully!');
    }
}
