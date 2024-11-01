<?php
/*
Plugin Name: WordPress Ad Blaster
Plugin URI: http://lastnightsdesigns.com/?page_id=166
Description: A new twist on an old idea!! Ad Blaster allows you to Blast your Ads onto any WordPress Site using this plugin. the More people to use this plugin the more your ad is seen.
Version: 4.0
Author: SangrelX
Author URI: http://lastnightsdesigns.com
*/
add_action('admin_menu', 'adb_menus');
add_action('wp_head', 'adb_style');

//set us up the menu

function adb_menus() {
  add_menu_page('Ad Blaster Settings', 'Ad Blaster', 8, __FILE__, adb_main);
  add_submenu_page(__FILE__, 'Hows it Look', 'Appearance', 8, 'adb-sub-page', adb_settings);
  add_submenu_page(__FILE__, 'Blast your Ad', 'Blast your Ad', 8, 'adb-sub-page1', adb_blastit);
  add_submenu_page(__FILE__, 'Terms of Service', 'Terms of Service', 8, 'adb-sub-page2', adb_terms);
}

function adb_terms(){
?>
<h2>Ad Blaster Terms of Service</h2><p>Ad Blaster users are subject to specific Terms of Service / Use. Many people will be seeing your Ads, This includes Children. So we must do what we can to ensure Clean & Mature ads are being Placed. Please Follow This simple list of Rules when it comes to submitting Ads. Failure to Follow these rules will result in your site being Banned from using the Ad Blaster Plugin & Network.</p>
<ol type="1">
    <lh>Ad Blaster Terms of Service/Usage</lh>
    <li>Ads for Adult Sites & Premium Ads Containing Nudity will not be allowed</li>
    <li>Ads containing Foul, Racist, or Sexually provocative language will not be allowed</li>
</ol>
<br />Naturally more will be added as needed
<?php
}

