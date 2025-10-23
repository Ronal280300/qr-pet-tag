<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PLANES DE PAGO ÚNICO
        Plan::create([
            'name' => 'Pago Único - 1 Mascota',
            'type' => 'one_time',
            'duration_months' => null,
            'pets_included' => 1,
            'price' => 20000,
            'additional_pet_price' => 10000,
            'is_active' => true,
            'description' => 'Plan de pago único para 1 mascota. Mascotas adicionales: ₡10,000 c/u',
            'sort_order' => 1,
        ]);

        Plan::create([
            'name' => 'Pago Único - 2 Mascotas',
            'type' => 'one_time',
            'duration_months' => null,
            'pets_included' => 2,
            'price' => 40000,
            'additional_pet_price' => 10000,
            'is_active' => true,
            'description' => 'Plan de pago único para 2 mascotas. Mascotas adicionales: ₡10,000 c/u',
            'sort_order' => 2,
        ]);

        Plan::create([
            'name' => 'Pago Único - 3 Mascotas',
            'type' => 'one_time',
            'duration_months' => null,
            'pets_included' => 3,
            'price' => 50000,
            'additional_pet_price' => 10000,
            'is_active' => true,
            'description' => 'Plan de pago único para 3 mascotas. Mascotas adicionales: ₡10,000 c/u',
            'sort_order' => 3,
        ]);

        // PLANES DE SUSCRIPCIÓN - 1 MES
        Plan::create([
            'name' => 'Suscripción 1 Mes - 1 Mascota',
            'type' => 'subscription',
            'duration_months' => 1,
            'pets_included' => 1,
            'price' => 2000,
            'additional_pet_price' => 1500,
            'is_active' => true,
            'description' => 'Suscripción mensual para 1 mascota. Mascotas adicionales: ₡1,500 c/u',
            'sort_order' => 4,
        ]);

        Plan::create([
            'name' => 'Suscripción 1 Mes - 2 Mascotas',
            'type' => 'subscription',
            'duration_months' => 1,
            'pets_included' => 2,
            'price' => 4000,
            'additional_pet_price' => 1500,
            'is_active' => true,
            'description' => 'Suscripción mensual para 2 mascotas. Mascotas adicionales: ₡1,500 c/u',
            'sort_order' => 5,
        ]);

        Plan::create([
            'name' => 'Suscripción 1 Mes - 3 Mascotas',
            'type' => 'subscription',
            'duration_months' => 1,
            'pets_included' => 3,
            'price' => 5000,
            'additional_pet_price' => 1500,
            'is_active' => true,
            'description' => 'Suscripción mensual para 3 mascotas. Mascotas adicionales: ₡1,500 c/u',
            'sort_order' => 6,
        ]);

        // PLANES DE SUSCRIPCIÓN - 3 MESES
        Plan::create([
            'name' => 'Suscripción 3 Meses - 1 Mascota',
            'type' => 'subscription',
            'duration_months' => 3,
            'pets_included' => 1,
            'price' => 5000,
            'additional_pet_price' => 3000,
            'is_active' => true,
            'description' => 'Suscripción trimestral para 1 mascota. Mascotas adicionales: ₡3,000 c/u',
            'sort_order' => 7,
        ]);

        Plan::create([
            'name' => 'Suscripción 3 Meses - 2 Mascotas',
            'type' => 'subscription',
            'duration_months' => 3,
            'pets_included' => 2,
            'price' => 10000,
            'additional_pet_price' => 3000,
            'is_active' => true,
            'description' => 'Suscripción trimestral para 2 mascotas. Mascotas adicionales: ₡3,000 c/u',
            'sort_order' => 8,
        ]);

        Plan::create([
            'name' => 'Suscripción 3 Meses - 3 Mascotas',
            'type' => 'subscription',
            'duration_months' => 3,
            'pets_included' => 3,
            'price' => 15000,
            'additional_pet_price' => 3000,
            'is_active' => true,
            'description' => 'Suscripción trimestral para 3 mascotas. Mascotas adicionales: ₡3,000 c/u',
            'sort_order' => 9,
        ]);

        // PLANES DE SUSCRIPCIÓN - 6 MESES
        Plan::create([
            'name' => 'Suscripción 6 Meses - 1 Mascota',
            'type' => 'subscription',
            'duration_months' => 6,
            'pets_included' => 1,
            'price' => 10000,
            'additional_pet_price' => 5000,
            'is_active' => true,
            'description' => 'Suscripción semestral para 1 mascota. Mascotas adicionales: ₡5,000 c/u',
            'sort_order' => 10,
        ]);

        Plan::create([
            'name' => 'Suscripción 6 Meses - 2 Mascotas',
            'type' => 'subscription',
            'duration_months' => 6,
            'pets_included' => 2,
            'price' => 20000,
            'additional_pet_price' => 5000,
            'is_active' => true,
            'description' => 'Suscripción semestral para 2 mascotas. Mascotas adicionales: ₡5,000 c/u',
            'sort_order' => 11,
        ]);

        Plan::create([
            'name' => 'Suscripción 6 Meses - 3 Mascotas',
            'type' => 'subscription',
            'duration_months' => 6,
            'pets_included' => 3,
            'price' => 25000,
            'additional_pet_price' => 5000,
            'is_active' => true,
            'description' => 'Suscripción semestral para 3 mascotas. Mascotas adicionales: ₡5,000 c/u',
            'sort_order' => 12,
        ]);
    }
}
