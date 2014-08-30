<?php

class Trackpoint extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'trackpoints';

	public function laps()
	{
		return $this->belongsTo('Lap', 'lap_id');
	}
}