function adb_blastit(){
?>
<h2>Blast your Ads!</h2>
<p>Using this Page you can create your Ads on Ad Blaster. Either Create A Free Text only Ad or You can create Premium Ads using your own Banner images by ordering A Premium Ad via PayPal.</p>
<h2>Why The cost</h2><p>Your helping to cover the operation expenses as well as making a small donation for the plugin itself. So help support the plugin & purchase premium ads.</p>
<p>Order Premium Ad Listings via PayPal. You will be given The Ad Blaster Premium Edition that allows Images & has A full Ad Management System</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="7500789">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<?php
                    require "config.php";
                    $adname = $_POST['adname'];
                    $addescription = $_POST['description'];
                    $ad_url = $_POST['adurl'];
                    $savemail = $_POST['email'];
                    $from_site = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
                    $db_link = mysql_connect($db_host, $db_user, $db_pass) or die('MySQL Connection Error:'.mysql_error());
                    mysql_select_db($db_db) or die('MySQL Error: Cannot select table');

              $sql = "SELECT * FROM ads_list WHERE from_site='$from_site'";
              $result = mysql_query($sql) or die(mysql_error());
             while ($row = mysql_fetch_assoc($result)) {
                $ad_name1 = $row['ad_name'];
                $ad_descript1 = $row['ad_description'];
                $ad_image1 = $row['ad_image'];
                $ad_url1 = $row['ad_url'];
                $email = $row['email'];
               }
              $sql = "SELECT * FROM banned_list WHERE from_site='$from_site'";
              $result = mysql_query($sql) or die(mysql_error());
             while ($row1 = mysql_fetch_assoc($result)) {
                $banned = $row1['banned'];
               }
                   mysql_close($db_link);


                 if ($banned == 1){
                    $db_link = mysql_connect($db_host, $db_user, $db_pass) or die('MySQL Connection Error:'.mysql_error());
                    mysql_select_db($db_db) or die('MySQL Error: Cannot select table');
                    if(mysql_num_rows(mysql_query("SELECT from_site FROM banned_list WHERE from_site = '$from_site'"))){
                    $sql = "DELETE FROM ads_list WHERE from_site='$from_site'";   
                     $result = mysql_query($sql) or die(mysql_error());                            
                     mysql_close($db_link);
                     echo "<script>alert('MESSAGE FROM Ad Blaster Administration : Sorry but your Site has been Banned from the Ad Blaster Network due to Violation of Terms of Service. If you feel this is an Error please Contact the Administrator');</script>";
                         echo "<meta http-equiv='refresh' content='0;url=?page=wordpress-ad-blaster/wp-adblaster.php'>";
                        }
                       }




if (isset($_POST['submit_ad'])){
                  if ($adname && $savemail && $addescription && $ad_url && $from_site){
                   $db_link = mysql_connect($db_host, $db_user, $db_pass) or die('MySQL Connection Error:'.mysql_error());
                   mysql_select_db($db_db) or die('MySQL Error: Cannot select table');

                   if(mysql_num_rows(mysql_query("SELECT from_site FROM ads_list WHERE from_site = '$from_site'"))){
//new update code

                  $sql = "UPDATE ads_list SET ad_name='$adname', email='$savemail', ad_description='$addescription', ad_url='$ad_url', ad_image='$image_path', from_site='$from_site' WHERE from_site='$from_site'";

//end of new update code

                   $result = mysql_query($sql) or die(mysql_error());
                        mysql_close($db_link);
                     echo "<script>alert('Ad Blaster Message: Your Ad has been updated. Please wait for page to Reload');</script>";                         
                     echo "<meta http-equiv='refresh' content='0'>";

                    }elseif (mysql_num_rows(mysql_query("SELECT from_site FROM banned_list WHERE from_site='$from_site'")) && !mysql_num_rows(mysql_query("SELECT from_site FROM ads_list WHERE from_site = '$from_site'"))){

                  $sql = "INSERT INTO ads_list(ad_name, email, ad_description, ad_url, ad_image, from_site)
                  VALUES 
                  ('$adname', '$savemail','$addescription','$ad_url','$image_path','$from_site')";
                   $result = mysql_query($sql) or die(mysql_error());
                        mysql_close($db_link);
                     echo "<script>alert('Ad Blaster Message: Your Ad is being Created. Please wait for page to Reload');</script>"; 
                     echo "<meta http-equiv='refresh' content='0'>";

                       }else{
                  $sql = "INSERT INTO ads_list(ad_name, email, ad_description, ad_url, ad_image, from_site)
                  VALUES 
                  ('$adname', '$savemail','$addescription','$ad_url','$image_path','$from_site')";
                   $result = mysql_query($sql) or die(mysql_error());
                       $sql = "INSERT INTO banned_list(from_site)
                            VALUES 
                            ('$from_site')";
                       $result = mysql_query($sql) or die(mysql_error());
                        mysql_close($db_link);
                     echo "<script>alert('Ad Blaster Message: Your Ad is being Created. Please wait for page to Reload');</script>"; 
                     echo "<meta http-equiv='refresh' content='0'>";
                              }

                            }else{
                     echo "<script>alert('Ad Blaster WARNING: You must complete all sections of the Creation Form to create or modify your Ad');</script>"; 
                                }
                                }


                  if (isset($_POST['remove_ad'])){
                   $db_link = mysql_connect($db_host, $db_user, $db_pass) or die('MySQL Connection Error:'.mysql_error());
                   mysql_select_db($db_db) or die('MySQL Error: Cannot select table');
                   if(mysql_num_rows(mysql_query("SELECT from_site FROM ads_list WHERE from_site = '$from_site'"))){
                    $sql = "DELETE FROM ads_list WHERE from_site='$from_site'";
                     $result = mysql_query($sql) or die(mysql_error());
                        mysql_close($db_link);
                     echo "<script>alert('Ad Blaster Message: Your Ad has been Removed from the Database. Please wait for page to Reload');</script>"; 
                         echo "<meta http-equiv='refresh' content='0'>";
                       }else{
                     echo "<script>alert('Ad Blaster Message: You have not created any Ads yet.');</script>"; 
                         }
                        }
?>
<br /><br />
<h2>Current Ad Preview</h2>
<?php if ($ad_image1){
           echo "<a href=\"$ad_url1\" target=\"_blank\"><img src=\"$ad_image1\" width=\"125\" height=\"125\"></a><br />$ad_name<br />$ad_descript1<br />";
           }else{
           echo "<a href=\"$ad_url1\" target=\"_blank\">$ad_name1</a><br />$ad_descript1<br />";
                 }
?>
<h2>Create/Modify your Free Ad Below</h2>
<table>
<form method="POST">
<tr><td>Your E-Mail Address</td><td><input type="text" name="email" value="<?php echo $email; ?>"></td></tr>
<tr><td>Your Company/Site Name</td><td><input type="text" name="adname" value="<?php echo $ad_name1; ?>"></td></tr>
<tr><td>Describe Your Company/Site </td><td><input type="text" name="description" value="<?php echo $ad_descript1; ?>"></td></tr>
<tr><td>Your Website Address</td><td><input type="text" name="adurl" value="<?php echo $ad_url1; ?>"></td></tr>
</table><input type="submit" name="submit_ad" value="Create/Modify your Ad"> :: <input type="submit" name="remove_ad" value="Delete Ad" onclick="return confirm('Are you sure you want to Permanently Delete this Ad? ')"></form>
<?php
// END OF Blast IT
}

