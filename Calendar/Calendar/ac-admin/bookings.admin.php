<?php
//	get list of calendar items
$sql="SELECT b.id, b.desc_".AC_LANG." as the_item FROM ".T_BOOKINGS_ITEMS." AS b WHERE b.state=1 ".$sql_condition." ORDER BY b.list_order";
$res=mysqli_query($db_cal,$sql) or die("Error checking items<br>".mysqli_Error($db_cal));
if(mysqli_num_rows($res)==0){
	//	no items in db
	$warning=$lang["warning_no_active_items"];
}else{
	while($row=mysqli_fetch_assoc($res)){
		//	create an array of items to be able to confirm that the calendar
		$user_items[]=$row["id"];
		$list_items.='<option value="'.$row["id"].'"';
		if($row["id"]==$_REQUEST["id_item"]) $list_items.=' selected="selected"';
		$list_items.='>'.$row["the_item"].'</option>';
	}
	//print_r($user_items);
	if( ($_REQUEST["id_item"]) && (!in_array($_REQUEST["id_item"],$user_items)) ){
		$warning.="item doesn't exist";
	}else{
		if(!isset($_REQUEST["id_item"])) $_REQUEST["id_item"]=$user_items[0];	# get first item in array of user items
		$the_file=AC_INLCUDES_ROOT."cal.inc.php";
		if(!file_exists($the_file)) die("<b>".$the_file."</b> not found");
		else		require_once($the_file);

		$xtra_js_files.='<script type="text/javascript" src="js/mootools-cal-admin.js"></script>
		';

		//	define js vars for calendar
		$xtra_js.="
		var date_hover 			= true;	//	true=on, false=off
		var show_message 		= true; //	true=on, false=off

		var url_ajax_cal 		= '".AC_DIR_AJAX."calendar.ajax.php'; 	//	ajax file for loading calendar via ajax
		var url_ajax_update 	= '".AC_DIR_AJAX."update_calendar.ajax.php'; //	ajax file for update calendar state
		var img_loading_day		= '".AC_DIR_IMAGES."ajax-loader-day.gif';	//	animated gif for loading
		var img_loading_month	= '".AC_DIR_IMAGES."ajax-loader-month.gif';//	animated gif for loading

		//	don't change these values
		var lang			=	'".AC_LANG."';	//	language
		var id_item			=	'".$_REQUEST["id_item"]."';	//	id of item to be modified (via ajax)
		var months_to_show	=	".AC_NUM_MONTHS.";	//	number of months to show
		var clickable_past	=	'".AC_ACTIVE_PAST_DATES."';
		";

		//	get calendar items
		$db_items	= '';
		$array_items= '';


		$contents.='
		<form>
		<table>
			<tr>
				<input type="hidden" name="page" value="'.ADMIN_PAGE.'">
				<td class="side" style="width:100px;">'.$lang["item_to_show"].'</td>
				<td>
					<select name="id_item" class="select" onchange="this.form.submit();">
						'.$list_items.'
					</select>
					<input type="submit" value="'.$lang["bt_change_item"].'" style="">
				</td>
				<td>&nbsp;&nbsp;</td>
				<td><input type="button" value="'.$lang["bt_add_item"].'" style="" onclick="document.location.href=\'index.php?page=items&action=new\'"></td>
			</tr>
			<tr>
				<td class="side">'.$lang["click_method"].':</td>
				<td>
					<select id="id_predefined_state"  class="select" >
						<option value="">'.$lang["states_method_click_through"].'</option>
						'.$sel_list_states.'
						<option value="free">'.$lang["available"].'</option>
					</select>
				</td>
			</tr>
		</table>
		</form>
		<div id="cal_wrapper">

			<div id="cal_controls">
				<div id="cal_prev" title="'.$lang["prev_X_months"].'"><img src="'.AC_DIR_IMAGES.'icon_prev.gif" class="cal_button"></div>
				<div id="cal_next" title="'.$lang["next_X_months"].'"><img src="'.AC_DIR_IMAGES.'icon_next.gif" class="cal_button"></div>
				<form method="post">
				<select name="Market" id="Market" class="select" onchange="Setpage(this)">
				<option value="ntx">NTX</option>
				<option value="stx" >STX</option>
				<option value="arok" >AROK</option>
				<option value="gmr" >GMR</option>
				<option value="np" >NP</option>
				<option value="ilwi" >ILWI</option>
				<option value="upny" >UPNY</option>
				<option value="ne" >NE</option>
				<option value="epasnjde" >EPASNJDE</option>
				<option value="miin" >MIIN</option>
				<option value="nynnj" >NYNNJ</option>
				<option value="ohwpa" >OHWPA</option>
				<option value="waba" >WABA</option>
				<option value="tnky" >TNKY</option>
				<option value="florida" >FLORIDA</option>
				<option value="almsla" >ALMSLA</option>
				<option value="pr" >PR</option>
				<option value="gasc" >GASC</option>
				<option value="vawvnc" >VAWVNC</option>
				<option value="sdlv" >SDLV</option>
				<option value="rmr" >RMR</option>
				<option value="pnw" >PNW</option>
				<option value="losangeles" >LOSANGELES</option>
				<option value="hi" >HI</option>
				<option value="aznm" >AZNM</option>
				<option value="ncal" >NCAL</option>
				<option value="pool_ntx" >POOL_NTX</option>
				<option value="pool_stx" >POOL_STX</option>
				<option value="pool_arok" >POOL_AROK</option>
				<option value="pool_gmr" >POOL_GMR</option>
				<option value="pool_np" >POOL_NP</option>
				<option value="pool_ilwi" >POOL_ILWI</option>
				<option value="pool_upny" >POOL_UPNY</option>
				<option value="pool_ne" >POOL_NE</option>
				<option value="pool_epasnjde" >POOL_EPASNJDE</option>
				<option value="pool_miin" >POOL_MIIN</option>
				<option value="pool_nynnj" >POOL_NYNNJ</option>
				<option value="pool_ohwpa" >POOL_OHWPA</option>
				<option value="pool_waba" >POOL_WABA</option>
				<option value="pool_tnky" >POOL_TNKY</option>
				<option value="pool_florida" >POOL_FLORIDA</option>
				<option value="pool_almsla" >POOL_ALMSLA</option>
				<option value="pool_pr" >POOL_PR</option>
				<option value="pool_gasc" >POOL_GASC</option>
				<option value="pool_vawvnc" >POOL_VAWVNC</option>
				<option value="pool_sdlv" >POOL_SDLV</option>
				<option value="pool_rmr" >POOL_RMR</option>
				<option value="pool_pnw" >POOL_PNW</option>
				<option value="pool_losangeles" >POOL_LOSANGELES</option>
				<option value="pool_hi" >POOL_HI</option>
				<option value="pool_aznm" >POOL_AZNM</option>
				<option value="pool_ncal" >POOL_NCAL</option>
				</select>

				<input type="hidden" name="page" value="'.ADMIN_PAGE.'">
				<select name="id_item" id = "id_item" class="select" onchange="this.form.submit();">
				<script>
					$("#Market").change(function() {
  				$("#id_item").load("get_item_userconfig("user_config_calendar", $_POST["Market"]."%")?choice=" + $("#Market").val());
					});
				</script>
				</select>
				<select name="lang" class="select" onchange="this.form.submit();">
						<option value="en" selected="selected">ww1020</option>
					</select>
				</form>
				<div id="ajax_message">'.$lang["inst_calendar_click"].'</div>
				<div class="clear"></div>
			</div>
			<div id="the_months">
				'.$calendar_months.'
			</div>
			<div id="key_wrapper">
				'.$calendar_states.'
			</div>
		</div>
		';
	}
}
?>
