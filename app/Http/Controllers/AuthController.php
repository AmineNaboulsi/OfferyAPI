<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }
    /**
    * @OA\POST(
    *      path="/login",
    *      summary="SignIn",
    *      description="SignIn",
    *      @AO\Parameter(
    *            name="email",
    *            in="body",
    *            required=true,
    *            @OA\Schema(
    *                type="string"
    *            )
    *      ),
    *       @AO\Parameter(
    *            name="password",
    *            in="body",
    *            required=true,
    *            @OA\Schema(
    *                type="string"
    *            )
    *      ),
    *      tags={"Auth"},
    *      @OA\Info(),
    *      @OA\Response(response="200", description="login successfuly"),
    *      @OA\Response(response="404", description="Account not found"),
    * )
    */
    public function signin(Request $Request){
       try{
          $credentials = $Request->validate([
             "email" => ['required' , 'email' ,'string' ] ,
             "password" => ['required' , 'string']
          ]);

          if (! $token = auth()->attempt($credentials)) {
                return response()->json(['message' => 'Account not found'], 401);
          }else{
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
         }
       }catch(\Exception $e){
          return response()->json([
             "message" => $e->getMessage()
          ]);
       }
    }
    /**
    * @OA\POST(
    *      path="/register",
    *      summary="Register",
    *      description="Register a new user",
    *      tags={"Auth"},
    *      @OA\Response(response="200", description="User registered successfully"),
    *      @OA\Response(response="422", description="Validation errors")
    * )
    */
    public function register(Request $request){
       try{
          $validatedData = $request->validate([
             "name" => ['required', 'string', 'max:255'],
             "email" => ['required', 'string', 'email', 'max:255', 'unique:users'],
             "password" => ['required', 'string', 'min:8'],
          ]);
          $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'ip_address' => request()->ip(),
                'mac_address' => $this->getMacAddress(),
                'user_agent' => request()->header('User-Agent'),
                'device_type' => $this->getDeviceType(request()->header('User-Agent'))
          ]);

          $token = auth()->login($user);

          return response()->json([
             "message" => "User registered successfully",
             "token" => $token,
             "user" => $user
        ], 201);

       }catch(\Exception $e){
          return response()->json([
             "message" => $e->getMessage()
          ], 422);
       }
    }
    public function getMacAddress()
    {
        $ipAddress = request()->ip();

        if ($ipAddress == '127.0.0.1' || $ipAddress == '::1') {
            $macAddress = exec("getmac");
        } else {
            $macAddress = exec("arp -a $ipAddress");
        }

        $regex = '/([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})/';
        if (preg_match($regex, $macAddress, $matches)) {
            return $matches[0];
        }

        return null;
    }
    private function parseDeviceType($userAgent)
    {
        // Check for Windows
        if (strpos($userAgent, 'Windows') !== false) {
            return 'windows';
        }
        // Check for Mac
        elseif (strpos($userAgent, 'Macintosh') !== false) {
            return 'mac';
        }
        // Check for iPhone/iPad
        elseif (strpos($userAgent, 'iPhone') !== false) {
            return 'iphone';
        }
        elseif (strpos($userAgent, 'iPad') !== false) {
            return 'ipad';
        }
        // Check for Android
        elseif (strpos($userAgent, 'Android') !== false) {
            // Check if it's a tablet
            if (strpos($userAgent, 'Mobile') === false) {
                return 'android_tablet';
            }
            return 'android_mobile';
        }
        // Check for Linux
        elseif (strpos($userAgent, 'Linux') !== false) {
            return 'linux';
        }
        // Default
        else {
            return 'other';
        }
    }


    private function getDeviceType($userAgent)
    {
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4))) {
            // Mobile device
            if (strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'tablet') !== false) {
                return 'tablet';
            }
            return 'mobile';
        } elseif (strpos($userAgent, 'Windows') !== false) {
            return 'windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            return 'mac';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'linux';
        } else {
            return 'unknown';
        }
    }
}
