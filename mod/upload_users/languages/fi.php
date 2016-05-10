<?php

/**
 * Upload users language strings
 * Finnish
 *
 * @package upload_users
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jaakko Naakka / Mediamaisteri Group
 * @author Ismayil Khayredinov / Arck Interactive
 * @copyright Mediamaisteri Group 2009
 * @copyright ArckInteractive 2013
 * @link http://www.mediamaisteri.com/
 * @link http://arckinteractive.com/
 */
return array(
	/**
	 * Admin Interface
	 */
	'admin:users:upload' => 'Käyttäjien tuonti',

	'upload_users:error:cannot_open_file' => 'Lataamasi tiedosto ei ole CSV-tiedosto, tai valitsemasi asetukset ovat väärin',

	'upload_users:incomplete' => 'Keskeneräiset tuonnit',
	'upload_users:upload' => 'Lataa uusi CSV-tiedosto',
	'upload_users:instructions' => 'Ohjeet',
	'upload_users:mapping' => 'Määritä tuotavat kentät',
	'upload_users:attributes' => 'Auto-generate required attributes',
	'upload_users:report' => 'Raportti',

	'upload_users:continue' => 'Jatka',
	'upload_users:back' => 'Takaisin',
	'upload_users:delete' => 'Poista tiedosto',

	'upload_users:choose_file' => 'Valitse tiedosto',

	'upload_users:encoding' => 'CSV-tiedoston merkitökoodaus',

	'upload_users:delimiter' => 'Kenttien erotin',
	'upload_users:delimiter:comma' => 'pilkku (&#44;)',
	'upload_users:delimiter:semicolon' => 'puolipiste (&#59;)',
	'upload_users:delimiter:colon' => 'kaksoispiste (&#58;)',

	'upload_users:enclosure' => 'Arvojen ympyröimiseen käytettävä merkki',
	'upload_users:enclosure:doublequote' => 'Lainausmerkki (&#34;)',
	'upload_users:enclosure:singlequote' => 'Heittomerkki (&#39;)',

	'upload_users:mapping_template' => 'Käytä olemassa olevaa kenttäpohjaa',
	'upload_users:new_template' => 'Uusi pohja',

	'upload_users:save_as_template' => 'Syötä kenttäpohjalle nimi, mikäli haluat tallentaa sen',
	'upload_users:yes' => 'Kyllä',
	'upload_users:no' => 'Ei',

	'upload_users:setting:notification' => 'Ilmoita henkilöille sähköpostitse tilin luomisesta',
	'upload_users:setting:update_existing_users' => 'Päivitä käyttäjäprofiilin tiedot, jos käyttäjätili on jo olemassa',
	'upload_users:setting:fix_usernames' => 'Korjaa käyttäjätunnukset, jos niissä on kiellettyjä merkkejä. Lisää numero tunnuksen eteen, jos käyttätunnus on jo olemassa.',
	'upload_users:setting:fix_passwords' => 'Luo uusi salasana, jos annettu salasana on virheellinen (esim. liian lyhyt)',

	'upload_users:create_users' => 'Create user accounts',
	'upload_users:success' => 'User created succesfully',
	'upload_users:statusok' => 'User can be created',
	'upload_users:creation_report' => 'Created users',
	'upload_users:process_report' => 'Preview of Created Users',
	'upload_users:no_created_users' => 'No created users.',
	'upload_users:number_of_accounts' => 'Total number of accounts',
	'upload_users:number_of_errors' => 'Number of errors',
	'upload_users:submit' => 'Submit',
	'upload_users:upload_help' => '
		<p>It is best to include all of the following columns into your CSV:
		<dl>
			<dt><b>email</b></dt>
			<dd>- this field is <b>required</b></dd>
			<dt><b>username</b></dt>
			<dd>- this field is optional, but strongly suggested</dd>
			<dd>- if omitted, you will be requested to select a set of fields from your file to auto-generate usernames (e.g. based on email)</dd>
			<dt><b>name</b></dt>
			<dd>- this field is optional, but strongly suggested</dd>
			<dd>- if omitted, you will be requested to select a set of fields from your file to auto-generate names (e.g. based on a concatination of first name and last name)</dd>
			<dt><b>password</b></dt>
			<dd>- this field is optional</dd>
			<dd>- if omitted, new cleartext password will be autogenerated</dd>
		</dl>
		</p>

		<p>For best import results (not required, but suggested) use the following configuration when creating your CSV file:
		<dl>
			<dt>Delimiter</dt>
			<dd>- comma (&#44;)</dd>
			<dt>Enclosure</dt>
			<dd>- double quotation mark (&#34;)</dd>
			<dt>Character Encoding</dt>
			<dd>- UTF-8</dd>
			<dt>Headers</dt>
			<dd>- first row of your CSV file should include headers (which you can map to profile manager fields or custom metadata names in the next step)</dd>
			<dd>- user lowercase letters</dd>
			<dd>- exclude spaces and special characters</dd>
		</dl>
		</p>


		<p>Here are some examples of CSV files:</p>',
	/**
	 * Error Messages
	 */
	'upload_users:error:file_open_error' => 'Tiedoston avaaminen epäonnistui',
	'upload_users:error:wrong_csv_format' => 'CSV-tiedosto on väärässä formaatissa',
	/**
	 * Email Notifications
	 */
	'upload_users:email:message' => 'Hei %s!

		Sinulle on luotu käyttäjätunnus palveluun %s.

		Tunnus: %s
		Salasana: %s

		Kirjautuaksesi mene osoitteeseen:
		%s

		%s
	',
	'upload_users:email:subject' => '%s - Käyttäjätunnuksesi',
	/**
	 * Miscellaneous
	 */
	'upload_users:random_cleartext_passowrd' => 'Random cleartext password',
	'upload_users:mapping:instructions' => 'Määritä, mihin profiilikenttään kukin CSV-tiedoston kenttä tuodaan.',
	'upload_users:mapping:instructions_required' => 'Attributes listed below are required for creating user accounts and were not mapped to a CSV header. Please specify the CSV headers, which will be used to denote these fields (i.e. email) or components, which will be used to auto-generate the value (i.e. username and name)',
	'upload_users:mapping:csv_header' => 'CSV-kentän nimi',
	'upload_users:mapping:elgg_header' => 'Profiilikentän nimi',
	'upload_users:mapping:access_id' => 'Lukuoikeus',
	'upload_users:mapping:value_type' => 'Arvon tyyppi',
	'upload_users:mapping:value_type:text' => 'Keep as text',
	'upload_users:mapping:value_type:tags' => 'Convert to tags',
	'upload_users:mapping:value_type:timestamp' => 'Convert to timestamp',

	'upload_users:mapping:attribute' => 'Attribute',
	'upload_users:mapping:components' => 'Components',
	'upload_users:mapping:select' => 'select ...',
	'upload_users:mapping:custom' => 'custom ...',
	'upload_users:mapping:guid' => 'GUID (only for updates)',
	'upload_users:mapping:username' => 'username',
	'upload_users:mapping:name' => 'name',
	'upload_users:mapping:email' => 'email',
	'upload_users:mapping:password' => 'password',
	'upload_users:mapping:user_upload_role' => 'role name',

	'upload_users:download_sample_help' => 'Lataa esimerkkitiedosto, joka vastaa järjestelmästä jo löytyviä kenttiä ja tietoja',
	'upload_users:download_sample' => 'Lataa',

	'upload_users:status:mapping_pending' => '[Kenttien määritys puuttuu]',
	'upload_users:status:ready_for_import' => '[Valmis tuotavaksi]',
	'upload_users:status:imported' => '[Tuotu]',

	'upload_users:continue:map' => 'Määritä kentät',
	'upload_users:continue:import' => 'Tuo',
	'upload_users:continue:import:warning' => 'Suurien CSV-tiedostojen tuomisessa saattaa kestää kauan. Älä päivitä sivua tai poistu siltä ennen kuin tuonti on valmis.',
	'upload_users:continue:view_report' => 'Näytä raportti',
	'upload_users:continue:download_report' => 'Lataa raportti',

	'upload_users:error:userexists' => 'Tämä tunnus tai sähköpostiosoite on jo käytössä',
	'upload_users:error:empty_name' => 'Nimi ei voi olla tyhjä',
	'upload_users:error:invalid_guid' => 'Järjestelmästä ei löytynyt tiliä, jolla on tämä GUID',
	'upload_users:error:update_email_username_mismatch' => 'Tiliä ei voida päivittää, koska tunnus ja sähköposti eivät täsmää',
);
