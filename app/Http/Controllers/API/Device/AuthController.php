<?php

namespace App\Http\Controllers\API\Device;

use App\Exceptions\ChangePasswordFailureException;
use App\Exceptions\FailureResponseException;
use App\Exceptions\LoginFailedException;
use App\Exceptions\LoginUnAuthorizeException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Device\RegisterAPIRequest;
use App\Http\Requests\Device\LoginAPIRequest;
use App\Http\Requests\Device\ForgotPasswordAPIRequest;
use App\Http\Requests\Device\ResetPasswordAPIRequest;
use App\Http\Requests\Device\ValidateResetPasswordOtpApiRequest;
use App\Http\Requests\Device\ChangePasswordApiRequest;
use App\Mail\MailService;
use App\Models\User;
use App\Notifications\SendSMSNotification;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Prettus\Validator\Exceptions\ValidatorException;
use Spatie\Permission\Models\Role;

class AuthController extends AppBaseController
{
    /**
     * @var  UserRepository
     */
    private $userRepository;

    /**
    * @param  UserRepository $userRepository
    */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param  RegisterAPIRequest $request
     *
     * @throws  ValidatorException
     * @return  JsonResponse
     */
    public function register(RegisterAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
        $input["user_type"] = User::TYPE_USER;

        /** @var  User $user */
        $user = $this->userRepository->create($input);
        $data['username'] = $user->username;
        $data['link'] = URL::to('email/verify/'.Crypt::encrypt($user->email));

        $userRole = Role::find($input['role']);
        $user->assignRole($userRole);

        Mail::to($user->email)
            ->send(new MailService('emails.verify_email',
                'Verify Email Address',
                $data));

        return $this->successResponse($user);
    }
  
    /**
     * @param  LoginAPIRequest $request
     *
     * @throws  LoginFailedException
     * @throws  LoginUnAuthorizeException
     *
     * @return  JsonResponse
     */
    public function login(LoginAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        /** @var  User $user */
        $user = User::where('username', $input['username'])->first();
        if (empty($user)) {
            $user = User::where('email', $input['username'])->first();
        }

        if (empty($user)) {
            throw new LoginFailedException('User not exists.');
        }

        if (! $user->email_verified_at) {
            throw new LoginUnAuthorizeException('Your account is not verified.');
        }

        if (! $user->is_active) {
            throw new LoginUnAuthorizeException('Your account is deactivated. please contact your administrator.');
        }

        if ($user->login_retry_limit >= User::MAX_LOGIN_RETRY_LIMIT) {
            $now = Carbon::now();
            if (empty($user->login_reactive_time)) {
                $expireTime = Carbon::now()->addMinutes(User::LOGIN_REACTIVE_TIME)->toISOString();
                $user->update([
                    'login_reactive_time' => $expireTime,
                    'login_retry_limit'   => $user->login_retry_limit + 1
                ]);
                throw new LoginFailedException('you have exceed the number of limit.you can login after '.User::LOGIN_REACTIVE_TIME.' minutes.');
            }

            $limitTime = Carbon::parse($user->login_reactive_time);
            if ($limitTime > $now) {
                $expireTime = Carbon::now()->addMinutes(User::LOGIN_REACTIVE_TIME)->toISOString();
                $user->update([
                    'login_reactive_time' => $expireTime,
                    'login_retry_limit'   => $user->login_retry_limit + 1
                ]);
            }
            throw new LoginFailedException('you have exceed the number of limit.you can login after '.User::LOGIN_REACTIVE_TIME.' minutes.');
        }

        if (!Hash::check($input['password'], $user->password)) {
            $user->update([
                'login_retry_limit' => $user->login_retry_limit + 1
            ]);
            throw new LoginFailedException('Password is incorrect.');
        }

        $roles = $user->getRoleNames();
        if(!$roles->count()){
            throw new LoginFailedException('You have not assigned any role.');
        }

        if ($roles->first() != User::DEFAULT_ROLE) {
            if (is_null($user->user_type)){
                throw new LoginFailedException('You have not assigned any user type.');
            }

            if (!in_array(User::PLATFORM['DEVICE'], User::LOGIN_ACCESS[User::USER_TYPE[$user->user_type]])) {
                throw new LoginFailedException('you are unable to access this platform.');
            }
        }
 
        $data = $user->toArray();
        $data['token'] = $user->createToken('Device Login')->plainTextToken;

        $user->update([
            'login_reactive_time' => null,
            'login_retry_limit'   => 0
        ]);
        
        return $this->loginSuccess($data);
    }
    

