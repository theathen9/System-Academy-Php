<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

<div class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-gray-800 p-5">
    <h1 class="text-2xl font-bold mb-8">Admin Panel</h1>
    <nav class="space-y-4">
      <a href="#" class="block hover:text-green-400">Dashboard</a>
      <a href="#" class="block hover:text-green-400">Users</a>
      <a href="#" class="block text-green-400">Analytics</a>
      <a href="#" class="block hover:text-green-400">Reports</a>
      <a href="#" class="block hover:text-green-400">Settings</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-6 overflow-y-auto">

    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold">Analytics</h2>
      <input type="text" placeholder="Search..." class="px-4 py-2 rounded bg-gray-800">
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-gray-800 p-5 rounded-xl">
        <h3>Total Users</h3>
        <p class="text-3xl font-bold">1,245</p>
      </div>
      <div class="bg-gray-800 p-5 rounded-xl">
        <h3>Server Load</h3>
        <p class="text-3xl font-bold">78%</p>
      </div>
      <div class="bg-gray-800 p-5 rounded-xl">
        <h3>Response Time</h3>
        <p class="text-3xl font-bold">120ms</p>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-gray-800 p-6 rounded-xl mb-6">
      <h3 class="mb-4">User Growth</h3>
      <canvas id="lineChart"></canvas>
    </div>

    <!-- Bar Chart -->
    <div class="bg-gray-800 p-6 rounded-xl">
      <h3 class="mb-4">Resource Usage</h3>
      <canvas id="barChart"></canvas>
    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const lineCtx = document.getElementById('lineChart');
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Oct 1','Oct 5','Oct 10','Oct 15','Oct 20','Oct 25'],
      datasets: [{
        label: 'Users',
        data: [20, 50, 40, 70, 90, 120],
        borderColor: '#34d399',
        backgroundColor: 'rgba(52,211,153,0.2)',
        fill: true
      }]
    }
  });

  const barCtx = document.getElementById('barChart');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Auth','DB','API','CPU'],
      datasets: [{
        label: 'Usage',
        data: [400, 700, 600, 500],
        backgroundColor: ['#34d399','#60a5fa','#fbbf24','#f87171']
      }]
    }
  });
</script>

</body>
</html>