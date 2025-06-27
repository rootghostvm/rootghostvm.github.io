<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$services = getServices($pdo);
$updates = getRecentUpdates($pdo, 10);

include __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <?php include __DIR__ . '/../includes/nav.php'; ?>
    
    <main class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <a href="post_update.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                Post Update
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">System Overview</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Monitored Services</p>
                        <p class="text-2xl font-bold"><?= count($services) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Recent Updates</p>
                        <p class="text-2xl font-bold"><?= count($updates) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Post Update Form -->
            <div class="bg-white rounded-xl shadow-md p-6 md:col-span-2">
                <h2 class="text-lg font-semibold mb-4">Post Status Update</h2>
                <form action="post_update.php" method="POST">
                    <div class="mb-4">
                        <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select id="service" name="service_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                            <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                            <option value="operational">Operational</option>
                            <option value="degraded">Degraded Performance</option>
                            <option value="outage">Major Outage</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea id="message" name="message" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                            Post Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Recent Updates -->
        <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Recent Updates</h2>
            </div>
            
            <?php if (empty($updates)): ?>
                <div class="p-6 text-center text-gray-500">
                    No updates available
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($updates as $update): ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 text-xs rounded-full <?= getStatusColor($update['status']) ?>">
                                <?= ucfirst($update['status']) ?>
                            </span>
                            <span class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($update['created_at'])) ?></span>
                        </div>
                        <h3 class="font-medium"><?= htmlspecialchars($update['service_name']) ?></h3>
                        <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($update['message'])) ?></p>
                        <div class="mt-3 text-sm text-gray-500">
                            Posted by <?= htmlspecialchars($update['username']) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
