@extends('master')

@section('content')
    {{Form::open()}}
    <div class="login">
    
        @if(Session::has('error'))
        <div class="alert alert-warning">
            {{Session::get('error')}}
        </div>
        @elseif(Session::has('message'))
        <div class="message">
            {{Session::get('message')}}
        </div>
        @endif
        <div id="login">
            <div class="login-element">{{Form::label('Email')}} {{Form::text('email')}}</div>
            <br>
            <div class="login-element">{{Form::label('Password')}} {{Form::password('password')}}</div>
            <br>
            
            <span class="login-element">{{Form::submit("Login")}}</span>
        </div>
    </div>
    {{Form::close()}}
@stop
	
	
