<?php
/*********************************************************
| eXtreme-Fusion
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
| 
**********************************************************/
require_once '../../infusions/migration_toolkit/class/class.Converter.php';

if ( ! isset($_COOKIE['efc_core']))
{
	require_once '../../maincore.php';
}
else
{
	require_once '../../config.php';
}

$aid = (isset($_GET['aid']) ? $_GET['aid'] : '');

if (file_exists('../../infusions/migration_toolkit/locale/'.(isset($_COOKIE['efc_lang']) ? $_COOKIE['efc_lang'] : $settings['locale']).'.php')) 
{
	include '../../infusions/migration_toolkit/locale/'.(isset($_COOKIE['efc_lang']) ? $_COOKIE['efc_lang'] : $settings['locale']).'.php';
} 
else
{
	include '../../infusions/migration_toolkit/locale/English.php';
}

$_EFC = New Converter($EFC_Locale, array($db_prefix, $db_host, $db_user, $db_pass, $db_name));

$start_gen = $_EFC->getTime();

if ( ! isset($_GET['step']) || ! preg_match("/^([0-9]{1,2})$/D", $_GET['step']))
{ 
	$_GET['step'] = '1'; 
}
if ($_GET['step'] <> '1' && ! isset($_POST['check']))
{ 
	$_GET['step'] = '1';
}

$steps = array(
	1 => "Najważniejsze informacje przed instalacją",
	2 => "Tworzenie kopii bazy",
	3 => "Tworzenie nowych tabel",
	4 => "Tworzenie struktury ustawień",
	5 => "Usunięcie nie potrzebnych tabel",
	6 => "Przetwarzanie pozostałych tabel",
	7 => "Przetwarzanie pozostałych tabel",
	8 => "Przetwarzanie pozostałych tabel",
	9 => "Przetwarzanie pozostałych tabel",
	10 => "Sukces! Migracja zakończona",
);

$cookies = 360;

