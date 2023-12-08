<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::paginate(3);
        return response()->json(['status' => true, 'message' => 'data list successfully', 'data' => $employee]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'number' => ['required', 'min:10'],
            'designation' => ['required', 'max:255'],
        ]);

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->number = $request->number;
        $employee->designation = $request->designation;
        $employee->save();

        return response()->json(['status' => true, 'message' => 'employee craeted success', 'data' => $employee], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::find($id);

        if (is_null($employee)) {
            return response()->json(['status' => false, 'message' => 'employee not found'], 404);
        }
        return response()->json(['status' => true, 'data' => $employee], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'number' => ['required', 'min:10'],
            'designation' => ['required', 'max:255'],
        ]);

        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'employee not found'], 404);
        }

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->number = $request->number;
        $employee->designation = $request->designation;
        $employee->save();
        return response()->json(['status' => true, 'message' => 'employee updated success', 'data' => $employee], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'employee not found'], 404);
        }
        
        $employee->delete();
        return response()->json(['status' => true, 'message' => 'employee deleted success'], 200);
    }
}
