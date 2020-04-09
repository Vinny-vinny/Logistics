<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersSeeder::class);
         $this->call(AgentSeeder::class);
         $this->call(TrackBySeeder::class);
         $this->call(ServiceTypeSeeder::class);
         $this->call(FuelBalanceSeeder::class);
         $this->call(ExpensesSeeder::class);
         $this->call(FuelTypeSeeder::class);
         $this->call(FuelSupplierSeeder::class);
         $this->call(ExpiryDateSeeder::class);
         $this->call(CategorySeeder::class);
         $this->call(TransactionSeeder::class);
         $this->call(JobcardCategorySeeder::class);
         $this->call(MechanicSeeder::class);
         $this->call(JobTypeSeeder::class);
         $this->call(CustomerTypesSeeder::class);
         $this->call(ChecklistToolSeeder::class);
         $this->call(StkCategorySeeder::class);
         $this->call(AssetCategorySeeder::class);
         $this->call(AssetsSeeder::class);
         $this->call(CustomersSeeder::class);
         $this->call(PartsSeeder::class);
         $this->call(PriceListSeeder::class);
         $this->call(UomSeeder::class);
         $this->call(AccountsSeeder::class);
         $this->call(ChargesSeeder::class);




    }
}