    /**
     * Logout auth user
     *
     * @return  JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        
        return $this->successResponse('Logout successfully.');
    }

    /**
     * This function send reset password mail or sms
     *
     * @param  ForgotPasswordAPIRequest $request
     *
     * @throws  FailureResponseException
     * @return  JsonResponse
     */
    public function forgotPassword(ForgotPasswordAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        /** @var  User $user */
        $user = User::where('email', $input['email'])->firstOrFail();

        $resultOfEmail = false;
        $resultOfSMS = false;
        $code = $this->generateCode();

        if (User::FORGOT_PASSWORD_WITH['link']['email']) {
            $resultOfEmail = $this->sendEmailForResetPasswordLink($user, $code);
        }
        if (User::FORGOT_PASSWORD_WITH['link']['sms']) {
            $resultOfSMS = $this->sendSMSForResetPasswordLink($user, $code);
        }

        if ($resultOfEmail && $resultOfSMS) {
            return $this->successResponse('otp successfully send.');
        } else if ($resultOfEmail && !$resultOfSMS) {
            return $this->successResponse('otp successfully send to your email.');
        } else if (!$resultOfEmail && $resultOfSMS) {
            return $this->successResponse('otp successfully send to your mobile number.');
        } else {
            throw new FailureResponseException('otp can not be sent due to some issue try again later.');
        }
    }

    /**
     * This function will send reset password email to given user.
     *
     * @param  ResetPasswordAPIRequest $request
     *
     * @return  JsonResponse
     */
    public function resetPassword(ResetPasswordAPIRequest $request): JsonResponse
    {
        $input = $request->all();
        /** @var  User $user */
        $user = User::where('reset_password_code', $input['code'])->first();
        if ($user && $user->reset_password_expire_time) {
            if (Carbon::now()->isAfter($user->reset_password_expire_time)) {
                return $this->errorResponse('Your reset password link is expired on invalid.');
            }
        } else {
            return $this->errorResponse('Invalid Code.');
        }

        $user->update([
            'password'                   => Hash::make($input['new_password']),
            'reset_password_expire_time' => null,
            'login_retry_limit'          => 0,
            'reset_password_code'        => null
        ]);

        $data['username'] = $user->username;
        $data['message'] = 'Your Password Successfully Reset';
        Mail::to($user->email)
            ->send(new MailService('emails.password_reset_success',
                'Reset Password',
                $data));

        return $this->successResponse('Password reset successful.');
    }

    /**
     * @param  ValidateResetPasswordOtpApiRequest $request
     *
     * @return  JsonResponse
     */
    public function validateResetPasswordOtp(ValidateResetPasswordOtpApiRequest $request): JsonResponse
    {
        $input = $request->all();
        /** @var  User $user */
        $user = User::where('reset_password_code', $input['otp'])->first();
        if (!$user || !$user->reset_password_expire_time) {
            return $this->errorResponse('Invalid OTP.');
        }

        // link expire
        if (Carbon::now()->isAfter($user->reset_password_expire_time)) {
            return $this->errorResponse('Your reset password link is expired or invalid.');
        }

        return $this->successResponse('Otp verified.');
    }

    /**
     * @param  $user
     * @param  string $code
     *
     * @return  bool
     */
    public function sendEmailForResetPasswordLink($user,string $code): bool
    {
        $expireTime = Carbon::now()->addMinutes(User::FORGOT_PASSWORD_WITH['expire_time'])->toISOString();
        $user->update([
            'reset_password_expire_time' => $expireTime,
            'reset_password_code'        => $code
        ]);

        // mail send code
        $data['link'] = URL::to('reset-password/'.$code);
        $data['expireTime'] = User::FORGOT_PASSWORD_WITH['expire_time'];
        $data['message'] = 'Click on the link below to reset your password.';
        Mail::to($user->email)
            ->send(new MailService('emails.password_reset',
                'Reset Password',
                $data));

        return true;
    }

    /**
     * @param  $user
     * @param  string $code
     *
     * @return  bool
     */
    public function sendSMSForResetPasswordLink($user,string $code): bool
    {
        $expireTime = Carbon::now()->addMinutes(User::FORGOT_PASSWORD_WITH['expire_time'])->toISOString();
        $user->update([
            'reset_password_expire_time' => $expireTime,
            'reset_password_code'        => $code
        ]);

        // sms send code
        $user->notify(new SendSMSNotification());
        
        return true;
    }

    /**
     * Change password of logged in user.
     *
     * @param  ChangePasswordApiRequest $request
     *
     * @throws  ChangePasswordFailureException
     * @return  JsonResponse
     */
    public function changePassword(ChangePasswordApiRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var  User $user */
        $user = Auth::user();
        if (!Hash::check($input['old_password'],  $user->password)) {
            throw new ChangePasswordFailureException('Current password is invalid.');
        }
        $input['password'] = Hash::make($input['new_password']);
        $user->update($input);

        return $this->changePasswordSuccess('Password Updated Successfully.');
    }

    /**
     * Generate unique code to reset password of given user.
     *
     * @return  string
     */
    public function generateCode(): string
    {
        $code = Str::random(6);
        while (true) {
            $codeExists = User::where('reset_password_code', $code)->exists();
            if ($codeExists) {
                return $this->generateCode();
            }
            break;
        }

        return $code;
    }
}
