<?php
// === 页面变量（include_once header.php 之前设置）===
$pageTitle = '网站信息';
$pageGreeting = 'DASHBOARD';
$pageHeading = '网站<span>信息</span>';
$activeNav = 'main';

include_once("header.php");
?>

<?php
// 获取服务器内存占用
// $memInfo = [];
// exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value', $memInfo);
// $total = $free = 0;
// foreach ($memInfo as $line) {
//     if (strpos($line, 'TotalVisibleMemorySize') !== false) {
//         $total = (float)trim(str_replace('TotalVisibleMemorySize=', '', $line));
//     }
//     if (strpos($line, 'FreePhysicalMemory') !== false) {
//         $free = (float)trim(str_replace('FreePhysicalMemory=', '', $line));
//     }
// }
$memoryUsage = mt_rand(0, 100);

// 获取CPU占用
$cpuUsage = mt_rand(0, 100);
// exec('wmic cpu get loadpercentage /Value', $cpuInfo);
// foreach ($cpuInfo as $line) {
//     if (strpos($line, 'LoadPercentage') !== false) {
//         $cpuUsage = (float)trim(str_replace('LoadPercentage=', '', $line));
//         break;
//     }
// }
?>

<style>
.stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 32px;
    transition: border-color 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.stat-card:hover { border-color: var(--accent); }
.stat-card .label { font-size: 12px; color: var(--muted); letter-spacing: 2px; margin-bottom: 16px; }
.stat-card .value { font-size: 36px; font-weight: 700; margin-bottom: 8px; }
.stat-card .value span { font-size: 16px; color: var(--muted); font-weight: 400; margin-left: 4px; }
.stat-card .desc { font-size: 12px; color: #bbb; }
.stat-card.accent .value { color: var(--accent); }
</style>

<div class="stats">
    <div class="stat-card accent">
        <div class="label">今日访客</div>
        <div class="value">1,000</div>
        <div class="desc">REAL-TIME VISITORS</div>
    </div>
    <div class="stat-card">
        <div class="label">服务器内存</div>
        <div class="value"><?php echo $memoryUsage; ?><span>%</span></div>
        <div class="desc">MEMORY USAGE</div>
    </div>
    <div class="stat-card">
        <div class="label">CPU 占用</div>
        <div class="value"><?php echo $cpuUsage; ?><span>%</span></div>
        <div class="desc">CPU USAGE</div>
    </div>
    <div class="stat-card">
        <div class="label">当前时间</div>
        <div class="value"><?php echo date('Y-m-d H:i:s'); ?></div>
        <div class="desc">SERVER TIME</div>
    </div>
</div>
</body>
</html>
