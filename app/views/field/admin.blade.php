@extends('master')

@section('content')
    <div class="admin_tools">
        {{link_to('/GenerateTeams', "Generate Teams")}} <br>
        {{link_to('/ViewTeams', "View Teams")}}
    </div>
@stop