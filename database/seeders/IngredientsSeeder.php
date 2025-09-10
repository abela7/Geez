<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing suppliers
        $suppliers = \App\Models\Supplier::all();
        if ($suppliers->isEmpty()) {
            $suppliers = collect([
                \App\Models\Supplier::create([
                    'name' => 'Fresh Foods Ltd',
                    'code' => 'FF001',
                    'contact_person' => 'John Smith',
                    'email' => 'john@freshfoods.com',
                    'phone' => '+1-555-0101',
                    'address' => '123 Market Street',
                    'status' => 'active',
                ]),
            ]);
        }

        $ingredients = [
            [
                'name' => 'Chicken Breast (Boneless)',
                'code' => 'PROT001',
                'description' => 'Fresh boneless chicken breast, premium quality',
                'category' => 'proteins',
                'unit' => 'kg',
                'cost_per_unit' => 8.50,
                'minimum_order_qty' => 5.0,
                'supplier_id' => $suppliers->first()->id,
                'lead_time_days' => 2,
                'storage_requirements' => 'refrigerated',
                'shelf_life_days' => 5,
                'nutritional_info' => ['calories' => 165, 'protein' => 31.0, 'fat' => 3.6],
                'allergen_info' => [],
                'status' => 'active',
                'last_updated' => now(),
            ],
            [
                'name' => 'Roma Tomatoes',
                'code' => 'VEG001',
                'description' => 'Fresh Roma tomatoes, ideal for sauces',
                'category' => 'vegetables',
                'unit' => 'kg',
                'cost_per_unit' => 3.25,
                'minimum_order_qty' => 10.0,
                'supplier_id' => $suppliers->first()->id,
                'lead_time_days' => 1,
                'storage_requirements' => 'ambient',
                'shelf_life_days' => 7,
                'nutritional_info' => ['calories' => 18, 'protein' => 0.9, 'carbs' => 3.9],
                'allergen_info' => [],
                'status' => 'active',
                'last_updated' => now(),
            ],
            [
                'name' => 'Mozzarella Cheese (Shredded)',
                'code' => 'DAIRY002',
                'description' => 'Pre-shredded mozzarella cheese',
                'category' => 'dairy',
                'unit' => 'kg',
                'cost_per_unit' => 8.95,
                'minimum_order_qty' => 2.0,
                'supplier_id' => $suppliers->first()->id,
                'lead_time_days' => 3,
                'storage_requirements' => 'refrigerated',
                'shelf_life_days' => 21,
                'nutritional_info' => ['calories' => 280, 'protein' => 28.0, 'fat' => 17.0],
                'allergen_info' => ['dairy'],
                'status' => 'active',
                'last_updated' => now(),
            ],
            [
                'name' => 'Black Pepper (Ground)',
                'code' => 'SPICE001',
                'description' => 'Freshly ground black pepper',
                'category' => 'spices',
                'unit' => 'kg',
                'cost_per_unit' => 18.75,
                'minimum_order_qty' => 1.0,
                'supplier_id' => $suppliers->first()->id,
                'lead_time_days' => 5,
                'storage_requirements' => 'dry',
                'shelf_life_days' => 1095,
                'nutritional_info' => ['calories' => 251, 'protein' => 10.4, 'fiber' => 25.3],
                'allergen_info' => [],
                'status' => 'active',
                'last_updated' => now(),
            ],
            [
                'name' => 'Extra Virgin Olive Oil',
                'code' => 'OIL001',
                'description' => 'Cold-pressed extra virgin olive oil',
                'category' => 'oils',
                'unit' => 'l',
                'cost_per_unit' => 15.25,
                'minimum_order_qty' => 5.0,
                'supplier_id' => $suppliers->first()->id,
                'lead_time_days' => 7,
                'storage_requirements' => 'cool_dry',
                'shelf_life_days' => 730,
                'nutritional_info' => ['calories' => 884, 'fat' => 100.0],
                'allergen_info' => [],
                'status' => 'active',
                'last_updated' => now(),
            ],
        ];

        foreach ($ingredients as $ingredientData) {
            \App\Models\Ingredient::create($ingredientData);
        }

        $this->command->info('Created ' . count($ingredients) . ' sample ingredients.');
    }
}
