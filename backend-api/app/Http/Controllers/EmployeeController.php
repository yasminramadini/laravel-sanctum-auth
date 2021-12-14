<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
//untuk validasi
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::latest()->get();
        
        return response()->json($employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validasi
        $data = Validator::make($request->all(), [
          'name' => 'required',
          'age' => 'required',
          'job' => 'required|min:3',
          'salary' => 'required'
        ]);
        
        //jika Validasi gagal
        if($data->fails()) {
          return response()->json($data->errors(), 422);
        }
        
        //save 
        $employee = Employee::create([
          'name' => $request->name,
          'age' => $request->age,
          'job' => $request->job,
          'salary' => $request->salary
        ]);
        
        return response()->json([
          'message' => 'Employee created successfuly'
          ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return response()->json([
          'employee' => $employee
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //update 
        $updateEmployee = $employee->update($request->all());
        
        return response()->json([
          'message' => 'Employee updated successfuly'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return response()->json('Employee deleted successfuly', 200);
    }
}
