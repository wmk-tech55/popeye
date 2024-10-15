<?php

namespace MixCode\Http\Controllers\Auth;

use MixCode\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MixCode\User;
use Validator, Redirect, Response, File;
use Socialite;

class SocialAuthController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback($provider)
	{
		$getInfo = Socialite::driver($provider)->user();
		
		auth()->login(
			$this->createUser($getInfo, $provider)
		);

		return redirect()->to('/');
	}

	function createUser($getInfo, $provider)
	{
		$user = User::where('provider_id', $getInfo->id)->first();
		
		if(!! $user) {
			return $user;
		}

		return (new User())->register([
			'full_name'     	=> $getInfo->name,
			'email'         	=> $getInfo->email,
			'phone'         	=> '00000000000',
			'password'      	=> $getInfo->email,
			'provider'      	=> $provider,
			'provider_id'   	=> $getInfo->id
		]);
	}
}
