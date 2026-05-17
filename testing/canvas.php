<canvas id="attendanceChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('attendanceChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_keys($data)) ?>,
    datasets: [{
      data: <?= json_encode(array_values($data)) ?>
    }]
  }
});
</script>
