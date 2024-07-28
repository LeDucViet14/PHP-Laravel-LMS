@extends('admin.admin_dashboard')
@section('admin')
    <style>
        .large-checkbox {
            transform: scale(1.5);
        }
    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Instructor </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">

            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Instructor Name </th>
                                <th>Username </th>
                                <th>Email </th>
                                <th>Phone </th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($allinstructor as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>

                                    <td>
                                        @if ($item->status == 1)
                                            <span id="update-button-{{ $item->id }}"
                                                class="btn
                                                btn-success">Active
                                            </span>
                                        @else
                                            <span id="update-button-{{ $item->id }}"
                                                class="btn
                                                btn-danger">InActive
                                            </span>
                                        @endif
                                    </td>


                                    <td>
                                        <div class="form-check-danger form-check form-switch">
                                            <input class="form-check-input status-toggle large-checkbox" type="checkbox"
                                                id="flexSwitchCheckCheckedDanger" data-user-id="{{ $item->id }}"
                                                {{ $item->status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="flexSwitchCheckCheckedDanger"> </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>




    </div>

    <script>
        $(document).ready(function() {
            $('.status-toggle').on('change', function() {
                var userId = $(this).data('user-id');
                var isChecked = $(this).is(':checked');
                // send an ajax request to update status 
                $.ajax({
                    url: "{{ route('update.user.stauts') }}",
                    method: "POST",
                    data: {
                        user_id: userId,
                        is_checked: isChecked ? 1 : 0,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        var button = $('#update-button-' + userId);
                        if (isChecked) {
                            button.text('Active'); // Change button text or other properties
                            button.addClass('btn-success').removeClass(
                                'btn-danger'); // Add/Remove classes as needed
                        } else {
                            button.text('Inactive'); // Change button text or other properties
                            button.addClass('btn-danger').removeClass(
                                'btn-success'); // Add/Remove classes as needed
                        }
                        // if (isChecked) {
                        //     $('#update-button').text(
                        //         'Active'); // Change button text or other properties
                        //     $('#update-button').addClass('btn-success').removeClass(
                        //         'btn-danger'); // Add/Remove classes as needed
                        // } else {
                        //     $('#update-button').text(
                        //         'Inactive'); // Change button text or other properties
                        //     $('#update-button').addClass('btn-danger').removeClass(
                        //         'btn-success'); // Add/Remove classes as needed
                        // }
                    },
                    error: function() {}
                });
            });
        });
    </script>
@endsection
