<?php
namespace App;
use App\User;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamMember;

class Account
{

  public static function checkOwner($projectId){
    $project = Project::find($projectId);
    $user = auth()->user();
    if($project['created_by'] == $user['id']) return true;
    else return false;
  }

  public static function checkTeamManager($projectId){
    $project = Project::find($projectId);
    $user = auth()->user();
    $teamLeader = $project['created_by'];
    $team = Team::where('owner', $teamLeader)->first();
    if(!$team) return false;
    $teamMember = TeamMember::where('team_id', $team['id'])
                    ->where('user_id', $user['id'])->first();
    if(!$teamMember) return false;
    if($teamMember['role'] == 'manager') return true;
    else return false;
  }

  public static function checkTeamMember($projectId){
    $project = Project::find($projectId);
    $user = auth()->user();
    $teamLeader = $project['created_by'];
    $team = Team::where('owner', $teamLeader)->first();
    if(!$team) return false;
    $teamMember = TeamMember::where('team_id', $team['id'])
                    ->where('user_id', $user['id'])->first();
    if($teamMember) return true;
    return false;
  }

}