<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

use App\Validators\Admin\AdminAccessRequestRules;

use App\Services\Interfaces\Admin\AdminAccessInterface;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;
use App\Services\Traits\Utilities\ComputeUniqueIDService;
use App\Services\Traits\Utilities\PassHashVerifyService;

use App\Services\Traits\AuthAbstractions\Admin\PasswordResetLinkAbstraction;
use App\Services\Traits\AuthAbstractions\Admin\NewPasswordAbstraction;


final class AdminAccessController extends Controller implements AdminAccessInterface
{
    use AdminAccessRequestRules;
    use AdminAccessAbstraction;
    use ComputeUniqueIDService;
    use PassHashVerifyService;

    use PasswordResetLinkAbstraction;
    use NewPasswordAbstraction;
    

    public function __construct()
    {
        //initialize Admin Object:
        //public $Admin = new Admin;
    }
    
    public function LoginDashboard(Request $request): JsonResponse
    {
        $status = array();
        try
        {
            //get rules from validator class:
            $reqRules = $this->loginRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            $detailsFound = $this->AdminDetailsFoundService($request);
            if(!$detailsFound)
            {
                throw new \Exception("Failed login attempt. Invalid Email Provided!");
            }

            //verify password against the hashed password in the database:
            $pass_was_verified = $this->CustomVerifyPassword($request->admin_password, $detailsFound['admin_password']);

            if(!$pass_was_verified)
            {
                throw new \Exception("Failed login attempt. Invalid Password Provided!");
            }

            //now start to prepare to update login status:

            //set query:
            $queryKeysValues = ['unique_admin_id' => $detailsFound['unique_admin_id']];

            //set the is_logged_in status as true:
            $newKeysValues = ['is_logged_in' => true];

            $login_status_was_changed = $this->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);

            if(!$login_status_was_changed)
            {
                throw new \Exception("Failed login attempt. Please try again!");
            }

            //After logged in state was set, start generating the Bearer token for Authorization Header...

            //Now create the token based on roles - boss and hired:
            switch($detailsFound->admin_role)
            {
                case 'boss':
                {
                    //Now create the token:
                    $admin_boss_header_token = $detailsFound->createToken('admin_boss_header_token', ['admin-crud, admin-boss, admin-hired']);
                    $admin_boss_header_token_text = $admin_boss_header_token->plainTextToken;
                    $status['adminAuthToken'] = $admin_boss_header_token_text; //for boss Authorization header using Sanctum...
                }
                case 'hired':
                {
                    //Now create the token:
                    $admin_hired_header_token = $detailsFound->createToken('admin_hired_header_token', ['admin-crud, admin-hired']);
                    $admin_hired_header_token_text = $admin_hired_header_token->plainTextToken;
                    $status['adminAuthToken'] = $admin_hired_header_token_text; //for hired Authorization header using Sanctum...
                }  
            }
            
            $status = [
                'code' => 1,
                'serverStatus' => 'LoginSuccess!',
                //for subsequent calls inside the dashboard:
                'adminUniqueId' => $detailsFound['unique_admin_id'],//for Admin Authentication
                'decription' => 'For subsequent calls to the dashboard endpoints, adminUniqueId must be included in the request body while adminAuthToken must be included in the Authorization header as a Bearer Token'
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'LoginFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }

    
    //for logged in users, from the dashboard:
    public function ChangePassword(Request $request): JsonResponse
    {
        $status = array();

        try
        {       
            //get rules from validator class:
            $reqRules = $this->changePasswordRules();

            //validate here:'new_pass'
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            $password_was_updated = $this->AdminUpdatePasswordService($request);

            if(!$password_was_updated)
            {
                throw new \Exception("Password could not be changed!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'PassUpdateSuccess!',
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'PassUpdateFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }


    //For Guests(Logged out users):
    public function SendResetPassLink(Request $request): JsonResponse
    {
        $status = array();
        try
        {
             //get rules from validator class:
             $reqRules = $this->sendRequestPassLinkRules();

             //validate here:
             $validator = Validator::make($request->all(), $reqRules);
 
            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //Get this admin unique_admin_id:
            $queryKeysValues = ['admin_email'=>$request->admin_email];
            $uniqueID = $this->AdminReadSpecificService($queryKeysValues)->unique_admin_id;

            //Use this to form link:
            $pass_reset_link_was_sent = $this->SendPasswordResetMail($request, 'admin.click.link.reset.password', [
                'reset' => $uniqueID,
                'answer' => 'done',
            ]);

            if(!$pass_reset_link_was_sent)
            {
                //throw Exception:
                throw new \Exception("Password Reset Link was not sent!");
            }
            $status = [
                'code' => 1,
                'serverStatus' => 'ResetLinkSent!',
                'pass_reset_link' =>  $pass_reset_link_was_sent
            ];
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'ResetLinkNotSent!',
                'short_description' => $ex->getMessage(),
            ];
        }
        /*finally
        {*/
            //return response:
            return response()->json($status, 200);
        //}
    }
    

    //for logged out user, outside the dashboard:
    public function ClickResetPasswordLink(string $reset, string $answer): JsonResponse
    {
        $status = array();
        try
        {
            $confirm_verify_state = $this->AdminConfirmVerifiedStateViaId($reset);

            if(!$confirm_verify_state)
            {
                throw new \LogicException("You can change your password only after your email has been verified! Please verify your email now to continue!");
            }

            //After this, return all both the admin unique id and sanctum token (for Auth header):
            $queryKeysValues = ['unique_admin_id' => $reset];
            $adminObject = $this?->AdminReadSpecificService($queryKeysValues);
            //Now create new sanctum token :
            $auth_header_token = $adminObject->createToken('auth_header_token', ['admin-update']);
            $auth_header_token_text = $auth_header_token?->plainTextToken;

            $status = [
                'code' => 1,
                'serverStatus' => 'ResetAccessGranted!',
                //for subsequent calls inside the dashboard:
                'adminUniqueId' => $adminObject['unique_admin_id'],//for Admin Authentication
                'adminAuthToken' => $auth_header_token_text, //for Authorization header using Sanctum...
                'decription' => "For the next password reset request (user interface), adminUniqueId must be included in the request body while adminAuthToken must be included in the Authorization header as a Bearer Token"
            ];
    
        }
        catch(\LogicException $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'ResetAccessRevoked!',
                'short_description' => $ex->getMessage(),
            ];

            return response()->json($status, 400);
        }
        
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }
   

    public function ImplementResetPassword(Request $request): JsonResponse
    {
        $status = array();

        try
        {       
            //get rules from validator class:
            $reqRules = $this->implementResetPasswordRules();

            //validate here:'new_pass'
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            $password_was_updated = $this->AdminUpdatePasswordService($request);

            if(!$password_was_updated)
            {
                throw new \Exception("Password could not be changed!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'PassResetSuccess!',
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'PassResetFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }  


    public function Logout(Request $request):  JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->logoutRules();

            //validate here:'new_pass'
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Access denied, not logged in!");
            }

            $has_logged_out = $this->AdminLogoutService($request);
            if(!$has_logged_out/*false*/)
            {
                throw new \Exception("Not logged out yet!");
            }
            
            //build response:
            $status = [
                'code' => 1,
                'serverStatus' => 'LogOutSuccess!',
            ];

        }
        catch(\Exception $ex)
        {
             $status = [
                'code' => 0,
                'serverStatus' => 'LogOutFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        finally
        {
            return response()->json($status, 200);
        }
    }

}

?>
