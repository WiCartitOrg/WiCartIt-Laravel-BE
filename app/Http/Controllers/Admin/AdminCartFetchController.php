

public function FetchAllCartProductsIDsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllCartProductsIDsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not a logged in user!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchAllCartProductsIDsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("{ $request->payment_status === 'cleared' ? 'Cleared' : 'Pending'} Carts IDs not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'allCartIDs' => $detailsFound
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

   public function FetchCartsIDsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchCartsIDsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $detailsFound = $this->VendorFetchCartsIDsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Pending Cart Details not found! Ensure that this is not a Cleared Cart ID.");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'cart_details' => $detailsFound
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

   public function FetchEachCartDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchEachCartDetailsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Cart ID provided!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchEachCartDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Pending Cart Details not found! Ensure that this is not a Cleared Cart ID.");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'cart_details' => $detailsFound
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];

      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

  