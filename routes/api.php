<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) 
{
    return $request->user();//returns authenticated user using the laravel sanctum...
});*/

//Buyer Auth:
Route::prefix('v1/buyer/')->group(function() 
{

	Route::middleware('BuyerCleanNullRecords')->group(function()
	{
		Route::post('register', [
			'as' => 'buyer.register',
			//'middleware' => '',
			'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@Register'
		]);

		Route::get('verifications/{verify}/{answer}', [
			'as' => 'buyer.verifications.verify',
			'middleware' => [/*'auth',*/'signed', 'throttle:6,1'],
			'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@VerifyEmail'
		]);
	
		Route::patch('dashboard/login', [
			'as' => 'buyer.login',
			'middleware' => 'BuyerConfirmVerifyState',
			'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@LoginDashboard'
		]);

        Route::patch('dashboard/logout', [
			'as' => 'buyer.logout',
			'middleware' => ['BuyerConfirmVerifyState', 'BuyerConfirmLoginState', 'DestroyTokenAfterLogout', 'auth:sanctum', 'ability:buyer-crud'],
			'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@Logout'
        ]);

		//This is only for guests who hasn't logged in yet: send the password reset link to the user gmail:
		Route::post('logged-out/send/forgot-password/link', 
			[App\Http\Controllers\Buyer\Auth\BuyerAccessController::class, 'SendResetPassLink']
		)->middleware('guest')
		->name('buyer.password.reset.link');

        //for logged out user: Reset Passsword 
        /*This option will be presented to Logged Out users: As this will be outside the dashboard, :*/
        Route::get('clicked/logged-out/reset/{reset}/{answer}', [
            'as' => 'buyer.click.link.reset.password',
            'middleware' => [/*'auth',*/'signed', 'throttle:6,1'],
            'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@ClickResetPasswordLink',
        ]);

		//This option will be presented to the already logged in user: 
		Route::put('auth/reset/password', [
			'as' => 'buyer.reset.password',
			'middleware' => ['BuyerEnsureLogoutState','DestroyTokenAfterLogout', 'auth:sanctum', 'ability:buyer-update'],
			'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@ImplementResetPassword'
		]);
	});
});


Route::group(['prefix' => 'v1/buyer/'], function()
{
	Route::controller(App\Http\Controllers\Vendor\BuyerProductController::class)->group(function()
	{
		//all products to allow for frontend complex search by the JS dev:
		Route::get('fetch/available/products/all', [
			'as' => 'fetch.all.products',
			//'middleware' => 'init',
			'uses' => 'FetchAllProducts'
		]);

		/*products search by specific category, the search here is just simple one-keyword search, 
		the JS frontend can perform most of the complex queries:*/
		Route::get('fetch/available/products/by/search/{category}', [
			'as' => 'search.products',
			//'middleware' => 'init',
			'uses' => 'FetchProductsByCategory'
		]);	


		Route::middleware(['auth:sanctum', 'ability:buyer-crud'])->group(function()
		{
			Route::get('fetch/all/products/summary', [
				'as' => 'search.products',
				'middleware' => 'BuyerConfirmLoginState',
				'uses' => 'FetchAllProductsSummary'
			]);
		
			//this is for when the buyer searches for details of the goods that he already has the summary...
			//use json for loop in the frontend-JS...
			Route::post('view/specific/product&vendor/details', [
				'as' => 'fetch.specific.product&vendor', 
				'middleware' => ['BuyerConfirmLoginState', 'BuyerConfirmVerifyState'],
    			'uses' => 'FetchSpecificProductWithVendorDetails'
			]);
		});
	});
});		