//General Page Function
function adb_main(){
$ppath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
                    require "config.php";
                    $db_link = mysql_connect($db_host, $db_user, $db_pass) or die('MySQL Connection Error:'.mysql_error());
                    mysql_select_db($db_db) or die('MySQL Error: Cannot select table');
              $sql = "SELECT messages FROM messages";
              $result = mysql_query($sql) or die(mysql_error());
             while ($row1 = mysql_fetch_assoc($result)) {
                $messages = $row1['messages'];
               }
                   mysql_close($db_link);
?>

<h2>About Ad Blaster</h2><p>Well I wanted to do Plugin that has never been done before and in a style that has never been done before. So Ad Blaster was born. You now have the ability to run a Mass ad campaign right from your Wordpress Site. The more people that use this Plugin the more your Ads & Their Ads are seen!. So please Move onto creating your Ad & tweaking your Ad Blast Plugin.</p>

<h2>How does Ad Blaster Work</h2><p>Ad Blaster is setup on A Client/Host Style Framework. In order to spread each Ad to a Mass of Wordpress Sites. Each site refreshes the ad directly from the Host Machine. If all goes well this plugin can be used to create A vast network of ads to help advertise everyones Wordpress Sites.</p> 

<h2>How Do I Manage My Ad?</h2><p>You have access to A complete Ad Management System for Maintaining your Ads</p> 

<h2>Help & Support</h2><p>If you find any issues, have comments, need support, etc. Feel free to <a href="mailto:J.Garber@lastnightsdesigns.com?subject=Ad%20Blaster%20Help">E-Mail me</a> for support.</p>

<h2>About The Author</h2><img border="0" alt="" src="<?php echo $ppath; ?>/images/rocktar.jpg" width="125" height="125"><br /><p>Well nothing to really say. I have random ideas like this plugin that float through my head so I sit down & create them. I tend to do this as a hobby & most the time enjoy sitting down and creating programs & plugins etc. Hopefully something ive created has been found useful by someone lol. So far The only Wordpress plugins ive made have been this one & Wordpress EZ Backup. Keep an eye out for more plugins in the future.</p>

<h2>Acknowledgements</h2><p>I would like to Thank our Awesome Web Host who allows me to develop my plugins & provides me with excellent Service. They also offer Free Hosting with full PHP and MySQL Support too. Check em out Guys.</p><br/>
<a href="http://www.northportws.com" target="_blank">NorthPort Web Service<br/><img src="http://www.northportws.com/images/logo.jpg" width="289" height="90"></a>

<h2>Latest Messages from Ad Blaster</h2><p><?php echo $messages ?></p>

<?php
}



