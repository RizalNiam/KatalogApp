<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Validator;
 
 
class UserController extends Controller
{
    use ApiResponses;
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make(request()->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'confirm_password' => 'required|string|same:password|min:8|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'register gagal, silahkan coba kembali');
        }
        
        $user = User::where('username', $request->username)->first();

        if ($user) {
            // Jika nomor telepon sudah terdaftar, kirim response dengan pesan error
            return $this->badRequest('Sorry the username number is already used. Please use a different one');
        }
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            // Jika nomor telepon sudah terdaftar, kirim response dengan pesan error
            return $this->badRequest('Sorry the phone number is already used. Please use a different one');
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Jika nomor email sudah terdaftar, kirim response dengan pesan error
            return $this->badRequest('Sorry the email is already used. Please use a different one');
        }

        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());


        return $this->requestSuccess('Registrstion Success', '200');
    }
 
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $input = request(['username', 'password']);
 
        if (! $token = auth('api')->attempt($input)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('api')->user();
    
        $data = DB::table('users')
            ->select('id', 'username', 'phone', 'email', 'created_at', 'updated_at')
            ->where('users.id', '=', $user->id)
            ->get();
 
        return $this->loginSuccess($data[0], $token);
    }

    public function getprofile()
    {
        $user = auth("api")->user();

        $rawData = DB::table('users')
        ->select('id', 'username', 'phone', 'email', 'created_at', 'updated_at')
        ->where('users.id', '=', $user->id)
            ->first(); 
        
        return $this->requestSuccessData('Success!', $rawData);
    }
 
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
 
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
 
        return response()->json(['message' => 'Successfully logged out']);
    }
 
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
 
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}