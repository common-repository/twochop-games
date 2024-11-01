<?php
	if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
		wp_die(__( "You are not allowed to be here", 'twochop' ));
?>
<?php 
	$post_id = intval($_REQUEST['post_id']);
	$cmdAction = '';
	
	if($post_id==0)
		wp_die(__( "You are not allowed to be here", 'twochop' ));

	if($_POST['cmdActionDl']=='Delete Play button')
	{
		if (!wp_verify_nonce($_POST['twochop_plugin_nonce'],'twochop_plugin') ) 
			wp_die(__( "You are not allowed to be here", 'twochop' ));

		$tc_del_idtype = get_post_meta($post_id, 'twochop_public_idtype', true);
		$tc_del_id = get_post_meta($post_id, 'twochop_public_id', true);
		delete_post_meta($post_id, 'twochop_public_idtype');
		delete_post_meta($post_id, 'twochop_public_id');
		delete_post_meta($post_id, 'twochop_public_customdata');
		delete_post_meta($post_id, 'twochop_public_buttonstyle');
		delete_post_meta($post_id, 'twochop_public_dataversion');
		delete_post_meta($post_id, 'twochop_public_originkey');
		$cmdAction = 'delete';
	}

	if($_POST['cmdActionRi']=='Reinsert Play button')
	{
		if (!wp_verify_nonce($_POST['twochop_plugin_nonce'],'twochop_plugin') ) 
			wp_die(__( "You are not allowed to be here", 'twochop' ));

		$cmdAction = 'reinsert';
	}

	function validate_twochop_id($tcid)
	{
		$patternid = "/^[0-9\|]+$/";
		$i7 = preg_match($patternid, $tcid);
		if($i7>=1) {
			return $tcid;
		}else {
			return '0';
		}
	}
	
	if($_POST['cmdActionCp']=='Insert Play button')
	{
		if (!wp_verify_nonce($_POST['twochop_plugin_nonce'],'twochop_plugin') ) 
			wp_die(__( "You are not allowed to be here", 'twochop' ));

		update_post_meta($post_id, 'twochop_public_idtype', 1);
		update_post_meta($post_id, 'twochop_public_id', validate_twochop_id($_POST['twochop_id_copy']));
		update_post_meta($post_id, 'twochop_public_customdata', "");
		update_post_meta($post_id, 'twochop_public_buttonstyle', "");
		update_post_meta($post_id, 'twochop_public_dataversion', "1.45");
		update_post_meta($post_id, 'twochop_public_originkey', "-1");

		$cmdAction = 'copy';
	}
	
	$tc_ispostmeta = false;
	$tc_optype = '';
    if (get_post_meta($post_id, 'twochop_public_idtype', true) && get_post_meta($post_id, 'twochop_public_id', true)){
		if(!get_post_meta($post_id, 'twochop_public_originkey', true)) {
			$tc_optype = 'ed';
			$tc_ispostmeta = true;
			$tc_idtype = get_post_meta($post_id, 'twochop_public_idtype', true);
			$tc_id = get_post_meta($post_id, 'twochop_public_id', true);
			$tc_customdata = get_post_meta($post_id, 'twochop_public_customdata', true);
			$tc_buttonstyle = get_post_meta($post_id, 'twochop_public_buttonstyle', true);
		} elseif(get_post_meta($post_id, 'twochop_public_originkey', true) != "-1") {
			$tc_optype = 'ed';
			$tc_ispostmeta = true;
			$tc_idtype = get_post_meta($post_id, 'twochop_public_idtype', true);
			$tc_id = get_post_meta($post_id, 'twochop_public_id', true);
			$tc_customdata = get_post_meta($post_id, 'twochop_public_customdata', true);
			$tc_buttonstyle = get_post_meta($post_id, 'twochop_public_buttonstyle', true);
		} else {
			$tc_optype = 'cp';
			$tc_ispostmeta = true;
			$tc_idtype = get_post_meta($post_id, 'twochop_public_idtype', true);
			$tc_id = get_post_meta($post_id, 'twochop_public_id', true);
			$tc_customdata = get_post_meta($post_id, 'twochop_public_customdata', true);
			$tc_buttonstyle = get_post_meta($post_id, 'twochop_public_buttonstyle', true);
		}
	}else {
		$tc_optype = 'nw';
		$tc_ispostmeta = false;
		$tc_idtype = '';
		$tc_id = '';
		$tc_customdata = '';
		$tc_buttonstyle = '';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>TwoChop Pre-editor form</title>
	<link rel="Stylesheet" type="text/css" href="<?php echo twochop_public_play_URLPATH?>assets/wpplugin.css" />
	<script language="javascript" type="text/javascript" src="<?php echo twochop_public_play_URLPATH?>assets/wpplugin.js"></script>
	<style type="text/css">
        .tabSel {
            background-image:url('<?php echo twochop_public_play_URLPATH?>assets/tabSel.png');
        }
        
        .tabBhd {
            background-image:url('<?php echo twochop_public_play_URLPATH?>assets/tabBhd.png');
        }
	</style>
</head>
<body onload="preform_load();">
    <div class="pageoutline">
        <div class="pagehead">
            TwoChop
        </div>
		
		<div class="tabOutline">
			<?php if ($tc_optype=='ed') : ?>
				<div class="tabBhd" style="left:20px;">NEW</div>
				<div class="tabSel" style="left:125px;">EDIT</div>
			<?php else : ?>
				<div class="tabSel" style="left:20px;">NEW</div>
				<div class="tabBhd" style="left:125px;">EDIT</div>
			<?php endif; ?>

            <div class="tabPage">
				<div id="dvHint" name="dvHint">&nbsp;</div>
				<div id="dvForm" name="dvForm">
					<form id="twochop_form1" method="post" onsubmit="return twochop_form1_submit();" name="twochop_form1" action="http://manage.twochop.com/ntgrtn/wordpress/gamesEditor.aspx">
                     <?php wp_nonce_field('twochop_plugin', 'twochop_plugin_nonce');?>
							<input type="hidden" id="twochop_idtype" name="twochop_idtype" value="<?php echo esc_attr($tc_idtype); ?>" />
							<input type="hidden" id="twochop_id" name="twochop_id" value="<?php echo esc_attr($tc_id); ?>" />
							<input type="hidden" id="twochop_customdata" name="twochop_customdata" value="<?php echo esc_attr($tc_customdata); ?>" />
							<input type="hidden" id="twochop_buttonstyle" name="twochop_buttonstyle" value="<?php echo esc_attr($tc_buttonstyle); ?>" />
							<input type="hidden" id="twochop_post_id" name="twochop_post_id" value="<?php echo esc_attr($post_id); ?>" />
							<?php
								$tcpost = get_post($post_id);
								$tcauthorname = get_the_author_meta( 'display_name', $tcpost->post_author );
								
								$tcupdatekey = uniqid('tCuK');
								$tcupdatedatetime = getdate();
								update_post_meta($post_id, 'twochop_public_updatekey', $tcupdatekey);
								update_post_meta($post_id, 'twochop_public_updatedatetime', $tcupdatedatetime);;
								
								$tcpublisherkey = get_option('twochop_publicpublisherkey', 'public-1234-5678');
								$tcwpkey =  get_option('twochop_public_wpkey', '');
								$tcreserved1key = get_option('twochop_reserved1key', '');
								
								global $current_user;
								get_currentuserinfo();
							?>
							<input type="hidden" id="twochop_updatekey" name="twochop_updatekey" value="<?php echo esc_attr($tcupdatekey); ?>" />
							<input type="hidden" id="twochop_publicpublisherkey" name="twochop_publicpublisherkey" value="<?php echo esc_attr($tcpublisherkey); ?>" />
							<input type="hidden" id="twochop_wpkey" name="twochop_wpkey" value="<?php echo esc_attr($tcwpkey); ?>" />
							<input type="hidden" id="twochop_wpuser" name="twochop_wpuser" value="<?php echo esc_attr($current_user->user_login); ?>" />
							<input type="hidden" id="twochop_reserved1key" name="twochop_reserved1key" value="<?php echo esc_attr($tcreserved1key); ?>" />
							<input type="hidden" id="twochop_pluginversion" name="twochop_pluginversion" value="public.1.4.5" />
							<input type="hidden" id="twochop_post_guid" name="twochop_post_guid" value="<?php echo esc_attr($tcpost->guid); ?>" />
							
							<input type="hidden" id="twochop_post_author" name="twochop_post_author" value="<?php echo esc_attr($tcauthorname); ?>" />
							<input type="hidden" id="twochop_post_title" name="twochop_post_title" value="" />
							<input type="hidden" id="twochop_post_content" name="twochop_post_content" value="" />
							
							<span style="display:none"><input type="checkbox" id="twochop_ispostinfo" name="twochop_ispostinfo" checked value="true"><span style="font-size:12px;">include post information</span></span><br />
							<?php
							function selfURL() {
								$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
								$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
								$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
								return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
							}
							function strleft($s1, $s2) {
								return substr($s1, 0, strpos($s1, $s2));
							}
							?>
							<input type="hidden" id="twochop_wpurl" name="twochop_wpurl" value="<?php echo esc_attr(selfURL()); ?>" />
							<?php if ($tc_optype=='ed') : ?>
								<input type="hidden" id="twochop_optype" name="twochop_optype" value="ed" />
								<input type="submit" id="cmdActionEd" name="cmdActionEd" value="Edit Play button" style="vertical-align:middle;width:200px;"/><br />.&nbsp;<br />
							<?php elseif ($tc_optype=='nw') : ?>
								<p class="tcInfo" style="margin-bottom:5px;">Create a new game for this post</p>
								<input type="hidden" id="twochop_optype" name="twochop_optype" value="nw" />
								<input type="submit" id="cmdActionNw" name="cmdActionNw" value="New Play button" style="vertical-align:middle;width:200px;"/><br />.&nbsp;<br />
							<?php endif; ?>
					</form>

					<?php if ($tc_optype=='nw') : ?>
						<br />Or<br />&nbsp;<br />&nbsp;<br />
						<form id="twochop_form3" method="post" name="twochop_form3" action="">
                        <?php wp_nonce_field('twochop_plugin', 'twochop_plugin_nonce');?>
							<p class="tcInfo" style="margin-bottom:5px;">Insert an existing game</p>
							PlayID: <input type="text" id="twochop_id_copy" name="twochop_id_copy" maxlength="20" value="" /><br />
							<input type="hidden" id="twochop_optype" name="twochop_optype" value="cp" />
							<input type="submit" id="cmdActionCp" name="cmdActionCp" value="Insert Play button" onclick="return copyPlay();"  style="vertical-align:middle;width:200px;margin-top:5px;"/>
						</form>
					<?php endif; ?>
					
					<?php if (($tc_optype=='ed') || ($tc_optype=='cp')) : ?>
						<form id="twochop_form2" method="post" name="twochop_form2" action="">
                        <?php wp_nonce_field('twochop_plugin', 'twochop_plugin_nonce');?>
							<input type="submit" id="cmdActionRi" name="cmdActionRi" value="Reinsert Play button" onclick="return reinsertPlay();" style="vertical-align:middle;width:200px;display:none;"/><br />
							.&nbsp;<br />
							<input type="submit" id="cmdActionDl" name="cmdActionDl" value="Delete Play button" onclick="return deletePlay();" style="width:200px;" />
						</form>
					<?php endif; ?>
					
				</div><!--dvForm-->
				<script type="text/javascript" language="javascript">

					jtc_optype = '<?php echo esc_js($tc_optype); ?>';
					jtc_cmdAction = '<?php echo esc_js($cmdAction); ?>';
					jtc_idtype = '<?php echo esc_js($tc_idtype); ?>';
					jtc_id = '<?php echo esc_js($tc_id); ?>';
					jtc_del_idtype = '<?php echo esc_js($tc_del_idtype); ?>';
					jtc_del_id = '<?php echo esc_js($tc_del_id); ?>';
					jtc_post_author = '';
					jtc_post_title = '';
					jtc_post_content = '';
					
					function testE()
					{

					}
				</script>
				<div id="dvPlayinfo" name="dvPlayinfo">
				<table cellspacing="0" class="form-table" width="100%">
					<tr>
					<td align="left" width="25%">IDType: <?php echo esc_html($tc_idtype); ?></td>
					<td align="left" width="25%">ID: <?php echo esc_html($tc_id); ?></td>
					<td align="left" width="25%">CustomData: <?php echo esc_html($tc_customdata); ?></td>
					<td align="left" width="25%">ButtonStyle: <?php echo esc_html($tc_buttonstyle); ?></td>
					</tr>
				</table>
				</div><!--dvPlayinfo-->
			</div><!--tabPage-->
		</div><!--tabOutline-->
	</div><!--pageoutline-->
</body>
</html>
