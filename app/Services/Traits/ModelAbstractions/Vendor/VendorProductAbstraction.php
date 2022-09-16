<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

use App\Services\Traits\ModelCRUDs\Vendor\VendorCRUD;
use App\Services\Traits\ModelCRUDs\General\PaymentInfoCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\General\ProductCRUD;

use App\Services\Traits\Utilities\ComputeUniqueIDService;
use App\Models\General\Product;


trait VendorProductAbstraction 
{
	use VendorCRUD;
	use PaymentInfoCRUD;
	use CartCRUD;
	use ProductCRUD;
	use ComputeUniqueIDService;

	protected function VendorSaveProductTextDetailsService(Request $request) : array
	{
		//get all requests:
		$product_array_to_persist = $request?->except('unique_vendor_id');

		
		$product_array_to_persist['unique_product_id'] = $this?->genUniqueAlphaNumID();

		//add products using related vendors:
		$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		$vendorObject = $this?->VendorReadSpecificService($queryKeysValues);
		$productWasCreatedByVendor = $this?->ProductCreateAllThroughVendor($vendorObject, $product_array_to_persist);

		if($productWasCreatedByVendor)
		{
			return [
				'is_saved' => false,
			];
		}

		return [
			'is_saved' => true,
			'unique_product_id' => $product_array_to_persist['unique_product_id']
		];
	}

	protected function VendorSaveProductImageDetailsService(Request $request): bool
	{
		/*Note: files links are to be stored in the database for now...
		while the real files are stored on this hosting service for now: 
		This will change in future as we employ paid remote file storage systems*/

		$unique_product_id = $request?->unique_product_id;

		if($unique_product_id !== "")
		{
			//query and new Keys and values:
			$queryKeysValues = ['unique_product_id' => $unique_product_id];
			//this is the image file uploads:
			//$newKeysValues = $request?->except(['unique_vendor_id', 'unique_product_id']);

			//Images in laravel will be stored in a storage folder while their pointer path will be stored in a database:

			//first store these images in a storage location on server:
			//probably stored in: ../storage/app/public/uploads first
			$main_image_1_rep = $request?->file('main_image_1')?->store('uploads');
			$main_image_2_rep = $request?->file('main_image_2')?->store('uploads');
			$logo_1_rep = $request?->file('logo_1')?->store('uploads');
			$logo_2_rep = $request?->file('logo_2')?->store('uploads');

			//Now store their respective links in the database:
			$newKeysValues = [
				'main_image_1' => $main_image_1_rep,
				'main_image_2' => $main_image_2_rep,
				'logo_1' => $logo_1_rep,
				'logo_2' => $logo_2_rep
			];

			$product_image_has_updated = $this?->ProductUpdateSpecificService($queryKeysValues, $newKeysValues);

			if(!$product_image_has_updated)
			{
				throw new \Exception("Could not upload image Successfully!");
			}
		}
		return true;
	}


	protected function VendorFetchAllProductSummaryService(Request $request): LazyCollection
	{
		//first get vendor object:
		$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		$vendorObject = $this?->VendorReadSpecificService($queryKeysValues);

		//use this vendor to get all associated products: 
		$allProductDetails = $this?->ProductReadAllThroughVendorService($vendorObject);
		
		/*the above returns a lazy collection of all products: 
		loop through to get only the ids:*/
		$allProductSummary = $allProductDetails?->pluck($value='main_image_1', $key='unique_product_id');
		return $allProductSummary;
	}


	protected function VendorFetchEachProductDetailsService(Request $request): Product
	{
		//Now, query for specific product:
		$queryKeysValues = [
			'unique_product_id' => $request?->unique_product_id,
		];

		$specific_product_detail = $this?->ProductReadSpecificService($queryKeysValues);
		if(!$specific_product_detail)
		{
			throw new \Exception("Product Details not found! Ensure you have created this product as appropriate.");
		}

		//get the buyer id:
		//now use this to get the buyer model:(this is in a bead to get buyer email and phone number)

		//begin to prepare the return array:
		
		//for images, fetch images whose db link is in the model:
		$specific_product_detail?->main_image_1 = base64_encode(Storage::get($specific_product_detail?->main_image_1));
		$specific_product_detail?->main_image_2 = base64_encode(Storage::get($specific_product_detail?->main_image_2));
		$specific_product_detail?->logo_1 = base64_encode(Storage::get($specific_product_detail?->logo_1));
		$specific_product_detail?->logo_2 = base64_encode(Storage::get($specific_product_detail?->logo_2));

		//get the date created because the feature is hidden:
		$specific_product_detail['product_created_at'] = $specific_product_detail?->created_at;

		return $specific_product_detail;
	}


	protected function VendorDeleteEachProductDetailsService(Request $request): bool
	{
		$deleteKeysValues = [
			'unique_product_id' => $request?->unique_product_id,
		];

		$specific_product_detail = $this?->ProductReadSpecificService($deleteKeysValues);
		if(!$specific_product_detail)
		{
			throw new \Exception("Product Details not found! Ensure you have created this product as appropriate.");
		}

		//get the buyer id:
		//begin to delete the images on server whose links are stored in our Model:
		
		//for images, fetch images whose db link is in the model:
		Storage::delete($specific_product_detail?->main_image_1);
		Storage::delete($specific_product_detail?->main_image_2);
		Storage::delete($specific_product_detail?->logo_1);
		Storage::delete($specific_product_detail?->logo_2);

		//having deleted the images, delete the whole entry inside the database:
		$product_has_deleted = $this?->ProductDeleteSpecificService($deleteKeysValues);

		return $product_has_deleted;
	}

}

?>