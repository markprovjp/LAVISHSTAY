<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản mới
     */
    public function register(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ], [
                'name.required' => 'Tên là bắt buộc',
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không đúng định dạng',
                'email.unique' => 'Email này đã được sử dụng',
                'password.required' => 'Mật khẩu là bắt buộc',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tạo user mới
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            // Tạo token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                    'token' => $token,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đăng nhập
     */
    public function login(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không đúng định dạng',
                'password.required' => 'Mật khẩu là bắt buộc',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Kiểm tra thông tin đăng nhập
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không đúng'
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                    'token' => $token,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        try {
            // Xóa token hiện tại
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đăng nhập bằng Google
     */
    public function googleLogin(Request $request)
    {
        try {
            // Validate input - hỗ trợ cả access_token và authorization code
            $validator = Validator::make($request->all(), [
                'access_token' => 'required_without:code|string',
                'code' => 'required_without:access_token|string',
            ], [
                'access_token.required_without' => 'Access token hoặc authorization code là bắt buộc',
                'code.required_without' => 'Access token hoặc authorization code là bắt buộc',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $googleUser = null;

            // Xử lý authorization code flow
            if ($request->has('code')) {
                $googleUser = $this->handleAuthorizationCode($request->code);
            }
            // Xử lý access token flow
            elseif ($request->has('access_token')) {
                $googleUser = $this->getGoogleUserInfo($request->access_token);
            }
            
            if (!$googleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google không hợp lệ hoặc đã hết hạn'
                ], 401);
            }

            // Tìm user theo email hoặc google_id
            $user = User::where('email', $googleUser['email'])
                       ->orWhere('google_id', $googleUser['id'])
                       ->first();

            if ($user) {
                // User đã tồn tại, cập nhật google_id nếu chưa có
                if (!$user->google_id) {
                    $user->google_id = $googleUser['id'];
                    $user->avatar = $googleUser['picture'] ?? null;
                    $user->save();
                }
                
                // Cập nhật avatar nếu có
                if (isset($googleUser['picture']) && $googleUser['picture']) {
                    $user->avatar = $googleUser['picture'];
                    $user->save();
                }
            } else {
                // Tạo user mới
                $user = User::create([
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'email_verified_at' => now(), // Google account đã verified
                    'password' => Hash::make(uniqid()), // Random password
                ]);
            }

            // Tạo token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập Google thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'avatar' => $user->avatar,
                        'google_id' => $user->google_id,
                    ],
                    'token' => $token,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý authorization code từ Google OAuth redirect
     */
    private function handleAuthorizationCode($code)
    {
        try {
            $client = new \Google\Client();
            $client->setClientId(config('google.client_id'));
            $client->setClientSecret(config('google.client_secret'));
            $client->setRedirectUri(config('google.redirect_uri'));
            
            // Cấu hình cURL để bỏ qua SSL verification trong development
            $httpClient = new \GuzzleHttp\Client([
                'verify' => false, // Bỏ qua SSL verification
                'timeout' => 30,
            ]);
            $client->setHttpClient($httpClient);
            
            \Log::info('Google OAuth Config:', [
                'client_id' => config('google.client_id'),
                'redirect_uri' => config('google.redirect_uri'),
                'code' => substr($code, 0, 20) . '...' // Log một phần code để debug
            ]);
            
            // Exchange authorization code for access token
            $tokenResponse = $client->fetchAccessTokenWithAuthCode($code);
            
            \Log::info('Token response:', $tokenResponse);
            
            if (isset($tokenResponse['error'])) {
                \Log::error('Google token exchange error: ' . json_encode($tokenResponse));
                return null;
            }
            
            $accessToken = $tokenResponse['access_token'];
            $idToken = $tokenResponse['id_token'] ?? null;
            
            // Sử dụng ID token nếu có, nếu không thì dùng access token
            if ($idToken) {
                $payload = $client->verifyIdToken($idToken);
                if ($payload) {
                    return [
                        'id' => $payload['sub'],
                        'email' => $payload['email'],
                        'name' => $payload['name'],
                        'picture' => $payload['picture'] ?? null,
                        'email_verified' => $payload['email_verified'] ?? false
                    ];
                }
            }
            
            // Fallback to access token
            return $this->getGoogleUserInfo($accessToken);
            
        } catch (\Exception $e) {
            \Log::error('Authorization code handling error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Lấy thông tin user từ Google API
     */
    private function getGoogleUserInfo($token)
    {
        try {
            $client = new \Google\Client();
            $client->setClientId(config('google.client_id'));
            $client->setClientSecret(config('google.client_secret'));
            
            // Cấu hình cURL để bỏ qua SSL verification trong development
            $httpClient = new \GuzzleHttp\Client([
                'verify' => false, // Bỏ qua SSL verification
                'timeout' => 30,
            ]);
            $client->setHttpClient($httpClient);
            
            // Thử verify ID token trước
            try {
                $payload = $client->verifyIdToken($token);
                if ($payload) {
                    return [
                        'id' => $payload['sub'],
                        'email' => $payload['email'],
                        'name' => $payload['name'],
                        'picture' => $payload['picture'] ?? null,
                        'email_verified' => $payload['email_verified'] ?? false
                    ];
                }
            } catch (\Exception $e) {
                // Nếu verify ID token thất bại, thử sử dụng access token
                \Log::info('ID token verification failed, trying access token: ' . $e->getMessage());
            }
            
            // Sử dụng access token để lấy thông tin user
            $client->setAccessToken($token);
            $oauth2 = new \Google\Service\Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            
            return [
                'id' => $userInfo->id,
                'email' => $userInfo->email,
                'name' => $userInfo->name,
                'picture' => $userInfo->picture ?? null,
                'email_verified' => $userInfo->verifiedEmail ?? false
            ];
            
        } catch (\Google\Exception $e) {
            \Log::error('Google API Error: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            \Log::error('General Error in getGoogleUserInfo: ' . $e->getMessage());
            return null;
        }
    }
}
