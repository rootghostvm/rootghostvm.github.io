<?php
require_once __DIR__ . '/config.php';

$services = getServices($pdo);
$updates = getRecentUpdates($pdo);

// Calculate overall status
$allOperational = true;
foreach ($services as $service) {
    if (getCurrentStatus($pdo, $service['id']) !== 'operational') {
        $allOperational = false;
        break;
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <?php include __DIR__ . '/includes/nav.php'; ?>
    
    <main class="container mx-auto px-4 py-8">
        <!-- Status Overview -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 bg-gradient-to-r from-purple-800 to-indigo-800 text-white">
                <h1 class="text-3xl font-bold"><?= SITE_NAME ?></h1>
                <div class="flex items-center mt-2">
                    <span class="h-4 w-4 rounded-full <?= $allOperational ? 'bg-green-400' : 'bg-yellow-400' ?> mr-2"></span>
                    <span class="text-lg"><?= $allOperational ? 'All Systems Operational' : 'Service Degradation' ?></span>
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Service Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($services as $service): 
                        $status = getCurrentStatus($pdo, $service['id']);
                    ?>
                    <div class="border rounded-lg p-4 <?= getStatusColor($status) ?>">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium"><?= htmlspecialchars($service['name']) ?></h3>
                            <span class="px-2 py-1 text-xs rounded-full <?= getStatusColor($status) ?>">
                                <?= getStatusIcon($status) ?> <?= ucfirst($status) ?>
                            </span>
                        </div>
                        <p class="text-sm mt-2 text-gray-600"><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Updates -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Recent Updates</h2>
            </div>
            
            <?php if (empty($updates)): ?>
                <div class="p-6 text-center text-gray-500">
                    No updates available
                </div>
            <?php else: ?>
                <?php foreach ($updates as $update): ?>
                <div class="p-6 border-b border-gray-200 last:border-b-0">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 text-xs rounded-full <?= getStatusColor($update['status']) ?>">
                            <?= ucfirst($update['status']) ?>
                        </span>
                        <span class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($update['created_at'])) ?></span>
                    </div>
                    <h3 class="font-medium text-lg"><?= htmlspecialchars($update['service_name']) ?></h3>
                    <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($update['message'])) ?></p>
                    <div class="mt-3 text-sm text-gray-500">
                        Posted by <?= htmlspecialchars($update['username']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</div>
