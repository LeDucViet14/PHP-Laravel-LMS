@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit SubCategory</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit SubCategory</h5>
                <form id="myForm" action="{{ route('update.subcategory') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $subcategory->id }}">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Category Name</label>
                        <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>Open this select menu</option>
                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $cat->id == $subcategory->category_id ? 'selected' : '' }}>{{ $cat->category_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">SubCategory Name</label>
                        <input type="text" name="subcategory_name" class="form-control" id="input1"
                            value="{{ $subcategory->subcategory_name }}">
                    </div>



                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>
@endsection
