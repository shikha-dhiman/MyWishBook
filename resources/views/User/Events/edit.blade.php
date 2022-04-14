@extends('User.Layouts.main')
@section('content')
<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)" class="capt-cls">{{request()->route()->getName()}}</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-validation">
                                    <form class="form-valide" action="" method="post">
                                        @csrf
                                        @foreach($event as $key => $val)
                                        <input type="hidden" name="event_id" value="{{$val->id}}">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label" for="eventname">Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control capt-cls" id="eventname" name="eventname" value="{{$val->name}}" onkeydown="return /[A-Za-z ]/i.test(event.key)" required>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="form-group row">
                                            <div class="col-lg-10 ml-auto">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        @endsection
       