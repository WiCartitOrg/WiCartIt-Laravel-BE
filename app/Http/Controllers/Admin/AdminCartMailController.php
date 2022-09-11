public function RemindPendingCartBuyers(Request $request): JsonResponse
   {
      $status = array();
      
      try
      {
         //get rules from validator class:
         $reqRules = $this->remindPendingBuyerRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not a logged in user!");
         }
         
         //this should return in chunks or paginate:
         $mailSent = $this->BuyerRemindPendingService($request);
         if(!$mailSent)
         {
            throw new \Exception("Mail not Sent!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'RemindSuccess!',
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'RemindError!',
            'short_description' => $ex->getMessage()
         ];

      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

   public function RemindWishlistBuyers(Request $request): JsonResponse
   {
      $status = array();
      
      try
      {
         //get rules from validator class:
         $reqRules = $this->remindPendingBuyerRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not a logged in user!");
         }
         
         //this should return in chunks or paginate:
         $mailSent = $this->BuyerRemindPendingService($request);
         if(!$mailSent)
         {
            throw new \Exception("Mail not Sent!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'RemindSuccess!',
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'RemindError!',
            'short_description' => $ex->getMessage()
         ];

      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }