<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
	
	protected $fillable = array('id');
	public function uploadParsing()
	{
		return $this->hasMany('UploadParsing');
	}

	public function activitys()
	{
		return $this->hasMany('Activity');
	}
	
	public function unsavedActivitys()
	{
		return $this->hasMany('UnsavedActivity');
	}

}