//Buyer Group: has "api/" by default: 
//use sanctum to authenticate all dashboard endpoint requests:
Route::group(['prefix' => 'v1/buyer/', 'middleware' => ['BuyerConfirmLoginState', 'BuyerConfirmVerifyState', 'auth:sanctum', 'ability:buyer-crud']], function()
{
	
	Route::prefix('dashboard/utils/')->group(function() 
	{
		Route::prefix('profile/')->group(function()
		{
			//This option will be presented to the already logged in user: 
			Route::put('logged-in/change-password', [
				'as' => 'buyer.change.password',
				//'middleware' => 'init',
				'uses' => 'App\Http\Controllers\Buyer\Auth\BuyerAccessController@ChangePassword'
			]);

			//this might include payment details like credit card info..
			Route::put('edit/profile', [
				//'as' => 'forgot_password',
				//'middleware' => 'init',
				'uses' => '\Buyer\BuyerProfileAssetsController@EditProfileDetails'
			]);

			//optional pictures:
			Route::put('edit/image', [
				//'as' => 'forgot_password',
				//'middleware' => 'init',
				'uses' => '\Buyer\BuyerProfileAssetsController@EditProfileImage'
			]);

		});
		

		Route::group(['prefix' => 'cart/', 'middleware' => ['DeleteEmptyCarts']], function()
		{
			Route::controller(App\Http\Controllers\Buyer\BuyerCartEditController::class)->group(function()
			{
				Route::post('add/product/to/pending/cart', [
					'as' => 'add.to.pending.cart', 
					//'middleware' => '',
					'uses' => 'AddProductsToPendingCart'
				]);

				//This will been handled fully on frontend:
				Route::patch('edit/pending/cart', [
					'as' => 'edit.pending.carts', 
					'middleware' => 'CartEnsureNotCleared',
					'uses' => 'EditProductsOnPendingCart'//Pending or Cleared
				]);

				//this will be handled fully on frontend:
				Route::delete('delete/pending/carts', [
					'as' => 'delete.pending.carts', 
					'middleware' => 'CartEnsureNotCleared',
					'uses' => 'DeletePendingCart'//Pending or Cleared
				]);

			});


			Route::controller(App\Http\Controllers\Buyer\BuyerCartFetchController::class)->group(function()
			{
				Route::get('fetch/carts/by/payment/category', [
					'as' => 'carts.payment.category', 
					//'middleware' => 'init',
					'uses' => 'FetchCartsByCategory'
				]);
	
				Route::get('fetch/all/buyer/carts/ids', [
					'as' => 'all.buyer.carts.ids', 
					//'middleware' => 'init',
					'uses' => 'FetchCartsIDsOnly'
				]);

				Route::get('fetch/all/cart/products/ids', [
					'as' => 'all.cart.products.ids', 
					//'middleware' => 'init',
					'uses' => 'FetchAllCartProductsIDsOnly'
				]);
			});

		});

		
		Route::group(['prefix' => 'wishlist/', 'middleware' => ['DeleteEmptyWishlists']], function()
		{

			Route::controller(App\Http\Controllers\Buyer\BuyerWishlistEditController::class)->group(function()
			{
				Route::post('add/product/to/wishlist', [
					'as' => 'add.to.wishlist', 
					//'middleware' => '',
					'uses' => 'AddProductsToWishList'
				]);

				//This will been handled fully on frontend:
				Route::patch('edit/existing/wishlist', [
					'as' => 'edit.existing.wishlist', 
					'middleware' => 'DeleteEmptyWishlists',
					'uses' => 'EditProductsOnWishlist'//Pending or Cleared
				]);

				//this will be handled fully on frontend:
				Route::delete('delete/wishlist', [
					'as' => 'delete.wishlist', 
					'middleware' => 'DeleteEmptyWishlists',
					'uses' => 'DeleteWishlist'//Pending or Cleared
				]);

				//this will be handled fully on frontend:
				Route::put('convert/to/cart', [
					'as' => 'convert.to.cart', 
					'middleware' => 'DeleteEmptyWishlists',
					'uses' => 'ConvertWishlistToCart'//Pending or Cleared
				]);

			});


			Route::controller(App\Http\Controllers\Buyer\BuyerWishlistFetchController::class)->group(function()
			{
				Route::get('fetch/all/wishlists/ids', [
					'as' => 'all.wishlists.ids', 
					//'middleware' => 'init',
					'uses' => 'FetchAllWishlistsIdsOnly'
				]);

				Route::get('fetch/each/wishlist/detail/by/id', [
					'as' => 'each.wishlist.details', 
					//'middleware' => 'init',
					'uses' => 'FetchEachWishlistDetailByID'
				]);
	
				Route::get('fetch/all/wishlists/details', [
					'as' => 'all.wishlists.details', 
					//'middleware' => 'init',
					'uses' => 'FetchAllWishlistsDetails'
				]);

				Route::get('fetch/all/wishlist/products/ids', [
					'as' => 'all.wishlist.products.ids', 
					//'middleware' => 'init',
					'uses' => 'FetchAllWishlistProductsIDsOnly'
				]);
			});

		});


		Route::group(['prefix' => 'billing_shipping/', 'middleware' => ['DeleteEmptyBillingAndShipping']], function()
		{
			Route::controller(App\Http\Controllers\Buyer\BuyerBillingAndShippingController::class)->group(function()
			{
				Route::post('upload/billing/details', [
					'as' => 'buyer.billing.details', 
					//'middleware' => 'init',
    				'uses' => 'UploadBillingDetails'
				]);

				Route::post('fetch/billing/details', [
					'as' => 'buyer.fetch.billing.details', 
					//'middleware' => 'init',
    				'uses' => 'FetchBillingDetails'
				]);

				Route::post('upload/shipping/details', [
					'as' => 'buyer.upload.shipping.details', 
					//'middleware' => 'init',
					'uses' => 'UploadShippingDetails'
				]);

				Route::post('fetch/shipping/details', [
					'as' => 'buyer.fetch.shipping.details', 
					//'middleware' => 'init',
					'uses' => 'FetchShippingDetails'
				]);
			});
		});


		Route::group(['prefix' => 'payment/', /*'middleware' => ['DeleteEmptyBillingAndShipping']*/], function()
		{
			Route::controller(App\Http\Controllers\Buyer\BuyerPaymentDetailsController::class)->group(function()
			{
				Route::post('upload/card/details', [
					'as' => 'buyer.upload.card.details', 
					//'middleware' => 'init',
    				'uses' => 'UploadCardDetails'
				]);

				Route::post('fetch/each/card/details', [
					'as' => 'buyer.fetch.each.card.details', 
					//'middleware' => 'init',
    				'uses' => 'FetchEachCardDetails'
				]);

				Route::post('upload/account/details', [
					'as' => 'buyer.upload.account.details', 
					//'middleware' => 'init',
					'uses' => 'UploadAccountDetails'
				]);

				Route::post('fetch/account/details', [
					'as' => 'buyer.fetch.account.details', 
					//'middleware' => 'init',
					'uses' => 'FetchAccountDetails'
				]);

				//This will be for later - an option to use the crypto wallet payment option:
				/*Route::post('upload/crypto/wallet/details', [
					'as' => 'buyer.upload.crypto.wallet.details', 
					//'middleware' => 'init',
					'uses' => 'UploadCryptoWalletDetails'
				]);

				Route::post('fetch/crypto/wallet/details', [
					'as' => 'buyer.fetch.crypto.wallet.details', 
					//'middleware' => 'init',
					'uses' => 'FetchCryptoWalletDetails'
				]);*/
			});

		});

		
		
		Route::group(['prefix' => 'execute/payment/', /*'middleware' => ['']*/], function()
		{
			
			Route::controller(App\Http\Controllers\Buyer\BuyerPaymentExecuteController::class)->group(function()
			{
				/*Buyer's Credit Card or other details of means of payment ....
				note:this should be encrypted...*/
				Route::post('make/payment/with/new/card/details', [
					'as' => 'buyer.pay.with.new.card',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithNewCard'
				]);

				Route::patch('make/payment/with/saved/card/details', [
					'as' => 'buyer.pay.with.saved.card',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithSavedCard'
				]);


				Route::patch('make/payment/with/new/bank/details', [
					'as' => 'buyer.pay.with.new.bank',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithNewBank'
				]);

				Route::patch('make/payment/with/saved/bank/details', [
					'as' => 'buyer.pay.with.saved.bank',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithSavedBank'
				]);


				/*This will be used later:*/
				/*Route::patch('make/payment/with/new/crypto/wallet/details', [
					//'as' => 'buyer.pay.with.new.crypto',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithNewCrypto'
				]);

				Route::patch('make/payment/with/saved/crypto/wallet/details', [
					//'as' => 'buyer.pay.with.saved.crypto',
					//'middleware' => 'init',
					'uses' => 'MakePaymentWithSavedCrypto'
				]);*/
			});

			/*Route::get('view/all/payment/history', [
				'as' => 'buyer.payment.history',
				//'middleware' => 'init',
				'uses' => 'ViewPaymentHistory'
			]);*/

		});
		

		Route::group(['prefix' => 'extras/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Buyer\BuyerExtrasController::class)->group(function()
			{
				//real time location of the goods as updated by the admin:
				//note: it is assumed that the business runs a delivery service...
				//note -- if this is a USA company, their is already a mailing system..

				Route::get('track/all/goods/bought', [
					'as' => 'buyer.track.products.location', 
					//'middleware' => 'init',
					'uses' => 'TrackProductsLocation'
				]);

				Route::put('confirm/products/delivery', [
					'as' => 'buyer.confirm.products.delivery', 
					//'middleware' => 'init',
					'uses' => 'ConfirmProductsDelivery'
				]);

				Route::post('comment/or/rate/service', [
					'as' => 'buyer.give.comment.rating', 
					//'middleware' => 'init',
					'uses' => 'CommentRateService'
				]);

				Route::get('view/others/comments/or/ratings', [
					'as' => 'buyer.view.others.comments.ratings', 
					//'middleware' => 'init',
					'uses' => 'ViewOtherCommentsRates'
				]);

				Route::get('fetch/general/statistics', [
					'as' => 'buyer.general.statistics', 
					//'middleware' => 'init',
					'uses' => 'FetchGeneralStatistics'
				]);

			});
		});

		Route::group(['prefix' => 'realtime/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Buyer\BuyerChatController::class)->group(function()
			{
				Route::post('initiate/buyer/chat/vendor', [
					'as' => 'buyer.chat.vendor', 
					//'middleware' => 'init',
					'uses' => 'BuyerChatVendor'
				]);

				Route::post('initiate/buyer/chat/admin', [
					'as' => 'buyer.chat.admin', 
					//'middleware' => 'init',
					'uses' => 'BuyerChatAdmin'
				]);
			});
		});

		Route::group(['prefix' => 'referral/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Buyer\BuyerReferralController::class)->group(function()
			{
				//first check if referral program has been activated by the admin before proceeding:
				Route::post('generate/unique/referral/link', [
					'as' => 'referral_link', 
					//'middleware' => 'init',
					'uses' => 'GenUniqueReferralLink'
				]);

				//first check if referral program has been activated by the admin before proceeding:
				Route::get('fetch/referral/bonus', [
					//'as' => 'referral_bonus', 
					//'middleware' => 'init',
					'uses' => 'GetReferralBonus'
				]);

				//when a new user clicks the unique referral link generated:
				Route::get('referral/{unique_buyer_id}', [
					//'as' => '', 
					//'middleware' => 'init',
					'uses' => 'FollowReferralLink'
				]);
			});
		});


	});

});



