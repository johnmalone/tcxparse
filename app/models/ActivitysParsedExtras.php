<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class ActivitysParsedExtras extends Eloquent {


	protected $table = 'activitysParsedExtras';

	public function activity()
	{
		return $this->hasOne('Activity');
	}
}
