<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:clear-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all products, variants, product images, and cart items to prepare for real data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure you want to delete ALL products and product-related data? This cannot be undone.')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Clearing product data...');

        $dbConnection = config('database.default');
        
        if ($dbConnection === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('cart_items')->truncate();
        DB::table('order_items')->truncate();
        DB::table('age_group_product')->truncate();
        DB::table('collection_product')->truncate();
        DB::table('reviews')->truncate();

        // Delete physical images
        $images = DB::table('product_images')->get();
        foreach ($images as $img) {
            if (isset($img->image) && $img->image && !str_starts_with($img->image, 'http')) {
                Storage::disk('public')->delete($img->image);
            }
        }
        DB::table('product_images')->truncate();

        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();

        if ($dbConnection === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->info('All products and related data successfully cleared!');
    }
}
