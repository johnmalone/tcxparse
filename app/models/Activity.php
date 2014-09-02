<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Activity extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'activitys';

	public function laps() 
	{
		return $this->hasMany('Lap');
	}
	
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	public function activitysParsedExtras()
	{
		return $this->hasOne('ActivitysParsedExtras');
	}

}
