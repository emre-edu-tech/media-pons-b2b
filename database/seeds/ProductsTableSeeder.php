<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ananas
        Product::create([
            'name'  => 'Ananas',
            'slug'  => 'ananas',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1099,
            'sale_price'    => 899,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(1);

        $product = Product::find(1);
        $product->categories()->attach(8);
        $product->categories()->attach(12);
        
        // Doppel Apfel
        Product::create([
            'name'  => 'Doppel Apfel',
            'slug'  => 'doppel-apfel',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1299,
            'sale_price'    => 1099,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(1);

        $product = Product::find(2);
        $product->categories()->attach(8);
        $product->categories()->attach(12);

        //Pfirsich, Maracuja & Wassermelone
        Product::create([
            'name'  => 'Pfirsich, Maracuja & Wassermelone',
            'slug'  => 'pfirsich-maracuja-wassermelone',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1399,
            'sale_price'    => 1199,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(1);

        $product = Product::find(3);
        $product->categories()->attach(8);
        $product->categories()->attach(12);

        //Traube & Minze
        Product::create([
            'name'  => 'Traube & Minze',
            'slug'  => 'traube-minze',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1599,
            'sale_price'    => 1299,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(1);

        $product = Product::find(4);
        $product->categories()->attach(8);
        $product->categories()->attach(12);

        //Traube & Minze
        Product::create([
            'name'  => 'Zitrone & Minze',
            'slug'  => 'zitrone-minze',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 2099,
            'sale_price'    => 1899,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(1);

        $product = Product::find(5);
        $product->categories()->attach(8);
        $product->categories()->attach(12);

        Product::create([
            'name' => 'Melitta Filtertüten 1x4 naturbraun Aroma 80 Stück',
            'slug'  => 'melitta-filtertueten-1x4-naturbraun-aroma-80-stueck',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1099,
            'sale_price' => 899,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(7);

        $product = Product::find(6);
        $product->categories()->attach(11);

        Product::create([
            'name' => 'Cherry Romatomaten 250g',
            'slug'  => 'cherry-romatomaten-250g',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 899,
            'sale_price'    => 799,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(2);

        $product = Product::find(7);
        $product->categories()->attach(10);

        Product::create([
            'name' => 'Tafeltrauben hell kernlos 500g',
            'slug' => 'tafeltrauben-hell-kernlos-500g',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1099,
            'sale_price' => 899,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ])->categories()->attach(2);

        $product = Product::find(8);
        $product->categories()->attach(9);
    }
}
