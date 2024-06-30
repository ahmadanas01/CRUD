<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller {

    // set index page view
    public function index() {
        return view('index');
    }

    // handle fetch all employees ajax request
    public function fetchAll() {
        $emps = Employee::all();
        $output = '';
        if ($emps->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Post</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($emps as $emp) {
                $output .= '<tr>
                <td>' . $emp->id . '</td>
                <td>';
                $avatars = json_decode($emp->avatars);
                foreach ($avatars as $avatar) {
                    $output .= '<img src="' . asset('storage/images/' . $avatar) . '" width="50" class="img-thumbnail rounded-circle">';
                }
                $output .= '</td>
                <td>' . $emp->first_name . ' ' . $emp->last_name . '</td>
                <td>' . $emp->email . '</td>
                <td>' . $emp->post . '</td>
                <td>' . $emp->phone . '</td>
                <td>
                  <a href="#" id="' . $emp->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $emp->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new employee ajax request
    public function store(Request $request) {
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

        return response()->json([
            'status' => 200,
        ]);
    }

    // handle edit an employee ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $emp = Employee::find($id);
        return response()->json($emp);
    }

    // handle update an employee ajax request
    // public function update(Request $request) {
    //     $fileNames = [];
    //     $emp = Employee::find($request->emp_id);

    //     if ($request->hasFile('avatar')) {
    //         foreach ($request->file('avatar') as $file) {
    //             $fileName = time() . '_' . $file->getClientOriginalName();
    //             $file->storeAs('public/images', $fileName);
    //             $fileNames[] = $fileName;
    //         }
    //         // Delete old avatars if necessary
    //         foreach (json_decode($emp->avatars) as $oldAvatar) {
    //             Storage::delete('public/images/' . $oldAvatar);
    //         }
    //     } else {
    //         $fileNames = json_decode($emp->avatars);
    //     }

    //     $empData = [
    //         'first_name' => $request->fname,
    //         'last_name' => $request->lname,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'post' => $request->post,
    //         'avatars' => json_encode($fileNames)
    //     ];

    //     $emp->update($empData);

    //     return response()->json([
    //         'status' => 200,
    //     ]);
    // }

	public function update(Request $request) {
		$emp = Employee::find($request->emp_id);
	
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
			'first_name' => $request->fname,
			'last_name' => $request->lname,
			'email' => $request->email,
			'phone' => $request->phone,
			'post' => $request->post,
			'avatars' => json_encode($fileNames)
		];
	
		$emp->update($empData);
	
		return response()->json([
			'status' => 200,
		]);
	}
	

    // handle delete an employee ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $emp = Employee::find($id);
        $avatars = json_decode($emp->avatars);

        foreach ($avatars as $avatar) {
            Storage::delete('public/images/' . $avatar);
        }

        Employee::destroy($id);

        return response()->json([
            'status' => 200,
        ]);
    }
}