//Vendor Auth:
Route::prefix('v1/vendor')->group(function() 
{

	Route::middleware('VendorCleanNullRecords')->group(function()
	{
		Route::post('register', [
			'as' => 'vendor.register',
			//'middleware' => '',
			'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@Register'
		]);

		Route::get('verifications/{verify}/{answer}', [
			'as' => 'vendor.verifications.verify',
			'middleware' => [/*'auth',*/'signed', 'throttle:6,1'],
			'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@VerifyEmail'
		]);
	
		Route::patch('dashboard/login', [
			'as' => 'vendor.login',
			'middleware' => 'VendorConfirmVerifyState',
			'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@LoginDashboard'
		]);

        Route::patch('dashboard/logout', [
			'as' => 'vendor.logout',
			'middleware' => ['VendorConfirmVerifyState', 'VendorConfirmLoginState', 'DestroyTokenAfterLogout', 'auth:sanctum', 'ability:vendor-crud'],
			'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@Logout'
        ]);

		//This is only for guests who hasn't logged in yet: send the password reset link to the user gmail:
		Route::post('logged-out/send/forgot-password/link', 
			[App\Http\Controllers\Vendor\Auth\VendorAccessController::class, 'SendResetPassLink']
		)->middleware('guest')
		->name('vendor.password.reset.link');

        //for logged out user: Reset Passsword 
        /*This option will be presented to Logged Out users: As this will be outside the dashboard, :*/
        Route::get('clicked/logged-out/reset/{reset}/{answer}', [
            'as' => 'vendor.click.link.reset.password',
            'middleware' => [/*'auth',*/'signed', 'throttle:6,1'],
            'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@ClickResetPasswordLink',
        ]);

		//This option will be presented to the already logged in user: 
		Route::put('auth/reset/password', [
			'as' => 'vendor.reset.password',
			'middleware' => ['VendorEnsureLogoutState','DestroyTokenAfterLogout', 'auth:sanctum', 'ability:vendor-update'],
			'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@ImplementResetPassword'
		]);
	});
});



