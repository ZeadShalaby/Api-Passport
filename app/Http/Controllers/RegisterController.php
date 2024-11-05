<?php


namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class RegisterController extends BaseController
{
    use ResponseTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                'password' => 'required|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $user = User::create($request->all());
            $user->token = $user->createToken('Passport')->accessToken;
            return $this->sendResponse($user, 'User register successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error occurred.', ['error' => $e->getMessage()]);
        }
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|max:20'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = User::find(auth()->user()->id);
                $user->token = $user->createToken('Passport')->accessToken;
                return $this->sendResponse($user, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (Exception $e) {
            return $this->sendError('Error occurred.', ['error' => $e->getMessage()]);
        }

    }

    /**
     * profile info api
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        try {
            return $this->returnData('data', $request->user());
        } catch (Exception $e) {
            return $this->returnError(500, $e->getCode() . "," . $e->getMessage());
        }
    }
    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // $request->user()->token()->revoke();      //? revoke token
        $request->user()->token()->delete();   //? delete token

        return $this->returnSuccessMessage('User logged out successfully.', 200);
    }


}

