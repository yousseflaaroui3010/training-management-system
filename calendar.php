<?php include 'includes/connection.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendrier des Formations</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .calendar-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .month-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #2c3e50;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 30px;
        }
        .day-header {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            background-color: #34495e;
            color: white;
        }
        .day-cell {
            min-height: 80px;
            padding: 5px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .formation-event {
            font-size: 0.8em;
            background: #3498db;
            color: white;
            padding: 2px 5px;
            margin: 2px 0;
            border-radius: 3px;
            cursor: pointer;
        }
        .formation-event:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h2>Calendrier des Formations</h2>
        
        <?php
        // Get current month and year
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        // Get formations for current month
        $start_date = "$year-$month-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $sql = "SELECT fd.date, c.name as cours_name, v.value as ville_name, f.id as formation_id
                FROM formation_dates fd
                JOIN formations f ON fd.formation_id = f.id
                JOIN cours c ON f.cours_id = c.id
                JOIN villes v ON f.ville_id = v.id
                WHERE fd.date BETWEEN ? AND ?
                ORDER BY fd.date";
        
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group formations by date
        $events = [];
        foreach($formations as $f) {
            $day = date('j', strtotime($f['date']));
            if(!isset($events[$day])) {
                $events[$day] = [];
            }
            $events[$day][] = $f;
        }
        
        // Calendar display
        $firstDay = date('N', strtotime($start_date));
        $daysInMonth = date('t', strtotime($start_date));
        ?>
        
        <div class="calendar-container">
            <div class="month-header">
                <a href="?month=<?php echo ($month == 1 ? 12 : $month - 1); ?>&year=<?php echo ($month == 1 ? $year - 1 : $year); ?>" class="btn">←</a>
                <?php echo date('F Y', strtotime($start_date)); ?>
                <a href="?month=<?php echo ($month == 12 ? 1 : $month + 1); ?>&year=<?php echo ($month == 12 ? $year + 1 : $year); ?>" class="btn">→</a>
            </div>
            
            <div class="calendar-grid">
                <!-- Day headers -->
                <div class="day-header">Lun</div>
                <div class="day-header">Mar</div>
                <div class="day-header">Mer</div>
                <div class="day-header">Jeu</div>
                <div class="day-header">Ven</div>
                <div class="day-header">Sam</div>
                <div class="day-header">Dim</div>
                
                <!-- Empty cells before first day -->
                <?php for($i = 1; $i < $firstDay; $i++): ?>
                    <div class="day-cell"></div>
                <?php endfor; ?>
                
                <!-- Days of month -->
                <?php for($day = 1; $day <= $daysInMonth; $day++): ?>
                    <div class="day-cell">
                        <div class="day-number"><?php echo $day; ?></div>
                        <?php if(isset($events[$day])): ?>
                            <?php foreach($events[$day] as $event): ?>
                                <div class="formation-event" onclick="window.location.href='inscription.php?id=<?php echo $event['formation_id']; ?>'">
                                    <?php echo $event['cours_name']; ?> - <?php echo $event['ville_name']; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>