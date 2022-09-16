<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\Traits\ModelCRUDs\General\ProductCRUD;
use App\Services\Traits\ModelCRUDs\General\WishlistCRUD;
use App\Services\Traits\ModelCRUDs\Vendor\VendorCRUD;
use App\Services\Traits\ModelAbstractions\Vendor\VendorProductAbstraction;
use App\Services\Traits\ModelCRUDs\General\WishlistMetadataCRUD;

use App\Services\Traits\Utilities\ComputeUniqueIDService;

trait VendorWishlistFetchAbstraction
{
	//inherits all their methods:
	use ProductCRUD;
	use WishlistCRUD;
	use WishlistMetadataCRUD;
	use VendorCRUD;
	use VendorProductAbstraction;

	use ComputeUniqueIDService;

	private function PersistWishlistMetadata(array $metadata_info): bool
	{
		//build query Keys/Values:
		$toPersistKeysValues = [
			'unique_buyer_id' => $metadata_info['unique_buyer_id'],
			'unique_vendor_id' => $metadata_info['unique_vendor_id'],
			'unique_wishlist_id' => $metadata_info['unique_wishlist_id'],
			'unique_product_id' => $metadata_info['unique_product_id'],
			'total_product_price' => $metadata_info['total_product_price'],
		];
		$detail_is_saved = $this?->WishlistMetadataCreateAllService($toPersistKeysValues);
		return $detail_is_saved;
	}

	protected function VendorFetchRelatedWishlistProductsDetailsService(Request $request) : array
	{	
		//vendor only need to fetch: their products_ids that are on wishlist, pricing
		//init:
		$related_wishlists_products_summary = array();

		//start with the vendor object:
		$unique_vendor_id = $request?->unique_vendor_id; 
        $queryKeysValues1 = [
            'unique_vendor_id' => $unique_vendor_id,
        ];
		
        //get related products:
        $relatedVendorProductObject = $this?->VendorReadSpecificService($queryKeysValues1)?->products;
        //get only products ids:
        $relatedVendorProductsIds = $relatedVendorProductObject?->pluck('unique_product_id');
        
        //get wishlist information:
		$payment_status = $request?->wishlist_payment_status;
		$queryKeysValues2 = [
			//'unique_vendor_id' => $vendor_id,
			'wishlist_payment_status' => $payment_status,
		];

		$relatedWishlistProductsCollections = $this?->WishlistReadAllLazySpecificService($queryKeysValues2);
		//convert lazy collections to array:
		$relatedWishlistProductsArray = $relatedWishlistProductsCollections?->toArray();

		$relatedWishlistProductsIds = $relatedWishlistProductsCollections?->pluck('wishlist_attached_products_ids_quantities');
		//convert lazy collections to array: this should return ['product_id' => quantities]
		$relatedWishlistProductsIdsArray = $relatedWishlistProductsIds?->toArray();

		//init product count:
		$related_wishlists_products_summary['products_count'] = 0;

		//first loop through each object in collections:
		foreach($relatedWishlistProductsArray as $eachrelatedWishlistProductObject)
		{
			//Now compare the two Id collections:
			foreach($relatedVendorProductsIds as $eachProductId)
			{
				//if a vendor product id is found in wishlist product ids:
				if(in_array($eachProductId, array_keys($relatedWishlistProductsIdsArray)))
				{
					//step product count by 1:
					$related_wishlists_products_summary['products_count']+=1;

					//then, get details summary:
					$queryKeysValues3 = ['unique_product_id' => $eachProductId];
					$productObject = $this?->ProductReadSpecificService($queryKeysValues3);

					$each_product_quantities = (int) $relatedWishlistProductsIdsArray[$eachProductId];
					$each_product_price = $productObject?->product_price;
					$each_product_shipping_cost = $productObject?->product_shipping_cost;
					
					$total_product_price = $each_product_price * $each_product_quantities;
					$total_product_shipping_cost = $each_product_shipping_cost * $each_product_quantities;
					$total_product_cost = $total_product_price + $total_product_shipping_cost;

					//get product image:
					$product_image = base64_encode(Storage::get($productObject?->main_image_1));

					//get related buyer_id:
					$unique_buyer_id = $eachrelatedWishlistProductObject?->unique_buyer_id;
					
					//create a new array
					$each_related_wishlist_product_details = [
						'product_id' => $eachProductId,
						'product_image' => $product_image,
						'product_price' => $total_product_price,
						'product_shipping_cost' => $total_product_shipping_cost,
						'product_total_cost' => $total_product_cost,
						'product_buyer_id' => $unique_buyer_id,
					];
					
					//add this to the global array: 
					//$related_wishlists_products_summary[] = $each_related_wishlist_product_details;
					array_push($related_wishlists_products_summary, $each_related_wishlist_product_details);

					//To show that $unique_buyer_id has bought products worth $total_product_price from $unique_vendor_id:
					//This will make future promos, discount and referrals possible...
					if($$eachrelatedWishlistProductObject?->wishlist_payment_status === 'cleared')
					{
						$this?->PersistWishlistMetadata([
							'unique_buyer_id'=>$unique_buyer_id, 
							'unique_vendor_id'=>$unique_vendor_id, 
							'unique_wishlist_id' => $eachrelatedWishlistProductObject?->unique_wishlist_id,
							'unique_product_id' => $eachProductId,
							'product_cost'=> $total_product_price,
						]);
					}
				}
			}
		}

		return $related_wishlists_products_summary;
	}


