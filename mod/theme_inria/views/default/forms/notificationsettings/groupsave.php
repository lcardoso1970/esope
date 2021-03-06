<?php
/**
 * Elgg notifications groups subscription form
 *
 * @package ElggNotifications
 *
 * @uses $vars['user'] ElggUser
 * Facyla accessibility patch : changed save submit text (based on 1.8.16)
 */

/* @var ElggUser $user */
$user = $vars['user'];

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	// Iris v2 : skip site (always enabled)
	if ($method == 'site') { continue; }
	$subsbig[$method] = elgg_get_entities_from_relationship(array(
		'relationship' => 'notify' . $method,
		'relationship_guid' => $user->guid,
		'type' => 'group',
		'limit' => false,
	));
	$tmparray = array();
	if ($subsbig[$method]) {
		foreach($subsbig[$method] as $tmpent) {
			$tmparray[] = $tmpent->guid;
		}
	}
	$subsbig[$method] = $tmparray;
}

?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-body">
		<?php echo elgg_view('notifications/subscriptions/jsfuncs',$vars); ?>
		<div>
			<?php echo elgg_echo('notifications:subscriptions:groups:description'); ?>
		</div>
		<?php
		if (isset($vars['groups']) && !empty($vars['groups'])) {
			?>
			<table id="notificationstable" cellspacing="0" cellpadding="4" width="100%">
				<tr>
					<td>&nbsp;</td>
					<?php
					$i = 0; 
					foreach($NOTIFICATION_HANDLERS as $method => $foo) {
						// Iris v2 : skip site (always enabled)
						if ($method == 'site') { continue; }
						if ($i > 0) { echo "<td class='spacercolumn'>&nbsp;</td>"; }
						?>
						<td class="<?php echo $method; ?>togglefield"><?php echo elgg_echo('notification:method:'.$method); ?></td>
						<?php
						$i++;
					}
					?>
					<td>&nbsp;</td>
				</tr>
				<?php
				foreach($vars['groups'] as $group) {
					$fields = '';
					$i = 0;
					foreach($NOTIFICATION_HANDLERS as $method => $foo) {
						// Iris v2 : skip site (always enabled)
						if ($method == 'site') { continue; }
						$checked[$method] = '';
						if (in_array($group->guid,$subsbig[$method])) { $checked[$method] = 'checked="checked"'; }
						// Iris v2 : always force site notification (= disabled here, forced server-side)
						if ($i > 0) { $fields .= "<td class=\"spacercolumn\">&nbsp;</td>"; }
						if ($method == 'site') {
							$fields .= <<< END
								<td class="sitetogglefield">
									<a border="0" id="site{$group->guid}" class="sitetoggleOff">
										<input type="checkbox" name="sitesubscriptions[]" id="sitecheckbox" checked="checked" disabled="disabled" />
									</a>
								</td>
END;
						} else {
							$fields .= <<< END
								<td class="{$method}togglefield">
								<a border="0" id="{$method}{$group->guid}" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}{$group->guid}');">
								<input type="checkbox" name="{$method}subscriptions[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}{$group->guid}');" value="{$group->guid}" {$checked[$method]} /></a></td>
END;
						}
						$i++;
					}
					?>
					<tr>
						<td class="namefield">
							<div>
								<?php echo $group->name; ?>
							</div>
						</td>
						<?php echo $fields; ?>
						<td>&nbsp;</td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
		echo '<div class="elgg-foot mtm">';
			echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));
			echo elgg_view('input/submit', array('value' => elgg_echo('save:groupnotifications')));
		echo '</div>';
		?>
	</div>
</div>

