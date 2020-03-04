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
        .text-red{
            color:red;
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
		<th>rank</th>
        <th>user</th>
		<th>pageviews</th>
        <th>data_volume_sent</th>
		<th>last visit</th>
    </tr>
    <?php $c = 0; $totalBytes = 0; $totalPageViews = 0; $rank = 1; ?>
	<?php foreach($stats->getStatsData() as $d): ?>
		<tr style="background-color: rgba(<?=(250-$c)?>,<?=(200-$c)?>,50,0.5);">
        <?php $totalBytes +=  $d['total_bytes']; $totalPageViews += $d['pageviews']; ?> 
        <td><?=$rank?></td>
        <td><?=$d['user_agent']?> <?php if($d['user_agent'] != 'unresolved'):?> (BOT)<?php endif ?></td>
            <td><?=$d['pageviews']?></th>
            <?php if($d['total_bytes']/1024/1024 > 1024): ?>
                <td><?=round($d['total_bytes']/1024/1024/1024,3)?> <span class="text-red">!!GIGABYTES!!</span></th>
            <?php else: ?>
            <td><?=round($d['total_bytes']/1024/1024,2)?> megabytes</th>
            <?php endif ?>
            <td><?=date('Y-m-d H:i',$d['updated_at'])?></th>
        </tr>
        <?php $c+=2; $rank++; ?>
    <?php endforeach ?>
    <tr>
        <td><b>total</b></td>
        <td><b><?=$totalPageViews?></b></td>
        <td><b><?=round($totalBytes/1024/1024/1024,3)?> GIGABYTES</b></td>
    </tr>
    </table>
    
    <?php elseif($page == 'about'): //---------------------------------------------- ?>   
    
    <nav>
		<a href="/">home</a>
	</nav>

	<h1>about</h1>
    <p>Search engines are extremely powerful machines. The algorithms behind these machines decide which information is considered relevant and comes into our field of vision. The basis of all search engines is the gathering of information. For this purpose, so-called bots or crawlers are used. These bots visit every page of the visible Internet at regular intervals and analyze and store the information that a website provides. The crawlers follow all links on a page and work their way through all subpages of a website.</p>
    <p>But what would happen if a single website had an infinite number of subpages that are all linked to each other? This is exactly what the project NOTENO does. The page itself consists of only a few kilobytes and less than 10 files, but offers an infinite number of pages that are all filled with total nonsense.</p>
    <p>
    <b>Noteno is an open source project. Get the code from github and start feeding...</b><br><br>

    <a href="https://github.com/rgb3000/noteno">https://github.com/rgb3000/noteno</a>
    </p>
    <?php else: //---------------------------------------------- ?>
        
        <nav>
            <ul>
                <li>
                    <a href="/page-<?=bcadd($page, 1);?>.html">next page</a> 
                </li>
                <li>
                    <a href="/stats.html">statistics</a>
                </li>
                <li><a href="/about.html">about</a></li>
            </ul>
        </nav>
    
        <article>
            <h1><?=$h1?></h1>
            <?= $txt?>
        </article>


<?php endif //---------------------------------------------- ?>
</body>
</html>