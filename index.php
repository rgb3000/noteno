<?php
//----------------------------------------------//----------------------------------------------

include('class/RandomTextGenerator.php');
include('class/Stats.php');
include('class/CrawlerDetection.php');

if(isset($_GET["p"])){
	$page = $_GET["p"];
	if(!ctype_digit($page) && !in_array($page, ['stats','about'])){die();}
}
else{
	$page = 1;
}


$randomTextGenerator = new RandomTextGenerator();
$stats = new Stats();

if($page !== 'stats'){
	
	$h1 = "NOTENO - page: $page | ".$randomTextGenerator->getRandomLine();
	$txt = $randomTextGenerator->getRandomText();
	$stats->incrementData(strlen($h1.$txt));
}

//----------------------------------------------//----------------------------------------------
?>

<!DOCTYPE html>
<html>
<head>
	<title>NOTENO - <?=$page?></title>

	<style>
		body {
			background-color: DarkSeaGreen;
			font-family: monospace;
		}
        table{
            border-collapse: collapse;
        }
		td,th{
			padding-right: 40px;
		}
        td{
            border: 1px solid green;
        }
        ul {
            display: flex;
            margin: 0;
            padding: 0;
        }
        li{
            list-style: none;
            padding-right: 30px;
        }
	</style>
</head>
<body>
<?php if($page == 'stats'): //---------------------------------------------- ?>
    
    <nav>
		<a href="/">home</a>
	</nav>

	<h1>statistics</h1>
    <p>amount of nonsense information successfully deliverd to humans and bots</p>
	<table>
	<tr>
		<th>user</th>
		<th>pageviews</th>
        <th>data_volume_sent</th>
		<th>last visit</th>
    </tr>
    <?php $c = 0; ?>
	<?php foreach($stats->getStatsData() as $d): ?>
		<tr style="background-color: rgba(<?=(250-$c)?>,<?=(200-$c)?>,50,0.5);">
			<td><?=$d['user_agent']?> <?php if($d['user_agent'] != 'human'):?> (BOT)<?php endif ?></td>
            <td><?=$d['pageviews']?></th>
            <?php if($d['total_bytes']/1024/1024 > 1024): ?>
                <td><?=round($d['total_bytes']/1024/1024/1024,3)?> !!GIGABYTES!!</th>
            <?php else: ?>
            <td><?=round($d['total_bytes']/1024/1024,2)?> megabytes</th>
            <?php endif ?>
            <td><?=date('Y-m-d H:i',$d['updated_at'])?></th>
        </tr>
        <?php $c+=2; ?>
	<?php endforeach ?>
    </table>
    
    <?php elseif($page == 'about'): //---------------------------------------------- ?>   
    
        <nav>
		<a href="/">home</a>
	</nav>

	<h1>about</h1>
    <p></p>

    <?php else: //---------------------------------------------- ?>
        
        <nav>
            <ul>
                <li>
                    <a href="/page-<?=bcadd($page, 1);?>.html">next page</a> 
                </li>
                <li>
                    <a href="/stats.html">statistics</a>
                </li>
                <li><a href="/about.html"></a></li>
            </ul>
        </nav>
    
        <article>
            <h1><?=$h1?></h1>
            <?= $txt?>
        </article>


<?php endif //---------------------------------------------- ?>
</body>
</html>