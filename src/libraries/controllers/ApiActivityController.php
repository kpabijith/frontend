<?php
/**
  * Activity controller for API endpoints
  *
  * This controller handles all activity endpoints
  * @author Jaisen Mathai <jaisen@jmathai.com>
  */
class ApiActivityController extends ApiBaseController
{
  /**
    * Call the parent constructor
    *
    * @return void
    */
  public function __construct()
  {
    parent::__construct();
    $this->activity = new Activity;
  }

  public function create()
  {
    $status = $this->activity->create($_POST);
    if($status !== false)
      return $this->success('Created activity for user', true);
    else
      return $this->error('Could not create activities', false);
  }

  public function list_()
  {
    $activities = $this->activity->list_();
    if(isset($_GET['groupBy']))
      $activities = $this->groupActivities($activities, $_GET['groupBy']);

    if($activities !== false)
      return $this->success("User's list of activities", $activities);
    else
      return $this->error('Could not get activities', false);
  }

  public function view($id)
  {
    $activity = $this->activity->view($id);
    if($activity !== false)
      return $this->success("Activity {$id}", $activity);
    else
      return $this->error('Could not get activity', false);
    
  }

  protected function groupActivities($activities, $groupBy)
  {
    switch($groupBy)
    {
      case 'hour':
        $fmt = 'YmdH';
        break;
      case 'day':
      default:
        $fmt = 'Ymd';
        break;
    }

    $return = array();
    foreach($activities as $activity)
    {
      $grp = sprintf('%s-%s', date($fmt, $activity['dateCreated']), $activity['type']);
      $return[$grp][] = $activity;
    }

    return $return;
  }
}
