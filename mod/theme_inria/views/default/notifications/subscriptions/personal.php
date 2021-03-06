<?php
/**
 * @uses $vars['user'] ElggUser
* 
 * ESOPE: accessibility patch - add 1 title in link (based on 1.8.16)
 */

// Iris v2 : force site notificaitons

/* @var ElggUser $user */
$user = $vars['user'];

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

?>
<div class="notification_personal">
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('notifications:subscriptions:personal:title'); ?>
		</h3>
	</div>
</div>
<table id="notificationstable" cellspacing="0" cellpadding="4" width="100%">
	<tr>
		<td>&nbsp;</td>
		<?php
		$i = 0; 
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			// Iris v2 : force site notifications
			if ($method == 'site') { continue; }
			if ($i > 0) {
				echo "<td class='spacercolumn'>&nbsp;</td>";
			}
			?>
			<td class="<?php echo $method; ?>togglefield"><?php echo elgg_echo('notification:method:'.$method); ?></td>
			<?php
			$i++;
		}
		?>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="namefield">
			<p>
				<?php echo elgg_echo('notifications:subscriptions:personal:description') ?>
			</p>
		</td>

<?php

$fields = '';
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	// Iris v2 : force site notifications
	if ($method == 'site') { continue; }
	if ($notification_settings = get_user_notification_settings($user->guid)) {
		$personalchecked[$method] = '';
		if (isset($notification_settings->$method) && $notification_settings->$method) {
			$personalchecked[$method] = 'checked="checked"';
		}
	}
	if ($i > 0) {
		$fields .= "<td class='spacercolumn'>&nbsp;</td>";
	}
	// Iris v2 : force site notifications
	if ($method == 'site') {
		$fields .= <<< END
			<td class="sitetogglefield">
			<a  border="0" id="sitepersonal" class="sitetoggleOff" title="site">
			<input type="checkbox" name="sitepersonal" id="sitecheckbox" value="1" checked="checked" disabled="disabled" /></a></td>
END;
	} else {
		$fields .= <<< END
			<td class="{$method}togglefield">
			<a  border="0" id="{$method}personal" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}personal');" title="{$method}">
			<input type="checkbox" name="{$method}personal" id="{$method}checkbox" onclick="adjust{$method}('{$method}personal');" value="1" {$personalchecked[$method]} /></a></td>
END;
	}
	$i++;
}
echo $fields;

?>

		<td>&nbsp;</td>
	</tr>
</table>
</div>
