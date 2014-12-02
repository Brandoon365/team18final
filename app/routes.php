<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * This will display the home page
 */
Route::get('/', function() {
	
	if(!Auth::check()) {
		return View::make('field.home');
	}
	else {
		if(Auth::user()->is_admin) {
			return Redirect::intended('/admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
});

Route::get('/ViewTeams', function() {
	$teams = Team::all();
	$projects = Project::all();
	$teamIDS = array();
	
	foreach($teams as $team) {
		$user = User::find($team->member);
		if (!array_key_exists($team->projectID, $teamIDS)) {
			$teamIDS[$team->projectID] = array();
			$teamIDS[$team->projectID][0]=$team->projectID;
		}
		
		array_push($teamIDS[$team->projectID], array($user->id, $user->first." ".$user->last));
	}
	ksort($teamIDS);
	return View::make('field.teams')->with('teamIDS', $teamIDS)->with('projects', $projects);
});

Route::get('/GenerateTeams', function() {
	Team::truncate();
	$users = User::where('teamFirst', '=', '0')->get();
	$projects = Project::all();
	$remainingUsers = User::where('is_admin', '=', '0')->where('teamFirst', '=', '1')->get();
	print(count($remainingUsers));

	foreach ($users as $u) {
		if($projects->find($u->preference1)) {
			$desiredProject = Project::find( $u->preference1 );
			$number = count( Team::where('projectID', '=', $desiredProject->id) );
			if ($number < $desiredProject->min) {
				$team = new Team;
				$team->projectID = $desiredProject->id;
				$team->member = $u->id;
				$team->save();
				continue;
			}
		}

		if($projects->find($u->preference2)) {
			$desiredProject = Project::find( $u->preference2 );
			$number = count( Team::where('projectID', '=', $desiredProject->id) );
			if ($number < $desiredProject->min) {
				$team = new Team;
				$team->projectID = $desiredProject->id;
				$team->member = $u->id;
				$team->save();
				continue;
			}
		}

		if($projects->find($u->preference3)) {
			$desiredProject = Project::find( $u->preference3 );
			$number = count( Team::where('projectID', '=', $desiredProject->id) );
			if ($number < $desiredProject->min) {
				$team = new Team;
				$team->projectID = $desiredProject->id;
				$team->member = $u->id;
				$team->save();
				continue;
			}
		}

		if($projects->find($u->preference4)) {
			$desiredProject = Project::find( $u->preference4 );
			$number = count( Team::where('projectID', '=', $desiredProject->id) );
			if ($number < $desiredProject->min) {
				$team = new Team;
				$team->projectID = $desiredProject->id;
				$team->member = $u->id;
				$team->save();
				continue;
			}
		}
		$remainingUsers->push($u);
	}
	print(count($remainingUsers));

	//Assign the rest of the users using a score system.
	// + 2 to project for each open spot below the minimum
	// - 2 for every spot above minimum
	// + 4 for each person on prefer list
	// - 4 for each person on avoid list
	// + 4 for pref1, 3 for pref2, etc..
	foreach ($remainingUsers as $rem) {
		$bestProject = 1;
		$bestScore = 0;
		$count = 1;
		foreach ($projects as $proj) {
			if($count == 1) {
				$bestProject = $proj->id;
			}
			$score = 0;
			$currentMembers = Team::where('projectID', '=', $proj->id)->get();
			if(count($currentMembers) < $proj->max) {
				$score += 2*($proj->min - count($currentMembers));
				
				$preferences = Teammate::where('student', '=', $rem->id);
				foreach ($preferences as $pref) {
					if (count(Team::where('projectID', '=', $proj->id, 'AND', 'member', '=', $pref->teammate))) {
						if($pref->prefer) {
							$score += 4;
						}
						else if ($pref->avoid) {
							$score -= 4;
						}
					}
				}
				
				if ($proj->id == $rem->preference1) {
					$score += 4;
				}
				else if ($proj->id == $rem->preference2) {
					$score += 3;
				}
				else if ($proj->id == $rem->preference3) {
					$score += 2;
				}
				else if ($proj->id == $rem->preference4) {
					$score += 1;
				}
				
				if ($score > $bestScore) {
					$bestScore = $score;
					$bestProject = $proj->id;
				}
			}
			else {
				if($bestProject == $count || is_null(Project::where('projectID', '=', $bestProject))) {
					$bestProject++;
				}
			}
			$count++;
		}
		$team = new Team;
		$team->projectID = $bestProject;
		$team->member = $rem->id;
		$team->save();
	}
	return Redirect::intended('/ViewTeams');
});

Route::post('MoveTo/{id}', function($id) {
	Team::where('member', '=', $id)->delete();
	$team = new Team;
	$team->projectID = Input::get('select'.$id);
	$team->member = $id;
	
	$team->save();
	
	return Redirect::intended('/ViewTeams');
});


Route::post('/', function() {
	$email = Input::get('email');
	$password = Input::get('password');
	
	$credentials = array(
		'email' => $email,
		'password' => $password
	);
	if(Auth::attempt($credentials)) {
		if(Auth::user()->is_admin) {
			return Redirect::intended('/admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
	else {
		return Redirect::back()->withInput()->with('error', "Invalid Credentials");
	}
});

Route::get('/student', function() {
	if(Auth::check()) {
		return View::make('field.form');
	}
	else {
		return Redirect::intended('/');
	}
});

Route::post('/student', function() {
	
	$user=User::whereEmail(Auth::user()->email);
	$userInfo = array(
		'preference1' => Input::get('proj_0'),
		'preference2' => Input::get('proj_1'),
		'preference3' => Input::get('proj_2'),
		'preference4' => Input::get('proj_3'),
		'teamFirst' => Input::get('teamFirst'),
	);

	$user->update($userInfo);
	$preferred = Input::get('pref');
	$avoid = Input::get('avoid');

	foreach($preferred as $p) {
		DB::table('teammates')->insert(array('student' => Auth::user()->id, 'teammate' => $p, 'prefer' => true, 'avoid' => false,));
	}
	
	foreach($avoid as $a) {
		DB::table('teammates')->insert(array('student' => Auth::user()->id, 'teammate' => $a, 'prefer' => false, 'avoid' => true,));
	}
	
	
	return Redirect::back()->with('message', 'Successfully updated preferences');
});

Route::get('/admin', function() {
	if(Auth::check()) {
		if(Auth::user()->is_admin) {
			return View::make('field.admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
	else {
		return Redirect::intended('/');
	}
});

Route::get('logout', function() {
	Auth::logout();
	return Redirect::to('/')->with('message', 'Successfully logged out');
});

Route::get('/viewTeams', function() {
	$teams=Teammate::all();
	var_dump($teams);
});
