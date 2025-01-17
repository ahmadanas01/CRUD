<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD App Laravel 8 & Ajax</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />

</head>

<body class="bg-light">
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-danger d-flex justify-content-between align-items-center">
                        <h3 class="text-light">Manage Employees</h3>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                                class="bi-plus-circle me-2"></i>Add New Employee</button>
                    </div>
                    <div class="card-body" id="show_all_employees">
                        <h1 class="text-center text-secondary my-5">Loading...</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="add_employee_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="my-2">
                            <label for="post">Post</label>
                            <input type="text" name="post" class="form-control" placeholder="Post" required>
                        </div>
                        <div class="my-2">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar[]" class="form-control" multiple required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_employee_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <input type="hidden" name="emp_id" id="emp_id">
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control"
                                    placeholder="First Name" required>
                            </div>
                            <div class="col-lg">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control"
                                    placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail"
                                required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone"
                                required>
                        </div>
                        <div class="my-2">
                            <label for="post">Post</label>
                            <input type="text" name="post" id="post" class="form-control" placeholder="Post"
                                required>
                        </div>
                        <div class="my-2">
                            <label for="avatar">Select Avatar</label>
                            <input type="file" name="avatar[]" id="avatar" class="form-control" multiple>
                        </div>
                        <div class="mt-2" id="avatar-preview">
                            <!-- Avatar preview images will be shown here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js'></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Fetch all employees on page load
            fetchAllEmployees();

            // Function to fetch all employees via AJAX
            function fetchAllEmployees() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('fetchAll') }}",
                    dataType: "html",
                    success: function (response) {
                        $("#show_all_employees").html(response);
                    }
                });
            }

            // Submit add employee form via AJAX
            $("#add_employee_form").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "{{ route('store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                      if (response.status == 200) {
                        Swal.fire(
                          'Added!',
                          'Employee Added Successfully!',
                          'success'
                        )
                        fetchAllEmployees();
                      }
                      $("#add_employee_btn").text('Add Employee');
                      $("#add_employee_form")[0].reset();
                      $("#addEmployeeModal").modal('hide');
                    }
                });
            });

            // Populate edit employee modal via AJAX
            $(document).on('click', '.editIcon', function () {
                var emp_id = $(this).attr('id');
                $.ajax({
                    type: "GET",
                    url: "{{ route('edit') }}",
                    data: {
                        id: emp_id
                    },
                    dataType: "json",
                    success: function (response) {
                        $("#emp_id").val(response.id);
                        $("#fname").val(response.first_name);
                        $("#lname").val(response.last_name);
                        $("#email").val(response.email);
                        $("#phone").val(response.phone);
                        $("#post").val(response.post);

                        // Display avatar images in edit modal
                        var avatars = JSON.parse(response.avatars);
                        var avatarPreview = '';
                        avatars.forEach(function (avatar) {
                            avatarPreview += '<img src="' + "{{ asset('storage/images/') }}" + '/' + avatar + '" width="100" class="img-fluid img-thumbnail">';
                        });
                        $("#avatar-preview").html(avatarPreview);

                        $("#editEmployeeModal").modal('show');
                    }
                });
            });

            // Submit edit employee form via AJAX (update)
            $("#edit_employee_form").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "{{ route('update') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                      if (response.status == 200) {
                        Swal.fire(
                          'Updated!',
                          'Employee Updated Successfully!',
                          'success'
                        )
                        fetchAllEmployees();
                      }
                      $("#edit_employee_btn").text('Update Employee');
                      $("#edit_employee_form")[0].reset();
                      $("#editEmployeeModal").modal('hide');
                    }
                });
            });

            // Delete employee via AJAX
            // $(document).on('click', '.deleteIcon', function () {
            //     var emp_id = $(this).attr('id');
            //     if (confirm("Are you sure you want to delete this employee?")) {
            //         $.ajax({
            //             type: "DELETE",
            //             url: "{{ route('delete') }}",
            //             data: {
            //                 id: emp_id
            //             },
            //             success: function (response) {
            //                 if (response.status == 200) {
            //                     fetchAllEmployees();
            //                 }
            //             }
            //         });
            //     }
            // });

            // delete employee ajax request
            $(document).on('click', '.deleteIcon', function(e) {
              e.preventDefault();
              let id = $(this).attr('id');
              let csrf = '{{ csrf_token() }}';
              Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                  $.ajax({
                    url: '{{ route('delete') }}',
                    method: 'delete',
                    data: {
                      id: id,
                      _token: csrf
                    },
                    success: function(response) {
                      console.log(response);
                      Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                      )
                      fetchAllEmployees();
                    }
                  });
                }
              })
            });

        });
    </script>
</body>

</html>
