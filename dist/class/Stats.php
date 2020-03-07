<?php

Class Stats{

private $db;

function __construct() {
    if(!file_exists('stats.db')){
        $this->initDB();
    }
}


private function initDB(){
    
    $this->db = new PDO('sqlite:stats.db');

    //tabe stats per user agent
    $this->db->exec("CREATE TABLE IF NOT EXISTS stats ( 
                    user_agent varchar(255) PRIMARY KEY, 
                    pageviews INTEGER,
                    total_bytes INTEGER,
                    updated_at INTEGER)");
    
    //stats per day
    $this->db->exec("CREATE TABLE IF NOT EXISTS counter_day ( 
        id varchar(255) PRIMARY KEY, 
        pageviews INTEGER,
        total_bytes INTEGER,
        updated_at INTEGER)");
}

public function incrementData($bytes){

    $ua = $_SERVER['HTTP_USER_AGENT'];

    $CrawlerDetect = new CrawlerDetect;

    if($CrawlerDetect->isCrawler($ua)) {
        $agent = $CrawlerDetect->getMatches();
    }
    else{
        $agent = 'unresolved';
    }

    $this->db = new PDO('sqlite:stats.db');
    $this->db->exec("INSERT INTO stats (user_agent, pageviews, total_bytes,updated_at) VALUES ('".$agent."', 1,".$bytes.",".time().")");
    $this->db->exec("UPDATE stats SET pageviews = pageviews + 1, total_bytes = total_bytes + ".$bytes.",updated_at = ".time()." WHERE user_agent = '".$agent."' ");

    $this->db->exec("INSERT INTO counter_day (id, pageviews, total_bytes,updated_at) VALUES ('".date('Y-m-d')."', 1,".$bytes.",".time().")");
    $this->db->exec("UPDATE counter_day SET pageviews = pageviews + 1, total_bytes = total_bytes + ".$bytes.",updated_at = ".time()." WHERE id = '".date('Y-m-d')."' ");
}


public function getStatsData(){
    $this->db = new PDO('sqlite:stats.db');
    $r = [];
    $r['agents'] = $this->db->query('SELECT * FROM stats ORDER BY total_bytes DESC')->fetchAll();
    $r['hitsPerMonth'] = $this->db->query('SELECT * FROM counter_day ORDER BY id DESC')->fetchAll();
    return $r;
}


public function render(){
    $data = $this->getStatsData();
    ?>
    <h3>amount of nonsense information successfully deliverd to humans and bots (since october 2017)</h3>
    <table>
	<tr>
		<th>rank</th>
        <th>user</th>
		<th>pageviews</th>
        <th>data_volume_sent</th>
		<th>last visit</th>
    </tr>
    <?php $c = 0; $totalBytes = 0; $totalPageViews = 0; $rank = 1; ?>
	<?php foreach($data['agents'] as $d): ?>
		<tr style="background-color: rgba(<?=(250-$c)?>,<?=(200-$c)?>,50,0.5);">
        <?php $totalBytes +=  $d['total_bytes']; $totalPageViews += $d['pageviews']; ?> 
        <td><?=$rank?></td>
        <td><?=$d['user_agent']?> <?php if($d['user_agent'] != 'unresolved'):?> (BOT)<?php endif ?></td>
            <td><?=number_format($d['pageviews'],0,',','.')?></td>
            <?php if($d['total_bytes']/1024/1024 > 1024): ?>
                <td><?=round($d['total_bytes']/1024/1024/1024,3)?> <span class="text-red">!!GIGABYTES!</span></td>
            <?php else: ?>
            <td><?=round($d['total_bytes']/1024/1024,2)?> megabytes</td>
            <?php endif ?>
            <td><?=date('Y-m-d H:i',$d['updated_at'])?></td>
        </tr>
        <?php $c+=2; $rank++; ?>
    <?php endforeach ?>
    <tr>
        <td></td>
        <td><b>total</b></td>
        <td><b><?=number_format($totalPageViews,0,',','.')?></b></td>
        <td><b><?=round($totalBytes/1024/1024/1024,3)?> <span class="text-red">!!GIGABYTES!</span></b></td>
    </tr>
    </table>

    <?php
    $maxHits = 0;
    foreach($data['hitsPerMonth'] as $d){
        if($d['pageviews'] > $maxHits){
            $maxHits = $d['pageviews'];
        }
    }
    ?>

    <h3 style="margin-top: 50px;">total pageviews per day (started march 2020)</h3>
    <div style="width: 90%;">
    <?php foreach($data['hitsPerMonth'] as $d): ?>
        <div class="d-flex" style="margin-bottom: 2px;">
            <div style="padding: 1px;padding-right: 20px;"><?=$d['id']?></div>
            <div style="padding: 1px;background-color: rgba(<?=round($d['pageviews']/$maxHits*255)?>,150,100,0.5);width: <?=round($d['pageviews']/$maxHits*100)?>%"><?=$d['pageviews']?></div>
        </div>
    <?php endforeach ?>
    </div>
    <?php
}



}