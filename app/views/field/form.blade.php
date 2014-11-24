@extends('master')

@section('content')
        @if(Session::has('message'))
        <div class="message">
            {{Session::get('message')}}
        </div>
        @endif
	{{Form::open()}}
	<div class="form">
			<?php
				$projects = Project::where('project', '!=', "")->get();	
				foreach (range(0,3) as $num) {
					echo "<select name='proj_$num'>"; 
					foreach ($projects as $project) {
						echo "<option value=$project->id>$project->project</option>";
					}
					echo "</select><br>";
				}
			?>
			<?php
				$users = User::where('is_admin', '!=', "1")->get();	
				foreach (range(0,1) as $num) {
					if ($num == 0){
						echo "Please select up to two students whom you'd prefer to avoid.
<br>";
					} else {
						echo "Please select up to two preferred group members.<br>";
					}
					if ($num == 0) {
						echo "<select multiple name='avoid[]'>";
					} else {
						echo "<select multiple name='pref[]'>";
					}
					foreach ($users as $user) {
						echo "<option value=$user->id>$user->first $user->last</option>";
					}
					echo "</select><br>";
				}
			?>
			Below, select which grouping factor you care most about<br>
			<select name="teamFirst">
				<option value=0>I care most about the Project I'm on</option>
				<option value=1>I care most about the teammates I'm with</option>
			</select>
	</div>
		{{Form::submit()}}
	{{Form::close()}}
@stop