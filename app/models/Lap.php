<?php

class Lap extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'laps';

	public function trackpoints() 
	{
		return $this->hasMany('Trackpoint');
	}
	
	public function activity()
	{
		return $this->belongsTo('Activity', 'activity_id');
	}
}
