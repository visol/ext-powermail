<?php

/**
 * Class FlexFormFieldSelection allows to add individual options
 */
class FlexFormFieldSelection {

	/**
	 * Extension prefix
	 *
	 * @var string
	 */
	protected $extension = 'tx_powermail';

	/**
	 * Add options to FlexForm Selection - Options can be defined in TSConfig
	 * 		Use page tsconfig in this way:
	 * 			tx_powermail.flexForm.type.addFieldOptions.newfield = New Field Name
	 * 			tx_powermail.flexForm.validation.addFieldOptions.newfield = New Validation
	 * 			tx_powermail.flexForm.feUserProperty.addFieldOptions.newfield = New fe_user
	 *
	 * @param $params
	 * @param $pObj
	 * @return void
	 */
	public function addOptions(&$params, &$pObj) {
		$typoScriptConfiguration = t3lib_BEfunc::getPagesTSconfig($this->getPid());
		$extensionConfiguration = $typoScriptConfiguration[$this->extension . '.']['flexForm.'];

		if (!empty($extensionConfiguration[$params['config']['itemsProcFuncFieldName'] . '.']['addFieldOptions.'])) {
			$options = $extensionConfiguration[$params['config']['itemsProcFuncFieldName'] . '.']['addFieldOptions.'];
			foreach ((array) $options as $value => $label) {
				$params['items'][] = array(
					$label,
					$value
				);
			}
		}
	}

	/**
	 * Read pid from current URL
	 * 		URL example:
	 * 		http://powermailt361.in2code.de/typo3/alt_doc.php?
	 * 		&returnUrl=%2Ftypo3%2Fsysext%2Fcms%2Flayout%2Fdb_layout.php%3Fid%3D17%23
	 * 		element-tt_content-14&edit[tt_content][14]=edit
	 *
	 * @return int
	 */
	protected function getPid() {
		$pid = 0;
		$backUrl = str_replace('?', '&', t3lib_div::_GP('returnUrl'));
		$urlParts = t3lib_div::trimExplode('&', $backUrl, 1);
		foreach ($urlParts as $part) {
			if (stristr($part, 'id=')) {
				$pid = str_replace('id=', '', $part);
			}
		}

		return intval($pid);
	}

}