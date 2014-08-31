<?php

class UploadParsing extends Eloquent {

	protected $table = 'uploadParsing';
	
	protected $fillable = array('completedActivitesCount', 'allActivitiesInDb', 'user_id');

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function unsavedActivity()
	{
		return $this->belongsTo('UnsavedActivity', 'unsavedActivity_id');
	}
}
