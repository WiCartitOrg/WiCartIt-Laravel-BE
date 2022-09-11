<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

use App\Validators\Vendor\VendorAccessRequestRules;

use App\Services\Interfaces\Vendor\VendorAccessInterface;
use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;
use App\Services\Traits\Utilities\ComputeUniqueIDService;
use App\Services\Traits\Utilities\PassHashVerifyService;

use App\Services\Traits\AuthAbstractions\Vendor\EmailVerificationNotificationAbstraction;
use App\Services\Traits\AuthAbstractions\Vendor\VerifyEmailAbstraction;
use App\Services\Traits\AuthAbstractions\Vendor\PasswordResetLinkAbstraction;
use App\Services\Traits\AuthAbstractions\Vendor\NewPasswordAbstraction;


final class VendorAccessController extends Controller implements VendorAccessInterface
{
    use VendorAccessRequestRules;
    use VendorAccessAbstraction;
    use ComputeUniqueIDService;
    use PassHashVerifyService;

    use EmailVerificationNotificationAbstraction;
    use VerifyEmailAbstraction;
    use PasswordResetLinkAbstraction;
    use NewPasswordAbstraction;
    

    public function __construct()
    {
        //initialize Vendor Object:
        //public $Vendor = new Vendor;
    }
    

    public function Register(Request $request): JsonResponse 
    {
        $status = array();
        try
        {
            //get rules from validator class:
            $reqRules = $this->registerRules();

            //first validate the requests:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                //throw Exception:
                throw new \Exception("Invalid Input(s) provided!");
            }

            //pass the validated value to the Model Abstraction Service: 
            $details_was_partially_saved = $this->VendorRegisterService($request);
            if(!$details_was_partially_saved)
            {
                throw new \Exception("Your details could not be registered. Please try again!"); 
            }

            //since password can't be saved through mass assignment, so save specific:
            $hashedPass = $this->CustomHashPassword($request->vendor_password);

            /*Vendor unique id can't be saved through mass assignment, so save specific:
            //This will be used in the Authorization process:*/
            $uniqueID = $this->genUniqueAlphaNumID();

            $queryKeysValues = [
                'vendor_email' => $request->vendor_email
            ];

            $newKeysValues = [ 
                'vendor_password' => $hashedPass, 
                'unique_vendor_id' => $uniqueID 
            ];

            $pass_id_were_saved = $this->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);
            if(!$pass_id_were_saved)
            {
                //delete the formerly saved data if password and id are not saved:
                $deleteKeysValues = ['vendor_email' => $request->vendor_email];
                $partially_saved_data_was_deleted = $this->VendorDeleteAllNullService($deleteKeysValues);

                if($partially_saved_data_was_deleted)
                {
                    //then throw new Exception:
                    throw new \Exception("Your details could not be registered. Please try again!"); 
                }
            }
            
            //After all these, send the mail for vendor email verification:
            $verify_link_was_sent = $this->SendVerificationReqMail($request, 'vendor.verifications.verify', [
                'verify' => $uniqueID, 
                'answer' => 'done',
            ]);

            if(!$verify_link_was_sent)
            {
                //delete all records of this vendor:
                $deleteKeysValues = ['vendor_email' => $request->vendor_email];
                $this->VendorDeleteSpecificService($deleteKeysValues);
                //throw Exception:
                throw new \Exception("Verification Request Mail wasn't sent successfully!");
            }
            
