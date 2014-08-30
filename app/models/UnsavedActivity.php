<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UnsavedActivity extends Eloquent {

	use SoftDeletingTrait;

	protected $dates = [ 'deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'unsavedActivitys';

	protected $fillable = array('activityXML');
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
