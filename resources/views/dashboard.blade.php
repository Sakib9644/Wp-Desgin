@extends('backend.layouts.master')

@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">
                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            <h4 class="fs-16 mb-1">{{ auth()->user()->name }}</h4>
                                            <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- calculator.php -->


                            <!-- Static Quick Links with Zoom Effect -->
                            @if (auth()->user()->hasRole('Super Admin'))
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-upload-2-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Product Upload</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-folder-upload-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Category Upload</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-file-list-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Manage Orders</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-user-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Customer List</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-bar-chart-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Reports</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="#"
                                            class="card text-center h-100 shadow-sm text-decoration-none zoom-hover">
                                            <div class="card-body">
                                                <i class="ri-settings-3-line fs-2 text-primary mb-2"></i>
                                                <h6 class="mb-0 text-dark">Settings</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        {{-- <div class="container ">
            <h2 class="text-center mb-4">Simple PHP Calculator</h2>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Enter First Number:</label>
                        <input type="number" id="num1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Enter Second Number:</label>
                        <input type="number" id="num2" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Select Operation:</label>
                        <select name="operator" class="form-control" id="operator" required>
                            <option value="add">Add (+)</option>
                            <option value="subtract">Subtract (-)</option>
                            <option value="multiply">Multiply (×)</option>
                            <option value="divide">Divide (÷)</option>
                        </select>
                    </div>
                    <button type="button" id="calculate" class="btn btn-primary btn-block">Calculate</button>



                </div>
            </div>
            <p class="total"></p>
        </div> --}}

    </div>
    {{-- <script>
    $(document).ready(function() {
        $('#calculate').click(function() {
            var num1 = $('#num1').val();
            var num2 = $('#num2').val();
            var operator = $('#operator').val();

            // Convert inputs to numbers
            num1 = parseFloat(num1);
            num2 = parseFloat(num2);

            // Check for NaN (invalid numbers)
            if (isNaN(num1) || isNaN(num2)) {
                alert('Please input two valid numbers');
                return;
            }

            var result;

            // Perform calculation based on operator
            if (operator === 'add') {
                result = num1 + num2;
            } else if (operator === 'subtract') {
                result = num1 - num2;
            } else if (operator === 'multiply') {
                result = num1 * num2;
            } else if (operator === 'divide') {
                if (num2 === 0) {
                    alert('Cannot divide by zero');
                    return;
                }
                result = num1 / num2;
            } else {
                alert('Invalid operator');
                return;
            }

            // Display the result
            $('.total').text(result);
        });
    });
</script> --}}

@endsection