	//get all Buyers that have bought products from this vendor so far:
	protected function VendorFetchWishingBuyersDetailsService(Request $request)
	{
		//init:
		$thisVendorCustomerBuyersDetailsSummary = array();

		$eachVendorCustomerBuyerDetail = array();
		$eachVendorCustomerBuyerDetail['products_ids_and_quantities'] = [];

		$unique_vendor_id = $request?->unique_vendor_id;
		//set query:
		$queryKeysValues1 = [
			'unique_vendor_id' => $unique_vendor_id,
		];

		$metaDataCollection1 = $this?->WishlistMetadataReadAllLazySpecificService($queryKeysValues1);
		//get all ids:
		$unique_buyers_ids = $metaDataCollection1?->pluck('unique_buyer_id')?->toArray();

		
		//loop through each id to query and get the amount bought so far:
		foreach($unique_buyers_ids as $unique_buyer_id)
		{
			$queryKeysValues2 = [
				'unique_vendor_id' => $unique_vendor_id,
				'unique_buyer_id' => $unique_buyer_id,
			];
			$metaDataCollection2 = $this?->WishlistMetadataReadAllLazySpecificService($queryKeysValues2);
			//total sum:
			$total_product_amount_bought = $metaDataCollection2?->pluck('product_cost')?->sum();

			//products and product count:
			$all_related_products_ids = $metaDataCollection2?->pluck('unique_product_id')?->toArray();
			//sort by only unique elements:
			$unique_related_products_ids = array_unique($all_related_products_ids);
			//returns the number of occurrence:
			$count_init = array_count_values($all_related_products_ids);

			//begin to add the buyer and what they have bought so far from this vendor:
			$eachVendorCustomerBuyerDetail[$unique_buyer_id] = $total_product_amount_bought;

			//loop through and compare:
			foreach($unique_related_products_ids as $each_unique_related_product_id)
			{
				//add purchased products ids and their count: 
				$product_count = $count_init[$each_unique_related_product_id];
				$eachVendorCustomerBuyerDetail['products_ids_and_quantities']+=[
					$each_unique_related_product_id => $product_count,
				];
			}

			array_push($thisVendorCustomerBuyersDetailsSummary, $eachVendorCustomerBuyerDetail);
		}

		return $thisVendorCustomerBuyersDetailsSummary;
	}	
}