            $status = [
                'code' => 1,
                'serverStatus' => 'RegisterSuccess!',
                'short_description' => 'E-mail Verification Link Sent. Please Verify your E-mail to continue!',
                'ver_lk' => $verify_link_was_sent
            ];

        }
        catch(\Exception $ex)
        {
            //if a vendor has registered before with this same email:

            /*$duplicationWarning1 = "Integrity constraint violation";
            $duplicationWarning2 = "SQLSTATE[23000]";*/
            $DUPLICATION_WARNING = '1062 Duplicate entry';

            $status = [
                'code' => 0,
                'serverStatus' => 'RegisterFailure!',
                'short_description' => $ex->getMessage()
            ];

            if( 
                str_contains($status['short_description'], $DUPLICATION_WARNING) 
            )
            {
                $status['short_description'] = '';
                $status['warning'] = 'Either Your Email, Password have been used! Try Another.';
            }

            return response()->json($status, 400);
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }
    

    public function VerifyEmail(string $verify, string $answer): JsonResponse
    {
        $status = array();
        try
        {
            $confirm_verify_state = $this->VendorConfirmVerifiedStateViaId($verify);

            if(!$confirm_verify_state)
            {
                $verify_state_was_changed = $this->VendorChangeVerifiedState($verify);
                if(!$verify_state_was_changed)
                {
                    throw new \Exception("Vendor Email was not verified!");
                }
    
                $status = [
                    'code' => 1,
                    'serverStatus' => 'VerifiedSuccess!',
                    'short_description' => 'Redirect to homepage here!'
                ];
            }
            else
            {
                 //redirect to home page:
                 $status = [
                    'code' => 1,
                    'serverStatus' => 'VerifiedAlready!',
                    'short_description' => 'Redirect to homepage here!'
                ];
            }

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'VerifiedFailure!',
                'short_description' => $ex->getMessage(),
            ];

            return response()->json($status, 400);
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
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

            $detailsFound = $this->VendorDetailsFoundService($request);
            if(!$detailsFound)
            {
                throw new \Exception("Failed login attempt. Invalid Email Provided!");
            }

            //verify password against the hashed password in the database:
            $pass_was_verified = $this->CustomVerifyPassword($request->vendor_password, $detailsFound['vendor_password']);

            if(!$pass_was_verified)
            {
                throw new \Exception("Failed login attempt. Invalid Password Provided!");
            }

            //now start to prepare to update login status:

            //set query:
            $queryKeysValues = ['unique_vendor_id' => $detailsFound['unique_vendor_id']];

            //set the is_logged_in status as true:
            $newKeysValues = ['is_logged_in' => true];

            $login_status_was_changed = $this->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);

            if(!$login_status_was_changed)
            {
                throw new \Exception("Failed login attempt. Please try again!");
            }

            //After logged in state was set, start generating the Bearer token for Authorization Header...

            //Now create the token:
            $auth_header_token = $detailsFound->createToken('auth_header_token', ['vendor-crud']);
            $auth_header_token_text = $auth_header_token->plainTextToken;

            $status = [
                'code' => 1,
                'serverStatus' => 'LoginSuccess!',
                //for subsequent calls inside the dashboard:
                'vendorUniqueId' => $detailsFound['unique_vendor_id'],//for Vendor Authentication
                'vendorAuthToken' => $auth_header_token_text, //for Authorization header using Sanctum...
                'decription' => 'For subsequent calls to the dashboard endpoints, vendorUniqueId must be included in the request body while vendorAuthToken must be included in the Authorization header as a Bearer Token'
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
        finally
        {
            return response()->json($status, 200);
        }
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

            $password_was_updated = $this->VendorUpdatePasswordService($request);

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

            //Get this vendor unique_vendor_id:
            $queryKeysValues = ['vendor_email'=>$request->vendor_email];
            $uniqueID = $this->VendorReadSpecificService($queryKeysValues)->unique_vendor_id;

            //Use this to form link:
            $pass_reset_link_was_sent = $this->SendPasswordResetMail($request, 'vendor.click.link.reset.password', [
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
            $confirm_verify_state = $this->VendorConfirmVerifiedStateViaId($reset);

            if(!$confirm_verify_state)
            {
                throw new \LogicException("You can change your password only after your email has been verified! Please verify your email now to continue!");
            }

            //After this, return all both the vendor unique id and sanctum token (for Auth header):
            $queryKeysValues = ['unique_vendor_id' => $reset];
            $vendorObject = $this?->VendorReadSpecificService($queryKeysValues);
            //Now create new sanctum token :
            $auth_header_token = $vendorObject->createToken('auth_header_token', ['vendor-update']);
            $auth_header_token_text = $auth_header_token?->plainTextToken;

            $status = [
                'code' => 1,
                'serverStatus' => 'ResetAccessGranted!',
                //for subsequent calls inside the dashboard:
                'vendorUniqueId' => $vendorObject['unique_vendor_id'],//for Vendor Authentication
                'vendorAuthToken' => $auth_header_token_text, //for Authorization header using Sanctum...
                'decription' => "For the next password reset request (user interface), vendorUniqueId must be included in the request body while vendorAuthToken must be included in the Authorization header as a Bearer Token"
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

            $password_was_updated = $this->VendorUpdatePasswordService($request);

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

            $has_logged_out = $this->VendorLogoutService($request);
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
