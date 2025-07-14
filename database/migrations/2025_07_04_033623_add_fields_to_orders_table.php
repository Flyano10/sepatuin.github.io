<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'product_id')) {
                $table->foreignId('product_id')->constrained()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'quantity')) {
                $table->integer('quantity')->default(1)->after('product_id');
            }

            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->integer('total_price')->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'product_id')) {
                $table->dropConstrainedForeignId('product_id');
            }

            if (Schema::hasColumn('orders', 'quantity')) {
                $table->dropColumn('quantity');
            }

            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};
