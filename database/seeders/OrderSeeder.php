<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างลูกค้า 10 คน
        $customers = Customer::factory()->count(10)->create();

        // สร้างสินค้า 20 รายการ
        $products = Product::factory()->count(20)->create();

        // สร้างออเดอร์ 15 ออเดอร์
        $orders = Order::factory()
            ->count(15)
            ->create()
            ->each(function ($order) use ($products) {
                // สุ่มจำนวนรายการสินค้าในแต่ละออเดอร์ (1-5 รายการ)
                $itemCount = rand(1, 5);
                
                // สุ่มสินค้าจากรายการสินค้าที่มี
                $selectedProducts = $products->random($itemCount);
                
                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 5);
                    $unitPrice = $product->price;
                    $lineTotal = $quantity * $unitPrice;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ]);
                }
                
                // คำนวณยอดรวมออเดอร์
                $order->calculateTotals();
            });

        // สร้างออเดอร์ตัวอย่างที่มีสถานะต่างๆ
        $this->createSampleOrders($customers, $products);
    }

    /**
     * สร้างออเดอร์ตัวอย่างที่มีสถานะต่างๆ
     */
    private function createSampleOrders($customers, $products): void
    {
        // ออเดอร์รอดำเนินการ
        $pendingOrder = Order::create([
            'customer_id' => $customers->random()->id,
            'order_number' => 'ORD-000001',
            'status' => OrderStatus::Pending,
            'shipping_address' => '123 ถนนสุขุมวิท แขวงคลองตัน เขตคลองตัน กรุงเทพมหานคร 10110',
            'notes' => 'รอการยืนยันจากลูกค้า',
            'subtotal' => 0,
            'total' => 0,
        ]);

        $product1 = $products->random();
        $product2 = $products->random();
        
        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'unit_price' => $product1->price,
            'line_total' => 2 * $product1->price,
        ]);

        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'unit_price' => $product2->price,
            'line_total' => $product2->price,
        ]);

        $pendingOrder->calculateTotals();

        // ออเดอร์ยืนยันแล้ว
        $confirmedOrder = Order::create([
            'customer_id' => $customers->random()->id,
            'order_number' => 'ORD-000002',
            'status' => OrderStatus::Confirmed,
            'shipping_address' => '456 ถนนพหลโยธิน แขวงลาดยาว เขตจตุจักร กรุงเทพมหานคร 10900',
            'notes' => 'พร้อมจัดส่ง',
            'subtotal' => 0,
            'total' => 0,
        ]);

        $product3 = $products->random();
        OrderItem::create([
            'order_id' => $confirmedOrder->id,
            'product_id' => $product3->id,
            'quantity' => 3,
            'unit_price' => $product3->price,
            'line_total' => 3 * $product3->price,
        ]);

        $confirmedOrder->calculateTotals();

        // ออเดอร์จัดส่งแล้ว
        $shippedOrder = Order::create([
            'customer_id' => $customers->random()->id,
            'order_number' => 'ORD-000003',
            'status' => OrderStatus::Shipped,
            'shipping_address' => '789 ถนนสีลม แขวงสีลม เขตบางรัก กรุงเทพมหานคร 10500',
            'notes' => 'จัดส่งโดย Kerry Express',
            'subtotal' => 0,
            'total' => 0,
        ]);

        $product4 = $products->random();
        $product5 = $products->random();
        
        OrderItem::create([
            'order_id' => $shippedOrder->id,
            'product_id' => $product4->id,
            'quantity' => 1,
            'unit_price' => $product4->price,
            'line_total' => $product4->price,
        ]);

        OrderItem::create([
            'order_id' => $shippedOrder->id,
            'product_id' => $product5->id,
            'quantity' => 2,
            'unit_price' => $product5->price,
            'line_total' => 2 * $product5->price,
        ]);

        $shippedOrder->calculateTotals();

        // ออเดอร์ส่งถึงแล้ว
        $deliveredOrder = Order::create([
            'customer_id' => $customers->random()->id,
            'order_number' => 'ORD-000004',
            'status' => OrderStatus::Delivered,
            'shipping_address' => '321 ถนนรัชดาภิเษก แขวงห้วยขวาง เขตห้วยขวาง กรุงเทพมหานคร 10310',
            'notes' => 'ลูกค้ารับสินค้าแล้ว',
            'subtotal' => 0,
            'total' => 0,
        ]);

        $product6 = $products->random();
        OrderItem::create([
            'order_id' => $deliveredOrder->id,
            'product_id' => $product6->id,
            'quantity' => 5,
            'unit_price' => $product6->price,
            'line_total' => 5 * $product6->price,
        ]);

        $deliveredOrder->calculateTotals();
    }
}
