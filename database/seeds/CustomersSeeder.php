<?php

use App\Customer;
use App\IclassTbl;
use App\SageCustomer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $internal = IclassTbl::where('Description','like','%staff%')->get()->map(function($i){
            return $i->IdCliClass;
        });

        $external = IclassTbl::where('Description','not like','%staff%')->get()->map(function($e){
            return $e->IdCliClass;
        });

        $internal_customers = SageCustomer::select("DCLink","Account","Name","Contact_Person","EMail",
            "Physical1","Physical2","Telephone","Telephone2","Fax1","Fax2","Tax_Number","Post1","Post2","iARPriceListNameID")->whereIn('iClassID',$internal)->get()->map(function($in_t){
            return [
                'DCLink'=>$in_t->DCLink,
                'Account'=> $in_t->Account,
                'type'=> 'internal',
                'Name'=> $in_t->Name,
                'Contact_Person'=> $in_t->Contact_Person,
                'EMail'=> $in_t->EMail,
                'Physical1'=> $in_t->Physical1,
                'Physical2'=> $in_t->Physical2,
                'Telephone'=> $in_t->Telephone,
                'Telephone2'=> $in_t->Telephone2,
                'Fax1'=> $in_t->Fax1,
                'Fax2'=> $in_t->Fax2,
                'Tax_Number'=> $in_t->Tax_Number,
                'Post1'=> $in_t->Post1,
                'Post2'=> $in_t->Post2,
                'iARPriceListNameID'=> $in_t->iARPriceListNameID
            ];
        });

        $external_customers = SageCustomer::select("DCLink","Account","Name","Contact_Person","EMail",
            "Physical1","Physical2","Telephone","Telephone2","Fax1","Fax2","Tax_Number","Post1","Post2","iARPriceListNameID")->whereIn('iClassID',$external)->get()->map(function($ex_t){
            return [
                'DCLink'=>$ex_t->DCLink,
                'Account'=> $ex_t->Account,
                'type'=> 'external',
                'Name'=> $ex_t->Name,
                'Contact_Person'=> $ex_t->Contact_Person,
                'EMail'=> $ex_t->EMail,
                'Physical1'=> $ex_t->Physical1,
                'Physical2'=> $ex_t->Physical2,
                'Telephone'=> $ex_t->Telephone,
                'Telephone2'=> $ex_t->Telephone2,
                'Fax1'=> $ex_t->Fax1,
                'Fax2'=> $ex_t->Fax2,
                'Tax_Number'=> $ex_t->Tax_Number,
                'Post1'=> $ex_t->Post1,
                'Post2'=> $ex_t->Post2,
                'iARPriceListNameID'=> $ex_t->iARPriceListNameID
            ];
        });

        $customers = array_merge($internal_customers->toArray(),$external_customers->toArray());

        $faker = Faker::create();
        foreach ($customers as $customer){
            $inserted[] = Customer::create([
                'dc_link' => $customer['DCLink'],
                'account' => $customer['Account'] ? $customer['Account'] : $faker->word,
                'name' => $customer['Name'] ? $customer['Name'] : $faker->name,
                'contact_person' => $customer['Contact_Person'] ?  $customer['Contact_Person'] : $faker->name,
                'email' => $customer['EMail'] ? $customer['EMail'] : $faker->email,
                'address' => $customer['Physical1'] ? $customer['Physical1'] : $customer['Physical2'],
                'box_no' => $customer['Post1'] ? $customer['Post1'] : $customer['Post2'],
                'fax' => $customer['Fax1'] ? $customer['Fax1'] : $customer['Fax2'],
                'tax_no' => $customer['Tax_Number'],
                'telephone' => $customer['Telephone'] ? $customer['Telephone'] : $customer['Telephone2'],
                'customer_type_id' => $customer['type']=='internal' ? 1 : 2 ,
                'price_list_name_id' => $customer['iARPriceListNameID']
            ]);

        }

    }
}