//Settings Menu function
function adb_settings(){
?>

<h2>Ad Blaster Style</h2>
<p>This will allow small adjustments to the way the ads look in your sidebar. This may look familiar to some people since its a simple CSS Style. However to those that have never seen CSS. I suggest reading up on it <a href="http://www.w3schools.com/Css/default.asp" target="_blank">HERE</a>. Using CSS you can make your Ad Display blend in perfectly to your current site.</p>

<?php

        $stylepath = dirname(__FILE__);
        $stylesheet = $stylepath.''."/adstyle.css";
        $readsheet = fopen($stylesheet, 'r') or die();
        $thesheet = fread($readsheet, filesize($stylesheet));
        fclose($readsheet);

if (isset($_POST['submit_style'])) {
$newstyle = $_POST['stylesheet'];
$writeit = "$stylesheet";
$fh1 = fopen($writeit, 'w') or die("can't open file");
$stringData = "$newstyle";
fwrite($fh1, $stringData);
fclose($fh1);
echo "<script>alert('Ad Blaster Message: Your Stylesheet has been updated. Please wait for page to Reload');</script>";
echo "<meta http-equiv='refresh' content='0'>";
}
?>



<form method="post">
<textarea name="stylesheet" cols="70" rows="20"><?php echo $thesheet; ?></textarea>
<br /><input type="submit" name="submit_style" value="Save Style Sheet">
</form>



<?php
}






//loads style sheet properly
function adb_style() {

    $style = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    echo '<link rel="stylesheet" type="text/css" href="' . $style . 'adstyle.css" />';

}


//Main Code ad showing etc

function adb_plugin() {

require "config.php";
// Mysql Connection
$db_link = mysql_connect($db_host, $db_user, $db_pass)
	or die('MySQL Connection Error:'.mysql_error());
mysql_select_db($db_db)
	or die('MySQL Error: Cannot select table');

$offset_result = mysql_query( " SELECT FLOOR(RAND() * COUNT(*)) AS 'offset' FROM ads_list ");
$offset_row = mysql_fetch_object( $offset_result );
$offset = $offset_row->offset;
$result = mysql_query( " SELECT * FROM ads_list LIMIT $offset, 1 " ); 



while ($row = mysql_fetch_assoc($result)) {
$ad_name = $row['ad_name'];
$ad_descript = $row['ad_description'];
$ad_image = $row['ad_image'];
$ad_urlb = $row['ad_url'];
}

if(strstr($ad_urlb,'http://')) 
{ 
$ad_url = $ad_urlb;
}else{
$ad_url = "http://".$ad_urlb;
}

mysql_close($db_link);

if ($ad_image){
echo "<br /><div class=\"adbox\"><a href=\"$ad_url\" target=\"_blank\"><img src=\"$ad_image\" width=\"125\" height=\"125\" style=\"border-style: none;\"></a><br />";
echo "<strong>";
echo $ad_name;
echo "</strong><br />";
echo $ad_descript;
echo "</div>";
}else{
echo "<br /><div class=\"adbox\">";
echo "<strong><a href=\"$ad_url\" target=\"_blank\">";
echo $ad_name;
echo "</a></strong><br />";
echo $ad_descript;
echo "</div>";
}



}

//set us up the widget

function widget_adb($args) {
  extract($args);
  echo $before_widget;
?>
<h2 class="adb_title">
Ads by <a href="http://wordpress.org/extend/plugins/wordpress-ad-blaster/" target="_blank">Ad Blaster</a>

<?php 
  echo $after_title;
  adb_plugin();
  echo $after_widget;
}

function adb_widget_init()
{
  register_sidebar_widget(__('Ad Blaster'), 'widget_adb');     
}
add_action("plugins_loaded", "adb_widget_init");

?>