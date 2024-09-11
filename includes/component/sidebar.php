<aside class="w-64 bg-white shadow-lg">
    <div class="p-4">
        <div class="flex items-center justify-center mb-4 border-b border-gray-100">
            <img src="../img/task_logo.png" alt="LOGO" class="w-32 object-contain transition duration-300 ease-in-out hover:opacity-70 hover:filter hover:drop-shadow-lg hover:contrast-125 hover:brightness-0 hover:invert hover:sepia">
        </div>
        <nav>
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>

            <ul class="space-y-6">
                <li class="mb-4">
                    <a href="../main/dashboard.php" class="flex items-center text-gray-600 hover:text-blue-600 <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-100 text-blue-800 font-semibold rounded-lg p-2 transition-all' : ''; ?>">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../main/task.php" class="flex items-center text-gray-600 hover:text-blue-600 <?php echo ($current_page == 'task.php') ? 'bg-blue-100 text-blue-800 font-semibold rounded-lg p-2 transition-all' : ''; ?>">
                        <i class="fas fa-tasks mr-3"></i>
                        <span>Tasks</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../main/categories.php" class="flex items-center text-gray-600 hover:text-blue-600 <?php echo ($current_page == 'categories.php') ? 'bg-blue-100 text-blue-800 font-semibold rounded-lg p-2 transition-all' : ''; ?>">
                        <i class="fas fa-cog mr-3"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../main/statistics.php" class="flex items-center text-gray-600 hover:text-blue-600 <?php echo ($current_page == 'statistics.php') ? 'bg-blue-100 text-blue-800 font-semibold rounded-lg p-2 transition-all' : ''; ?>">
                        <i class="fas fa-chart-line mr-3"></i>
                        <span>Statistics</span>
                    </a>
                </li>
            </ul>

        </nav>
    </div>
</aside>