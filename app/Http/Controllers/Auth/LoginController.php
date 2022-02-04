<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
        /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {

        try{
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user  = User::where('provider_id',$githubUser->getId())->first();

            //Create a new user in our database

            if(!$user){

                $user  =  User::create([
                    'email' => $githubUser->getEmail(),
                    'name' => $githubUser->getName(),
                    'provider_id'=> $githubUser->getId(),
                ]);

            }
     
            //Log the user in 
     
            auth()->login($user,true);
     
            //Redirect to dashboard
            return redirect('dashboard');

            
        }catch(\Exception $e){

            dd($e);

        }
       
    }
}
