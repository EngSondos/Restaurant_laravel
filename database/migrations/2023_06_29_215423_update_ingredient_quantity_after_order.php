<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER update_ingredient_quantity AFTER INSERT ON order_product
        FOR EACH ROW
        BEGIN
            DECLARE ingredient_id INT;
            DECLARE used_quantity DECIMAL(5,2);
            DECLARE product_quantity INT;

            -- iterate over each product in the order
            FOR product IN (SELECT * FROM products WHERE id IN (SELECT product_id FROM order_product WHERE order_id = NEW.order_id)) DO
                -- iterate over each ingredient in the product
                FOR ingredient IN (SELECT * FROM ingredients WHERE id IN (SELECT ingredient_id FROM product_ingredient WHERE product_id = product.id)) DO
                    -- calculate the quantity used in the product
                    SET product_quantity = (SELECT quantity FROM order_product WHERE order_id = NEW.order_id AND product_id = product.id);
                    SET used_quantity = product_quantity * (SELECT quantity * ingredient.quantity_per_item FROM product_ingredient WHERE product_id = product.id AND ingredient_id = ingredient.id);
        
                    -- update the ingredient\'s quantity
                    UPDATE ingredients SET quantity_used = quantity_used + used_quantity WHERE id = ingredient.id;
                END FOR;
            END FOR;
        END;
    ');    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_ingredient_quantity');    }
};
