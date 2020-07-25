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
        ]);
        
        // Doppel Apfel
        Product::create([
            'name'  => 'Doppel Apfel',
            'slug'  => 'doppel-apfel',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1299,
            'sale_price'    => 1099,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ]);

        //Pfirsich, Maracuja & Wassermelone
        Product::create([
            'name'  => 'Pfirsich, Maracuja & Wassermelone',
            'slug'  => 'pfirsich-maracuja-wassermelone',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1399,
            'sale_price'    => 1199,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ]);

        //Traube & Minze
        Product::create([
            'name'  => 'Traube & Minze',
            'slug'  => 'traube-minze',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 1599,
            'sale_price'    => 1299,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ]);

        //Traube & Minze
        Product::create([
            'name'  => 'Zitrone & Minze',
            'slug'  => 'zitrone-minze',
            'short_description' => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
            'regular_price' => 2099,
            'sale_price'    => 1899,
            'long_description'  => 'In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.Suscipit purus vitae, hendrerit tortor. Sed lobortis tempor venenatis. Mauris eu rhoncus nulla, vitae laoreet est. Phasellus vehicula lectus sed odio vehicula, at placerat tortor malesuada. Vestibulum porta pellentesque bibendum. In consequat, massa sit amet euismod consequat, lectus augue vehicula odio, nec laoreet purus orci sit amet neque.',
        ]);
    }
}
