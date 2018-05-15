<?php
/** RA - Version 0.1 **/


class RA_TableModel extends RA_TableQuery {
	protected $pageObj = null;
	protected $queryObj = null;

	public function __construct($obj = null) {
		global $ra;

		$this->pageObj = $obj;
		$numrecords = $ra->get_config("NumRecordstoDisplay");
		$this->setRecordLimit($numrecords);
		return $this;
	}

	public function _execute($implementationData = null) {
	}

	public function setPageObj($pageObj) {
		$this->pageObj = $pageObj;
	}

	public function getPageObj() {
		return $this->pageObj;
	}

	public function execute($implementationData = null) {
		$results = $this->_execute($implementationData);
		$this->getPageObj()->setData($results);
		return $this;
	}
}

?>