//Vendor Group: has "api/" by default: 
//use sanctum to authenticate all dashboard endpoint requests:
Route::group(['prefix' => 'v1/vendor/', 'middleware' => ['VendorConfirmLoginState', 'VendorConfirmVerifyState', 'auth:sanctum', 'ability:vendor-crud']], function()
{
	Route::prefix('dashboard/utils/')->group(function() 
	{

		Route::prefix('/profile')->group(function()
		{
			//This option will be presented to the already logged in user: 
			Route::put('logged-in/change-password', [
				'as' => 'vendor.change.password',
				//'middleware' => 'init',
				'uses' => 'App\Http\Controllers\Vendor\Auth\VendorAccessController@ChangePassword'
			]);

			//this might include payment details like credit card info..
			Route::post('edit/profile', [
				//'as' => 'forgot_password',
				//'middleware' => 'init',
				'uses' => 'App\Http\Controllers\Vendor\VendorProfileAssetsController@EditProfileDetails'
			]);

			//optional pictures:
			Route::post('edit/image', [
				//'as' => 'forgot_password',
				//'middleware' => 'init',
				'uses' => '\Buyer\VendorProfileAssetsController@EditProfileImage'
			]);

		});

		Route::prefix('/product')->group(function()
		{
			Route::controller(App\Http\Controllers\Vendor\VendorProductController::class)->group(function()
			{
				Route::post('upload/product/details/texts', [
					'as' => 'upload.product.text.details',
					//'middleware' => 'init',
					'uses' => 'UploadProductTextDetails'
				]);
		
				Route::post('upload/product/details/images', [
					'as' => 'upload.product.image.details',
					//'middleware' => 'init',
					'uses' => 'UploadProductImageDetails'
				]);
		
				Route::post('fetch/all/product/summary', [
					'as' => '', 
					//'middleware' => 'init',
					'uses' => 'FetchAllProductSummary'
				]);
		
				Route::post('fetch/each/product/details', [
					'as' => '', 
					//'middleware' => 'init',
					'uses' => 'FetchEachProductDetails'
				]);
		
				Route::post('delete/each/product/details', [
					'as' => '', 
					//'middleware' => 'init',
					'uses' => 'DeleteEachProductDetails'
				]);
			});
		});


		Route::group(['prefix' => '/cart', 'middleware' => ['DeleteEmptyCarts']], function()
		{

			Route::controller(App\Http\Controllers\Vendor\VendorCartFetchController::class)->group(function()
			{
				Route::post('fetch/related/cart/products/details', [
					'as' => 'vendor.related.cart.products',
					//'middleware' => 'init',
					'uses' => 'FetchRelatedCartProductsDetails'
				]);

				Route::post('fetch/related/buyers/details', [
					'as' => 'vendor.related.buyers',
					//'middleware' => 'init',
					'uses' => 'FetchBuyersCustomerDetails'
				]);
			});

		});


		Route::group(['prefix' => '/wishlist', 'middleware' => ['DeleteEmptyWishlists']], function()
		{
			Route::controller(App\Http\Controllers\Vendor\VendorWishlistFetchController::class)->group(function()
			{
				Route::post('fetch/related/vendor/products/details', [
					'as' => 'vendor.related.wishlist.products',
					//'middleware' => 'init',
					'uses' => 'FetchRelatedWishlistProductsDetails'
				]);

				Route::post('fetch/related/buyers/details', [
					'as' => 'vendor.related.buyers',
					//'middleware' => 'init',
					'uses' => 'FetchWishingBuyersDetails'
				]);
			});
		});


		Route::group(['prefix' => '/business/bank', 'middleware' => ['DeleteEmptyWishlists']], function()
		{
			Route::controller(App\Http\Controllers\Vendor\VendorBusinessAndAccountController::class)->group(function()
			{
				Route::post('update/bank/details', [
					'as' => 'vendor.update.bank.details',
					//'middleware' => 'init',
					'uses' => 'UploadBankAccountDetails'
				]);

				Route::post('fetch/bank/details', [
					'as' => 'vendor.fetch.bank.details',
					//'middleware' => 'init',
					'uses' => 'FetchEachBankAccountDetails'
				]);

				/*Route::post('update/crypto/wallet/details', [
					'as' => 'vendor.update.crypto.wallet.details',
					//'middleware' => 'init',
					'uses' => 'UploadCryptoWalletDetails'
				]);

				Route::post('fetch/crypto/wallet/details', [
					'as' => 'vendor.fetch.crypto.wallet.details',
					//'middleware' => 'init',
					'uses' => 'FetchEachCryptoWalletDetails'
				]);*/

				Route::post('update/business/details', [
					'as' => 'vendor.update.business.details',
					//'middleware' => 'init',
					'uses' => 'UploadBusinessDetails'
				]);

				Route::post('fetch/business/details', [
					'as' => 'vendor.fetch.business.details',
					//'middleware' => 'init',
					'uses' => 'FetchEachBusinessDetails'
				]);

			});
		});


		Route::group(['prefix' => 'execute/payment', /*'middleware' => ['DeleteEmptyBillingAndShipping']*/], function()
		{
			Route::controller(App\Http\Controllers\Buyer\VendorPaymentExecuteController::class)->group(function()
			{
				/*Buyer's Credit Card or other details of means of payment ....
				note:this should be encrypted...*/
				Route::post('make/payment/from/wallet/into/new/bank/account/details', [
					'as' => 'vendor.receive.payment.into.new.bank.account',
					//'middleware' => 'init',
					'uses' => 'MakePaymentIntoNewBankAccount'
				]);

				Route::patch('make/payment/from/wallet/into/saved/bank/account/details', [
					'as' => 'vendor.receive.payment.into.saved.bank.account',
					//'middleware' => 'init',
					'uses' => 'MakePaymentIntoSavedBankAccount'
				]);

				//For later:
				/*Route::post('make/payment/from/wallet/into/new/crypto/wallet/details', [
					'as' => 'vendor.receive.payment.by.new.crypto',
					//'middleware' => 'init',
					'uses' => 'MakePaymentIntoNewCrypto'
				]);

				Route::patch('make/payment/from/wallet/into/saved/crypto/wallet/details', [
					'as' => 'vendor.receive.payment.by.saved.crypto',
					//'middleware' => 'init',
					'uses' => 'MakePaymentIntoSavedCrypto'
				]);*/
			});
		});

		Route::group(['prefix' => 'track/', /*'middleware' => ['']*/], function()
		{	
			//Vendor track product location:
			Route::controller(App\Http\Controllers\Vendor\VendorLocationsAndTracksController::class)->group(function()
			{
				/*Route::put('update/cleared/cart/location', [
					'as' => '',
					//'middleware' => 'init',
					'uses' => 'UpdateLocationDetails'
				]);*/

				//view all goods in motion and their respective locations:
				Route::post('view/all/tracked/carts', [
					//'as' => 'all_tracked_goods', 
    				'uses' => 'ViewTrackedCarts'
				]);

				//update tracking details:
				Route::post('update/tracking/details', [
					//'as' => 'update_tracking', 
					'uses' => '\Vendor\VendorTrackController@UpdateTrackingLocation'
				]);

			});
		});


		Route::group(['prefix' => 'realtime/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Vendor\VendorChatController::class)->group(function()
			{
				Route::post('initiate/vendor/chat/buyer', [
					'as' => 'vendor.chat.buyer', 
					//'middleware' => 'init',
					'uses' => 'VendorChatBuyer'
				]);

				Route::post('initiate/buyer/chat/admin', [
					'as' => 'vendor.chat.admin', 
					//'middleware' => 'init',
					'uses' => 'BuyerChatAdmin'
				]);
			});
		});


		Route::group(['prefix' => 'referral/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Vendor\VendorReferralController::class)->group(function()
			{
				//activate or deactivate referral program 
				Route::post('(de-)activate/referral/program', [
					'as' => 'referral_program', 
    				'uses' => 'ReferralProgram'
				]);

				//set bonus amount per click:
				Route::post('set/referral/bonus', [
					'as' => 'referral_bonus', 
    				'uses' => 'ReferralBonus'
				]);

				Route::get('view/all/referral/links/and/owners', [
					//'as' => 'view_all_referral_links', 
					'uses' => 'ViewAllReferralLinks'
				]);

				Route::put('disable/referral/program', [
					'as' => '',
					//'middleware' => 'init',
					'uses' => 'DisableReferral'
				]);

			});
		});
		

		Route::group(['prefix' => 'overview/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Vendor\VendorOverviewController::class)->group(function()
			{
				/*some of these data will be used for plotting charts on the frontend:
				include - month, total payment made*/

				Route::post('fetch/general/statistics', [
					'as' => 'fetch.general.statistics', 
					//'middleware' => 'init',
					'uses' => 'FetchGeneralStatistics'
				]);
		

				Route::get('sales/chart/data', [
					'as' => 'get.sales.data', 
					//'middleware' => 'init',
					'uses' => 'GetSalesData'
				]);

				Route::get('view/frequently/bought/goods', [
					'as' => 'view.frequently.bought', 
					//'middleware' => 'init',
					'uses' => 'ViewFrequent'
				]);

			});
		});

		Route::group(['prefix' => 'extras/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Vendor\VendorExtrasController::class)->group(function()
			{
				//send messages as reminders to the owner of these goods to complete their purchases:
				//try to use Observers to do this... 
				Route::post('remind/pending/buyer', [
					//'as' => 'remind_cart_owners', 
					'uses' => 'RemindPendingBuyer'
				]);

			});
		});
		
	});

});