echo "
<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'>
		<title>Migration Toolkit ".$_EFC->geteXtremeFusionVersion()." » ".$_EFC->getNeweXtremeFusionVersion()."</title>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/grid.reset.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/grid.text.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/grid.960.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/jquery.uniform.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/jquery.table.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/jquery.ui.css'>
		<link rel='stylesheet' href='../../infusions/migration_toolkit/stylesheet/main.css'>
		<script src='../../infusions/migration_toolkit/javascripts/jquery.js'></script>
		<script src='../../infusions/migration_toolkit/javascripts/jquery.uniform.js'></script>
		<script src='../../infusions/migration_toolkit/javascripts/main.js'></script>
	</head>
	<body>
		<header>
			<div class='container_12'>
				<img src='../../infusions/migration_toolkit/images/extreme-fusion-logo.png' alt='Migration Toolkit ".$_EFC->geteXtremeFusionVersion()." » ".$_EFC->getNeweXtremeFusionVersion()."'>
			</div>
		</header>	
			<div id='Content'>
			<div class='corner4px'><div class='ctl'><div class='ctr'><div class='ctc'></div></div></div><div class='cc'>
				<div id='IframeOPT' class='container_12' >



					<div class='clear'></div>
					<h3 class='ui-corner-all'>
						Migration Toolkit ".$_EFC->geteXtremeFusionVersion()." » ".$_EFC->getNeweXtremeFusionVersion()." | Migracja krok: ".$_GET['step']." » ".($steps[$_GET['step']])."
					</h3>

					<ul id='InstalationSteps'>
							<li class='".($_GET['step'] < '1' ? 'bold' : $_GET['step'] == '1' ? $_GET['step'] == '1' ? 'bold' : '' : 'done')."'>Krok 1</li>
							<li class='".($_GET['step'] < '2' ? 'bold' : $_GET['step'] == '2' ? $_GET['step'] == '2' ? 'bold' : '' : 'done')."'>Krok 2</li>
							<li class='".($_GET['step'] < '3' ? 'bold' : $_GET['step'] == '3' ? $_GET['step'] == '3' ? 'bold' : '' : 'done')."'>Krok 3</li>
							<li class='".($_GET['step'] < '4' ? 'bold' : $_GET['step'] == '4' ? $_GET['step'] == '4' ? 'bold' : '' : 'done')."'>Krok 4</li>
							<li class='".($_GET['step'] < '5' ? 'bold' : $_GET['step'] == '5' ? $_GET['step'] == '5' ? 'bold' : '' : 'done')."'>Krok 5</li>
							<li class='".($_GET['step'] < '6' ? 'bold' : $_GET['step'] == '6' ? $_GET['step'] == '6' ? 'bold' : '' : 'done')."'>Krok 6</li>
							<li class='".($_GET['step'] < '7' ? 'bold' : $_GET['step'] == '7' ? $_GET['step'] == '7' ? 'bold' : '' : 'done')."'>Krok 7</li>
							<li class='".($_GET['step'] < '8' ? 'bold' : $_GET['step'] == '8' ? $_GET['step'] == '8' ? 'bold' : '' : 'done')."'>Krok 8</li>
							<li class='".($_GET['step'] < '9' ? 'bold' : $_GET['step'] == '9' ? $_GET['step'] == '9' ? 'bold' : '' : 'done')."'>Krok 9</li>
							<li class='".($_GET['step'] < '10' ? 'bold' : $_GET['step'] == '10' ? $_GET['step'] == '10' ? 'bold' : '' : 'done')."'>Krok 10</li>
					</ul>

					<div id='MainBox'>";

					if((isset($_COOKIE['efc_core']) ? ! $_COOKIE['efc_core'] : ! iSUPERADMIN))
					{
						echo "<p class='error'>Jeśli chcesz użyć konwertera, musisz być zalogowany oraz posiadać rangę Super Administratora!</p>";
						setcookie("efc_superamdin", TRUE, time() + $cookies);
					}
					else
					{
						if($_GET['step'] == '1')
						{
							unset($_COOKIE["efc_".md5($aid)]);
							unset($_COOKIE["efc_lang"]);
							unset($_COOKIE["efc_vers"]);
							unset($_COOKIE["efc_core"]);
							unset($_COOKIE["efc_superamdin"]);
							if (isset($settings['ep_version']) && $settings['ep_version'] !== $_EFC->geteXtremeFusionVersion())
							{
								echo "<p class='error'>Jeśli chcesz użyć tego konwertera, musisz mieć zainstalowany CMS eXtreme-Fusion 4.17!</p>";
							}
							else
							{
								echo "
								<p class='info'>Witaj w wtyczce, która pomoże Ci przekonwertować <a href='http://extreme-fusion.org/'>eXtreme-Fusion ".$_EFC->geteXtremeFusionVersion()."</a>, na system <a href='http://extreme-fusion.org/'>eXtreme-Fusion ".$_EFC->getNeweXtremeFusionVersion()."</a>. Jeśli jeszcze nie zrobiłeś kopii zapasowej bazy danych oraz plików na serwerze, teraz jest to najlepszy moment aby to uczynić, jeśli coś pójdzie nie tak będziesz mógł bez problemu przywrócić kopię bazy danych.</p>
								
								<p class='error'>
									Pamiętaj, jeśli zaczniesz operacje bez posiadania kopi swojej bazy danych narażasz się na utratę cennych danych.<br />
									W przypadku gdy wtyczka napotkałaby błąd, Twoja strona mogła by działać nie stabilnie.<br />
									Dlatego zaleca się aby przed wykonywaniem tej czynności zaopatrzyć się w kopię całej bazy danych którą wykonasz takim narzędziem jak PHPMyAdmin, Chive, SQLBuddy itp.<br />
								</p>
								
								<p class='status'>Przed rozpoczęciem migracji proszę zapoznać się z licencją:<br /> <a id='AcceptLink' href='https://raw.github.com/extreme-fusion/eXtreme-Fusion-CMS/master/LICENSE' target='_blank'>aGPL v3 License</a></p>
								
								<p class='info'>
									Aby przejść do kolejnego kroku kliknij na przycisk <strong>\"Dalej\"</strong>.
								</p>
								<form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=2'>
								<p><input type='submit' name='check' value='Dalej'/></p>
								</form>
							</div>";
							}
						}
						elseif($_GET['step'] == '2')
						{	
							echo "<div class='tbl2'>";
							echo "<p class='info'>Wykonaj prosze kopię bazy...<p>";
							setcookie("efc_".md5($aid), $aid, time() + $cookies);
							setcookie("efc_lang", $settings['locale'], time() + $cookies);
							setcookie("efc_vers", $settings['ep_version'], time() + $cookies);
							setcookie("efc_core", TRUE, time() + $cookies);
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=3'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '3')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie tworzone są nowe tabele wymagene przez system.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>".$db_prefix.$value['name']."</strong> została utworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>".$db_prefix.$value['name']."</strong> nie została utworzona.</li>";
								}
							}
							echo "</ul>";
							
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=4'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '4')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie przetwarzane są struktury tabel.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> nie została przetworzona, zgłoś ten błąd!</li>";
								}
							}
							echo "</ul>";
							
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=5'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '5')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie usuwane są stare tabele nie potrzebne już w systemie.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."articles</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."article_cats</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."custom_pages</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."downloads</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."download_cats</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."faqs</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."faq_cats</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."forums</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."forum_attachments</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."flood_control</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."infusions</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."messages_options</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."new_users</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."photos</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."photo_albums</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."poll_votes</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."polls</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."posts</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."ratings</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."submissions</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."vcode</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."buttons</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."cautions</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."cautions_config</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."colors</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."panels_article</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."panels_download</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."panels_forum</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."rss_builder</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."site_links</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."site_links_groups</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."threads</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."thread_notify</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."user_groups</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."eps_points</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."eps_rangs</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."forumrang</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."weblinks</strong> została przetworzona.</li>";
									echo "<li class='Valid'>Tabela <strong>".$db_prefix."weblink_cats</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."articles</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."article_cats</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."captcha</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."custom_pages</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."downloads</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."download_cats</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."faqs</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."faq_cats</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."forums</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."forum_attachments</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."flood_control</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."infusions</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."messages_options</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."new_users</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."photos</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."photo_albums</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."poll_votes</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."polls</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."posts</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."ratings</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."submissions</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."vcode</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."buttons</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."cautions</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."cautions_config</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."colors</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."panels_article</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."panels_download</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."panels_forum</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."rss_builder</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."site_links</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."site_links_groups</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."threads</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."thread_notify</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."user_groups</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."eps_points</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."eps_rangs</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."forumrang</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."weblinks</strong> nie została przetworzona.</li>";
									echo "<li class='Warning'>Tabela <strong>".$db_prefix."weblink_cats</strong> nie została przetworzona.</li>";
								}
							}
							echo "</ul>";
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=6'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '6')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie przetwarzana jest reszta tabel.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> nie została przetworzona.</li>";
								}
							}
							echo "</ul>";
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=7'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '7')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie przetwarzana jest reszta tabel.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> nie została przetworzona.</li>";
								}
							}
							echo "</ul>";
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=8'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '8')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie przetwarzana jest reszta tabel.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> nie została przetworzona.</li>";
								}
							}
							echo "</ul>";
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=9'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '9')
						{
							$r = $_EFC->stepNum(intval($_GET['step']));
							echo "<div class='tbl2'>";
							echo "<p class='info'>Na tym etapie przetwarzana jest reszta tabel.<p>";
							
							echo "<ul class='left'>";
							foreach ($r as $value)
							{
								if ($value['status'] === TRUE)
								{
									echo "<li class='Valid'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> została przetworzona.</li>";
								}
								else
								{
									echo "<li class='Warning'>Tabela <strong>". $db_prefix.$value['name'] ."</strong> nie została przetworzona.</li>";
								}
							}
							echo "</ul>";
							echo "</div><form method='post' class='center' action='".basename($_SERVER['PHP_SELF'])."?aid=".$aid."&amp;step=10'>
							<p><input type='submit' name='check' value='Dalej' /></p>
							</form>";
						}
						elseif($_GET['step'] == '10')
						{
							echo "<div class='tbl2'>";
							echo "<p class='status'>Sukces!<br />";
							echo "Twój system został zaktualizowany do najnowszej wersji systemu eXtreme-Fusion.<br />";
							echo "Teraz możesz usunąć stare pliki z eXtreme-Fusion i wrzuciś pliki z nowego systemu eXtreme-Fusion.<br />";
							echo "Wyedytuj plik config.php zamieniając jego zawartość na:<br /></p>";
							echo "<div class='center'>".$_EFC->stepNum(intval($_GET['step']))."</div><br />";
							echo "<p class='info'><a href='../../index.php' class='green bold'>Gdy skończysz wrzucać pliki na swój serwer oraz dokonasz edycji pliku config.php będziesz mógł zobaczyć swoją nową stronę klikając na ten napis.</a></p>";
							echo "</div>";
						}
					}
				mysql_close();
				ob_end_flush();
				echo '
				</div>
				<div class="clear"></div>

			</div>
			<hr />
			<div class="tab-click" id="crew-list"><a href="javascript:void(0)">Developers of eXtreme-Fusion v5.0</a></div>
			<div id="tab-crew-list" class="tab-cont">
				<div class="center">
					<div id="leaders">
						<div class="left"><span class="bold">Project founder:</span> Wojciech (zer0) Mycka</div>
						<div class="right"><span class="bold">Project leader:</span> Paweł (Inscure) Zegardło</div>
					</div>
					<div class="clear"></div>

					<div id="team">
						<div class="bold">Code Developers:</div>

						<p>Andrzej (Andrzejster) Sternal</p>
						<p>Dominik (Domon) Barylski</p>
						<p>Paweł (Inscure) Zegardło</p>
						<p>Piotr (piotrex41) Krzysztofik</p>
						<p>Rafał (Rafik89) Krupiński</p>
						<p>Wojciech (zer0) Mycka</p>

						<div class="bold">Design Developers:</div>

						<p>Andrzej (Andrzejster) Sternal </p>
						<p>Piotr (piotrex41) Krzysztofik</p>
						<p>Wojciech (zer0) Mycka </p>

						<div class="bold">Language Team:</div>

						<p>Marcin (Tymcio) Tymków - English language files</p>
						<p>Pavel (LynX) Laurenčík - Czech language files</p>

						<div class="bold">jQuery & Ajax Developers:</div>

						<p>Dominik (Domon) Barylski</p>
						<p>Paweł (Inscure) Zegardło </p>
						<p>Wojciech (zer0) Mycka </p>

						<div class="bold">Beta testers:</div>

						<p>Dariusz (Chomik) Markiewicz</p>
						<p>Mariusz (FoxNET) Bartoszewicz</p>

					</div>
				</div>
			</div>
			<div class="clear"></div>
			<footer>
				<div class="left">
					<p>Copyright © 2005 - 2013 by the <a href="http://extreme-fusion.org/" rel="copyright">eXtreme-Fusion</a> Crew</p>
					<p>Copyright 2002-2013 <a href="http://php-fusion.co.uk/">PHP-Fusion</a>. Released as free software without warranties under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">aGPL v3</a>.</p>
				</div>
				<div class="right">';
					$time_gen = $_EFC->getTime() - $start_gen;
					if($time_gen >= 1) 
					{
						$time_gen = round($time_gen, 5).' sekund';
					} 
					elseif($time_gen < 1) 
					{
						$time_gen = (round($time_gen, 5)*1000).' milisekund ('.round($time_gen, 3).'s)';
					}
					echo 'Czas generowania strony: '.$time_gen.' <br />
					Użycie pamięci: '.(memory_get_peak_usage(TRUE)/1048576).' Mb<br />
					Ilość zapytań: '.$_EFC->getSQLQueries().'
				</div>
			</footer>
			<div class="clear"></div>
		</div>
	</body>
</html>';
?>