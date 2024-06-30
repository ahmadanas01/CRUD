<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeApiController extends Controller
{
    // Fetch all employees
    public function index()
    {
        $emps = Employee::all();
        return response()->json($emps);
    }

    // Store a new employee
    public function store(Request $request)
    {
        $fileNames = [];
        if ($request->hasFile('avatar')) {
            foreach ($request->file('avatar') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
                $fileNames[] = $fileName;
            }
        }

        $empData = [
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'post' => $request->post,
            'avatars' => json_encode($fileNames)
        ];

        Employee::create($empData);
        
        return response()->json(['status' => 200]);
    }

    // Edit an employee
    public function edit($id)
    {
        $emp = Employee::find($id);
        return response()->json($emp);
    }

    // Update an employee
    public function update(Request $request, $id)
    {
        $emp = Employee::find($id);

        // Prepare for storing new avatar file names
		$fileNames = json_decode($emp->avatars, true);
	
		// Check if new avatar files are uploaded
		if ($request->hasFile('avatar')) {
			// Upload new files
			foreach ($request->file('avatar') as $file) {
				$fileName = time() . '_' . $file->getClientOriginalName();
				$file->storeAs('public/images', $fileName);
				$fileNames[] = $fileName;
			}
	
			// Delete old avatars if necessary
			foreach (json_decode($emp->avatars) as $oldAvatar) {
				Storage::delete('public/images/' . $oldAvatar);
			}
		}

        $empData = [
            'first_name' => $request->input('fname'),
            'last_name' => $request->input('lname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'post' => $request->input('post'),
            'avatars' => $fileName
        ];

        $emp->update($empData);
        
        return response()->json(['status' => 200]);
    }

    // Delete an employee
    public function destroy($id)
    {
        try {
            $emp = Employee::findOrFail($id); // Find the employee or throw 404 error if not found
            
            // Delete all avatars associated with the employee
            $avatars = json_decode($emp->avatars);
            if (!empty($avatars)) {
                foreach ($avatars as $avatar) {
                    Storage::delete('public/images/' . $avatar);
                }
            }
            
            // Delete the single avatar (if exists)
            if ($emp->avatar) {
                Storage::delete('public/images/' . $emp->avatar);
            }

            // Finally, delete the employee record itself
            $emp->delete();

            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'error' => 'Failed to delete employee.']);
        }
    }


}
