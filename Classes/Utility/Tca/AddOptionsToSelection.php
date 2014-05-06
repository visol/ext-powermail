<?php
namespace In2code\Powermail\Utility\Tca;

use \In2code\Powermail\Utility\Div;

/**
 * Class AddOptionsToSelection allows to add individual options
 */
class AddOptionsToSelection {

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
		$typoScriptConfiguration = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig(Div::getPidFromBackendPage());
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

}