//Admin Auth:
Route::group(['prefix' => 'v1/admin/', 'middleware' => ['AdminCleanNullRecords']], function()
{
	Route::patch('dashboard/login', [
		'as' => 'admin.login',
		'middleware' => ['AdminCreateBoss'],
		'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@LoginDashboard'
	]);

	Route::middleware(['auth:sanctum', 'ability:admin-crud'])->group(function()
	{
		//here, admin-boss registers admin hired:
		Route::post('register', [
			'as' => 'admin.register',
			'middleware' => ['auth:sanctum', 'ability:admin-boss'],
			'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@RegisterHiredAdmin'
		]);

        Route::patch('dashboard/logout', [
			'as' => 'admin.logout',
			'middleware' => ['AdminConfirmLoginState', 'DestroyTokenAfterLogout', 'auth:sanctum', 'ability:admin-boss, admin-hired'],
			'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@Logout'
        ]);

		//This is only for guests who hasn't logged in yet: send the password reset link to the user gmail:
		Route::post('logged-out/send/forgot-password/link', 
			[App\Http\Controllers\Admin\Auth\AdminAccessController::class, 'SendResetPassLink']
		)->middleware('guest')
		->name('admin.password.reset.link');

        //for logged out user: Reset Passsword 
        /*This option will be presented to Logged Out users: As this will be outside the dashboard, :*/
        Route::get('clicked/logged-out/reset/{reset}/{answer}', [
            'as' => 'admin.click.link.reset.password',
            'middleware' => [/*'auth',*/'signed', 'throttle:6,1'],
            'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@ClickResetPasswordLink',
        ]);

		//This option will be presented to the already logged in user: 
		Route::put('auth/reset/password', [
			'as' => 'admin.reset.password',
			'middleware' => ['AdminEnsureLogoutState','DestroyTokenAfterLogout', 'auth:sanctum', 'ability:admin-update'],
			'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@ImplementResetPassword'
		]);
	});
});

        
//use sanctum to authenticate all dashboard endpoint requests:
Route::group(['prefix' => 'v1/admin/', 'middleware' => ['AdminConfirmLoginState', /*'auth:sanctum', 'ability:admin-crud'*/ ]], function()
{ 
	Route::middleware(['auth:sanctum', 'ability:admin-boss'])->group(function()
	{
		//This option will be presented to the already logged in admin: 
		Route::post('logged-in/change-password', [
			//'as' => 'change_password',
			//'middleware' => 'init',
			'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@ChangePassword'
		]);

		Route::post('set/hired/admin/passwords', [
			//'as' => 'change_password',
			//'middleware' => 'init',
			'uses' => 'App\Http\Controllers\Admin\Auth\AdminAccessController@SetHiredPasswords'
		]);

		//this might include payment details like credit card info..
		Route::post('edit/profile', [
    		//'as' => 'forgot_password',
    		//'middleware' => 'init',
    		'uses' => '\Admin\AdminProfileAssetsController@EditProfile'
		]);

		//optional pictures:
		Route::post('edit/image', [
    		//'as' => 'forgot_password',
    		//'middleware' => 'init',
    		'uses' => '\Admin\AdminProfileAssetsController@EditImage'
		]);

		Route::prefix('dashboard/utils/')->group(function() 
		{
			//to on-board busy vendors: admin can create for them
			Route::post('create/vendor', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@CreateVendor'
			]);

			Route::post('view/all/warned/vendors', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@ViewAllWarnedVendor'
			]);

			//Admin can delete vendor after warning(unauthorised content upload or in case of in-activity):
			Route::delete('delete/each/vendor/details', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@DeleteVendor'
			]);

			Route::put('suspend/each/vendor', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@SuspendVendor'
			]);

			Route::put('activate/each/suspended/vendor', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@ActivateVendor'
			]);
		});

		Route::group(['prefix' => 'referral/', /*'middleware' => ['']*/], function()
		{	
			Route::controller(App\Http\Controllers\Admin\AdminReferralController::class)->group(function()
			{
				Route::post('update/referral/details', [
					'as' => 'admin.update.referral.details', 
					//'middleware' => 'init',
					'uses' => 'UpdateReferralDetails'
				]);

				Route::get('fetch/referral/details', [
					'as' => 'admin.fetch.referral.details', 
					//'middleware' => 'init',
					'uses' => 'FetchReferralDetails'
				]);

				//when a new user clicks the unique referral link generated:
				Route::get('disable/referral/program', [
					//'as' => '', 
					//'middleware' => 'init',
					'uses' => 'DisableReferral'
				]);
			});
		});

	});

	Route::middleware(['auth:sanctum', 'abilities:admin-boss, admin-hired'])->group(function()
	{
		Route::prefix('dashboard/utils/')->group(function() 
		{
			//note: dashboard utils have to be re-written because the admin function here is different...
			Route::get('view/all/vendors/summary', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => 'AdminGeneralController@ViewAllVendorsSummary'//paginate
			]);

			Route::patch('view/each/vendor/details', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => 'AdminGeneralController@ViewEachVendorDetails'//paginate
			]);

			Route::put('warn/each/vendor', [
				'as' => '',
				//'middleware' => 'init',
				'uses' => '\Admin\AdminGeneralController@WarnVendor'
			]);


			//some of these data will be used for plotting charts on the frontend:
			//include - month, total payment made
			Route::post('fetch/general/statistics', [
				'as' => 'general_statistics', 
				//'middleware' => 'init',
    			'uses' => '\Admin\AdminOverviewController@FetchGeneralStatistics'
			]);
		
			Route::get('sales/chart/data', [
				'as' => 'sales_data', 
				//'middleware' => 'init',
    			'uses' => '\Admin\AdminOverviewController@SpecificVendorSalesData'
			]);

			Route::get('view/all/vendors/by/highest/sales', [
				'as' => 'frequently_bought', 
				//'middleware' => 'init',
    			'uses' => '\Admin\AdminOverviewController@ViewVendorsByHighestSales'
			]);	

			Route::post('view/all/products', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => '\Admin\AdminProductController@ViewAllProducts'
			]);

			Route::post('hint/vendor/on/highest/sales/products', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminCartController@HintVendor'
			]);

			Route::post('sell/sales/data', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminCartController@HintVendor'
			]);

			Route::get('view/transactions/complaints', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminComplaintController@ViewAllBuyerComplaints'
			]);

			Route::patch('view/each/transaction/complaints/details', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminComplaintController@ViewEachBuyerComplaintsDetails'
			]);

			Route::patch('chat/buyer/on/buyer/complaint', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminComplaintController@ChatBuyerOnBuyerComplaint'
			]);

			Route::patch('chat/vendor/on/buyer/complaint', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminComplaintController@ChatVendorOnBuyerComplaint'
			]);

			Route::patch('general/chat/buyer', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminSupportController@GeneralChatBuyer'
			]);

			Route::patch('general/chat/vendor', [
				//'as' => 'pending_or_cleared_cart_goods', 
    			'uses' => 'AdminSupportController@GeneralChatVendor'
			]);
		});

	});

});
