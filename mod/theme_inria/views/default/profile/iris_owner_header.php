<?php
/**
 * Iris v2 profile header
 */

$user = elgg_get_page_owner_entity();
$own = elgg_get_logged_in_user_entity();

$profile_type = esope_get_user_profile_type($user);
if (empty($profile_type)) { $profile_type = 'external'; }
// Archive : replace profile type by member status archived
if ($user->memberstatus == 'closed') { $profile_type = 'archive'; }

echo '<a href="' . $user->getURL() . '" title="' . elgg_echo('theme_inria:profile:back') . '">';
	?>
	<div class="iris-profile-icon <?php if (in_array($profile_type, ['external', 'archive'])) { echo 'profile-type-' . $profile_type; } ?>" style="background-image:url('<?php echo $user->getIconUrl(array('size' => 'large')); ?>');" />
	</div>

	<div class="iris-profile-title">
		<h2>
			<?php
			echo $user->name;
			// Add profile type badge, if defined
			if (!empty($profile_type)) { echo '<span class="iris-badge"><span class="iris-badge-' . $profile_type . '" title="' . elgg_echo('profile:types:'.$profile_type.':description') . '">' . elgg_echo('profile:types:'.$profile_type) . '</span></span>'; }
			?>
		</h2>
		<?php echo strip_tags($user->briefdescription); ?>
	</div>
</a>

