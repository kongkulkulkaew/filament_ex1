<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ลูกค้าทั้งหมด</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCustomers }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ลูกค้าที่มีออเดอร์</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $customersWithOrders }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ลูกค้าที่ไม่มีออเดอร์</h3>
            <p class="text-3xl font-bold text-gray-600 dark:text-gray-400 mt-2">{{ $customersWithoutOrders }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ออเดอร์ทั้งหมด</h3>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $totalOrders }}</p>
        </div>
    </div>
</div>
