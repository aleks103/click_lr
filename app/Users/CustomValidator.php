<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/22/2017
 * Time: 5:51 PM
 */

namespace App\Users;

use \Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
	public function validateLikeRestricted($attribute, $value, $parameters)
	{
		$res_words = $parameters;
		foreach ( $res_words as $word ) {
			if ( strpos($value, $word) !== false ) return false;
		}
		
		return true;
	}
	
	public function validateMatchRestricted($attribute, $value, $parameters)
	{
		$res_words = $parameters;
		
		return (!in_array($value, $res_words));
	}
	
	public function validateCheckValidLink($attribute, $value, $parameters)
	{
		$primary_link_valid = remoteFileExists($value);
		
		if ( $primary_link_valid ) {
			return true;
		}
		
		return false;
	}
}