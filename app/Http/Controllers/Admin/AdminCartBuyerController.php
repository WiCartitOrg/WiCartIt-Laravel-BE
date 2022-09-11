//first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function FetchAllCartBuyerIDs(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllCartBuyerIDsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchAllCartBuyerIDsService();
         if( empty($detailsFound) )
         {
            throw new \Exception("No Buyer ID found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'buyers' => $detailsFound
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

//first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function FetchEachCartBuyerDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchEachBuyerDetailsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Wrong Buyer ID!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchEachBuyerDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Buyer Details not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'buyer_details' => $detailsFound
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