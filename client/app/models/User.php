<?php

use Illuminate\Support\MessageBag;

class User extends Eloquent {

    /**
     * Activation error message
     *
     * @var string
     */
    public $activation_error;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';


    public function loans()
    {
        return $this->hasMany('Loan');
    }

	/**
     * Validate the activation code
     *
     * @param  array  $activation_code
     * @param  array  $messages
     * @return bool
     */
    public function activate($activation_code)
    {
        if (!is_null($this->activated_at)) {
        	$this->activation_error = 'already_confirmed';
        	return false;
        }
        if ($activation_code != $this->activation_code) {
        	$this->activation_error = 'invalid_code';
        	return false;
        }

        $this->activated_at = new DateTime;
        $this->save();

        return true;
    }

    /**
     * Check out a set of documents to the current user
     *
     * @param  Document[]  $documents
     * @return array
     */
    public function addLoans($documents)
    {
    	$due = new DateTime();
        date_add($due, date_interval_create_from_date_string('30 days'));

        $response = array();

        foreach ($documents as $doc) {

        	if (!$doc) {
        		die('dokumentet finnes ikke');
        	}

        	$loan = new Loan();
        	$loan->user_id = $this->id;
        	$loan->document_id = $doc->id;
            $loan->due_at = $due;
            $response[$doc->id] = $loan->save();
        }
        return $response;
    }

}