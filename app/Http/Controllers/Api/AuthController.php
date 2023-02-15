<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function sendResponse($data, $message, $status = 200)
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }


    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function register(Request $request)
    {
        $input = $request->only('username', 'mobile_number', 'password');
        $validator = Validator::make($input, [
            'username' => 'required',
            'mobile_number' => 'required|',
            'password' => 'required|min:6',
        ]);

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid =  getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($input['mobile_number'], "sms");

        $user = User::create([
            'username' => $input['username'],
            'mobile_number' => $input['mobile_number'],
            'password' => Hash::make($input['password']),
        ]); // eloquent creation of data

        $success['user'] = $user;

        return $this->sendResponse($success, 'user registered successfully', 201);

    }



    protected function verify(Request $request)
    {

        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'mobile_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create([
                'code' => $data['verification_code'],
                'to' => $data['mobile_number']
            ]);
        print($verification->status);
        if ($verification->valid) {
            $user = tap(User::where('mobile_number', $data['mobile_number']))->update(['isVerified' => true]);
            /* Authenticate user */
            Auth::login($user->first());
        }
            return response()->json(
                ['message' => 'Invalid code'],
                500
            );
    }



    public function login(Request $request)
    {
        $input = $request->only('mobile_number', 'password');

        $validator = Validator::make($input, [
            'mobile_number' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 'Validation Error', 422);
        }

        try {
            // this authenticates the user details with the database and generates a token
            if (! $token = JWTAuth::attempt($input)) {
                return $this->sendError([], "invalid login credentials", 400);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }

        $success = [
            'token' => $token,
        ];
        return $this->sendResponse($success, 'successful login', 200);
    }


    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully logout']);
    }

    public function userProfile() {
        return response()->json(auth('api')->user());
    }

}
