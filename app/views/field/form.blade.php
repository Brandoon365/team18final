@extends('master')

@section('content')
        @if(Session::has('message'))
        <div class="message">
            {{Session::get('message')}}
        </div>
        @endif
        
        <div id="currentPref">
            <b>Preference 1:</b> <?php $name = Project::where('id', $user->preference1)->pluck('project');
                                       if ($name != "") {
                                            print($name);
                                       }
                                       else {
                                            print($user->preference1);
                                       }?> <br>
            <b>Preference 2:</b> <?php $name = Project::where('id', $user->preference2)->pluck('project');
                                       if ($name != "") {
                                            print($name);
                                       }
                                       else {
                                            print($user->preference2);
                                       }?> <br>
            <b>Preference 3:</b> <?php $name = Project::where('id', $user->preference3)->pluck('project');
                                       if ($name != "") {
                                            print($name);
                                       }
                                       else {
                                            print($user->preference3);
                                       }?> <br>
            <b>Preference 4:</b> <?php $name = Project::where('id', $user->preference4)->pluck('project');
                                       if ($name != "") {
                                            print($name);
                                       }
                                       else {
                                            print($user->preference4);
                                       }?> <br>
            <br>
            <b>Preferred Students: </b><br>
            <?php foreach($preferNames as $p) {
                    echo("<span class='studentPref'>");
                    print($p);
                    echo("</span>");
                    echo("<br>");
                  }
            ?><br>
            <br>
            <b>Avoided Students: </b><br>
            <?php foreach($avoidNames as $a) {
                    echo("<span class='studentPref'>");
                    print($a);
                    echo("</span>");
                    echo("<br>");
                  }
            ?>
            <br>
            <span id="teamPref">
                <?php if($user->teamFirst) {
                        print("Team First");
                      }
                      else {
                        print("Project First");
                      }
                ?>
            </span>
        </div>
            
	{{Form::open()}}
	<div id="newPref">
                        <div id="newProjPref">
			<?php
				$projects = Project::all();	
				foreach (range(1,4) as $num) {
                                        echo "<b>Preference $num: </b>";
					echo "<select name='proj_$num'>"; 
					foreach ($projects as $project) {
						echo "<option value=$project->id>$project->project</option>";
					}
					echo "</select><br>";
				}
			?>
                        </div>
                        <br>
                        <div id="newTeamPref">
			<?php
				$users = User::where('is_admin', '!=', "1")->get();	
				foreach (range(0,1) as $num) {
					if ($num == 0){
						echo "<b>Please select up to two students whom you'd prefer to avoid.</b><br>";
					} else {
						echo "<b>Please select up to two preferred group members.</b><br>";
					}
					if ($num == 0) {
						echo "<select multiple name='avoid[]'>";
					} else {
						echo "<select multiple name='pref[]'>";
					}
					foreach ($users as $user) {
						echo "<option value=$user->id>$user->first $user->last</option>";
					}
					echo "</select><br><br>";
				}
			?>
                        </div>
			<b>Below, select which grouping factor you care most about</b><br>
			<select name="teamFirst">
				<option value=0>I care most about the Project I'm on</option>
				<option value=1>I care most about the teammates I'm with</option>
			</select>
                        <br><br>
                        {{Form::submit("Save Preferences")}}
	</div>
	{{Form::close()}}
@stop