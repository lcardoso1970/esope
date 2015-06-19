<?php

/**
 * Upload users language strings
 * English
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
$english = array(
	/**
	 * Admin Interface
	 */
	'admin:users:upload' => 'CSV User Import',

	'upload_users:error:cannot_open_file' => 'The file you are uploading does not seem to be a valid CSV, or the parsing settings you have defined are not correct',
	
	'upload_users:incomplete' => 'Incomplete imports',
	'upload_users:upload' => 'Upload a new file',
	'upload_users:instructions' => 'Instructions',
	'upload_users:mapping' => 'Map CSV headers to profile fields',
	'upload_users:attributes' => 'Auto-generate required attributes',
	'upload_users:report' => 'Import Completed',

	'upload_users:continue' => 'Continue',
	'upload_users:back' => 'Back',
	'upload_users:delete' => 'Delete File',

	'upload_users:choose_file' => 'Choose file',

	'upload_users:encoding' => 'CSV File Encoding',

	'upload_users:delimiter' => 'Field delimiter',
	'upload_users:delimiter:comma' => 'comma (&#44;)',
	'upload_users:delimiter:semicolon' => 'semicolon (&#59;)',
	'upload_users:delimiter:colon' => 'colon (&#58;)',

	'upload_users:enclosure' => 'Field enclosure',
	'upload_users:enclosure:doublequote' => 'double quotation mark (&#34;)',
	'upload_users:enclosure:singlequote' => 'single quotation mark (&#39;)',

	'upload_users:mapping_template' => 'Select an existing header mapping template',
	'upload_users:new_template' => 'New template',

	'upload_users:save_as_template' => 'Enter a new name to save header mapping as a template',
	'upload_users:yes' => 'Yes',
	'upload_users:no' => 'No',

	'upload_users:setting:notification' => 'Notify users by email when their new account is created',
	'upload_users:setting:update_existing_users' => 'Update profile information when user account already exists',
	'upload_users:setting:fix_usernames' => 'Fix usernames if the value does not meet Elgg standards (e.g. contains special characters), and suffix them with a number if already taken',
	'upload_users:setting:fix_passwords' => 'Generate a new cleartext passwords, if the value does not meet Elgg standards (e.g. is too short)',

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
	'upload_users:error:file_open_error' => 'Error opening file',
	'upload_users:error:wrong_csv_format' => 'CSV file in wrong format',
	/**
	 * Email Notifications
	 */
	'upload_users:email:message' => 'Hello %s!

		A user account has been created for you for %s. Use your username and password to login.

		Username: %s
		Password: %s

		Go to address %s to login.

	',
	'upload_users:email:subject' => 'Your user account for %s',
	/**
	 * Miscellaneous
	 */
	'upload_users:random_cleartext_passowrd' => 'Random cleartext password',
	'upload_users:mapping:instructions' => 'Specify how should each header from CSV file be mapped to the user metadata and/or profile fields; the dropdowns contain a list of user attributes and metadata, as well profile manager fields. You can as well select a custom metadata name.',
	'upload_users:mapping:instructions_required' => 'Attributes listed below are required for creating user accounts and were not mapped to a CSV header. Please specify the CSV headers, which will be used to denote these fields (i.e. email) or components, which will be used to auto-generate the value (i.e. username and name)',
	'upload_users:mapping:csv_header' => 'CSV Header',
	'upload_users:mapping:elgg_header' => 'Corresponding profile field or metadata name',
	'upload_users:mapping:access_id' => 'Access level',
	'upload_users:mapping:value_type' => 'Value type',
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

	'upload_users:download_sample_help' => 'Download a sample CSV file with headers that represent all user metadata currently stored on your site',
	'upload_users:download_sample' => 'Download',

	'upload_users:status:mapping_pending' => '[Header Mapping Pending]',
	'upload_users:status:ready_for_import' => '[Ready For Import]',
	'upload_users:status:imported' => '[Imported]',

	'upload_users:continue:map' => 'Map Headers',
	'upload_users:continue:import' => 'Import',
	'upload_users:continue:import:warning' => 'Large CSV files may take longer time to import. Please do not refresh the page after confirming the import.',
	'upload_users:continue:view_report' => 'View Report',
	'upload_users:continue:download_report' => 'Download Report',

	'upload_users:error:userexists' => 'User account with this email or username already exists',
	'upload_users:error:empty_name' => 'Display name can not be empty',
	'upload_users:error:invalid_guid' => 'There are no user accounts associated with this GUID',
	'upload_users:error:update_email_username_mismatch' => 'User account can not be updated due to username and email mistmatch',
	
	

);

add_translation('en', $english);