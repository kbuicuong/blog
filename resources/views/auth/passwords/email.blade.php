@extends('main')

@section('title', "| Forgot my password")

@section('content')


    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">

                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ Form::open(['url' => 'password/email', 'method' => "POST"]) }}
                    
                        {{ Form::label('email', 'Email: ') }}
                        {{ Form::email('email', null, ['class' => 'form-control']) }}
                    
                        {{ Form::submit('Reset Password', ['class' => 'btn btn-primary form-spacing-top']) }}

                    {{ Form::close() }}

                </div>

            </div>
        </div>
    </div>


@stop