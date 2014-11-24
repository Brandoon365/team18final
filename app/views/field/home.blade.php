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
        <div class="login-element">{{Form::label('Email')}} {{Form::text('email')}}</div>
        <br>
        <div class="login-element">{{Form::label('Password')}} {{Form::password('password')}}</div>
        <br>
        

        
        <span class="login-element">{{Form::submit()}}</span>
    </div>
    {{Form::close()}}
@stop
	
	
