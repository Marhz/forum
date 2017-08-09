<?php

namespace App;

trait RecordsActivity
{
	protected static function bootRecordsActivity()
	{
		if(auth()->guest()) return;
		foreach(static::getRecordEvent() as $event) {
	        static::$event(function ($model) {
	            $model->recordActivity('created');
	        });			
		}

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
	}

	protected static function getRecordEvent()
	{
		return ['created'];
	}

    protected function recordActivity($event)
    {
    	$this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)    		
    	]);    	
    }

    protected function getActivityType($event)
    {
        return $event.'_'.strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function activity()
    {
    	return $this->morphMany(Activity::class, 'subject');
    }

}