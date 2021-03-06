<?php
/**
 * Class that represents an object of subtype directory
 */
class ElggDirectory extends ElggObject {
	const SUBTYPE = "directory";

	private $persons = array();
	private $organisations = array();

	/** Set subtype */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = $this::SUBTYPE;
	}
	
	/* Get Contacts */
	protected function getContacts() {
		return $this->contacts;
	}
	
	/* Get Organisations */
	protected function getOrganisations() {
		return $this->organisations;
	}
	
	
	
}

