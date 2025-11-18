<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Método de pago: 'transfer' (transferencia bancaria) o 'sinpe' (SINPE Móvil)
            $table->string('payment_method')->nullable()->after('total')->comment('transfer o sinpe');

            // Información adicional para SINPE (número de teléfono del cliente)
            $table->string('sinpe_phone')->nullable()->after('payment_method')->comment('Teléfono usado para SINPE');

            // Descripción/referencia del pago (para SINPE principalmente)
            $table->text('payment_description')->nullable()->after('sinpe_phone')->comment('Descripción del pago para identificación');

            // Zona de envío: 'gam' o 'fuera_gam'
            $table->string('shipping_zone')->nullable()->after('payment_description')->comment('gam o fuera_gam');

            // Costo del envío (1500 para GAM, 3500 para fuera del GAM)
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_zone')->comment('Costo del envío');

            // Dirección de envío
            $table->text('shipping_address')->nullable()->after('shipping_cost')->comment('Dirección completa de envío');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'sinpe_phone',
                'payment_description',
                'shipping_zone',
                'shipping_cost',
                'shipping_address'
            ]);
        });
    }
};
