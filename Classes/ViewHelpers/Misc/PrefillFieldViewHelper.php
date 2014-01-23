<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Prefill a field with variables
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class PrefillFieldViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $piVars;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $cObj;

	/**
	 * Prefill string for fields
	 *
	 * @param 	object 		$field Field Object
	 * @param 	int 		$cycle Cycle Number (1,2,3...) - if filled it's a checkbox or radiobutton
	 * @return 	string		Prefill field with this string
	 */
	public function render($field, $cycle = 0) {
		// config
		$value = '';
		$marker = $field->getMarker();
		$uid = $field->getUid();

		// Default fieldtypes (input, textarea, hidden, select)
		if ($cycle == 0) {

			// if GET/POST with marker (&tx_powermail_pi1[mail][marker]=value)
			if (isset($this->piVars['field'][$marker])) {
				$value = $this->piVars['field'][$marker];
			}

			// if GET/POST with marker (&tx_powermail_pi1[marker]=value)
			elseif (isset($this->piVars[$marker])) {
				$value = $this->piVars[$marker];
			}

			// if GET/POST with new uid (&tx_powermail_pi1[field][123]=value)
			elseif (isset($this->piVars['field'][$uid])) {
				$value = $this->piVars['field'][$uid];
			}

			// if GET/POST with old uid (&tx_powermail_pi1[uid123]=value (downgrade to powermail < 2)
			elseif (isset($this->piVars['uid' . $uid])) {
				$value = $this->piVars['uid' . $uid];
			}

			// if field should be filled with FE_User values
			elseif ($field->getFeuserValue()) {
				// if fe_user is logged in
				if (intval($GLOBALS['TSFE']->fe_user->user['uid']) !== 0) {
					$value = $GLOBALS['TSFE']->fe_user->user[$field->getFeuserValue()];
				}
			}

			// if prefill value (from flexform)
			elseif ($field->getPrefillValue()) {
				$value = $field->getPrefillValue();
			}

			// if prefill value (from typoscript)
			elseif ($this->settings['prefill.'][$marker]) {
				// Parse cObject
				if (isset($this->settings['prefill.'][$marker . '.']) && is_array($this->settings['prefill.'][$marker . '.'])) {
					// make array from object
					$data =  \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettableProperties($field);
					// push to ts
					$this->cObj->start($data);
					// parse
					$value = $this->cObj->cObjGetSingle($this->settings['prefill.'][$marker], $this->settings['prefill.'][$marker . '.']);
				// Use String only
				} else {
					$value = $this->settings['prefill.'][$marker];
				}

			}

			return $value;



		// Check, Radio
		} else {
			$selected = 0;
			$index = $cycle - 1;
			$options = $field->getModifiedSettings();

			// if GET/POST with marker (&tx_powermail_pi1[field][marker]=value)
			if (isset($this->piVars['field'][$marker])) {
				if ($this->piVars['field'][$marker] == $options[$index]['value'] || $this->piVars['field'][$marker] == $options[$index]['label']) {
					$selected = 1;
				}
			}

			// if GET/POST with marker (&tx_powermail_pi1[marker]=value)
			elseif (isset($this->piVars[$marker])) {
				if ($this->piVars[$marker] == $options[$index]['value'] || $this->piVars[$marker] == $options[$index]['label']) {
					$selected = 1;
				}
			}

			// if GET/POST with new uid (&tx_powermail_pi1[field][123]=value)
			elseif (isset($this->piVars['field'][$uid])) {
				if (is_array($this->piVars['field'][$uid])) {
					foreach ($this->piVars['field'][$uid] as $key => $value) {
						if ($this->piVars['field'][$uid][$key] == $options[$index]['value'] || $this->piVars['field'][$uid][$key] == $options[$index]['label']) {
							$selected = 1;
						}
					}
				} else {
					if ($this->piVars['field'][$uid] == $options[$index]['value'] || $this->piVars['field'][$uid] == $options[$index]['label']) {
						$selected = 1;
					}
				}
			}

			// if GET/POST with old uid (&tx_powermail_pi1[uid123]=value (downgrade to powermail < 2)
			elseif (isset($this->piVars['uid' . $uid])) {
				if ($this->piVars['uid' . $uid] == $options[$index]['value'] || $this->piVars['uid' . $uid] == $options[$index]['label']) {
					$selected = 1;
				}
			}

			// if field should be filled with FE_User values
			elseif ($field->getFeuserValue() && intval($GLOBALS['TSFE']->fe_user->user['uid']) !== 0) {
				// if fe_user is logged in
				if ($GLOBALS['TSFE']->fe_user->user[$field->getFeuserValue()] == $options[$index]['value'] || $GLOBALS['TSFE']->fe_user->user[$field->getFeuserValue()] == $options[$index]['label']) {
					$selected = 1;
				}
			}

			// if prefill value (from flexform)
			elseif ($options[$index]['selected']) {
				$selected = 1;
			}

			// if prefill value (from typoscript)
			elseif ($this->settings['prefill.'][$marker]) {
				// Parse cObject
				if (isset($this->settings['prefill.'][$marker . '.']) && is_array($this->settings['prefill.'][$marker . '.'])) {
					// make array from object
					$data =  \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettableProperties($field);
					// push to ts
					$this->cObj->start($data);
					if (
						$this->cObj->cObjGetSingle($this->settings['prefill.'][$marker], $this->settings['prefill.'][$marker . '.']) == $options[$index]['value'] ||
						$this->cObj->cObjGetSingle($this->settings['prefill.'][$marker], $this->settings['prefill.'][$marker . '.']) == $options[$index]['label']
					) {
						$selected = 1;
					}
				// Use String only
				} else {
					if ($this->settings['prefill.'][$marker] == $options[$index]['value'] || $this->settings['prefill.'][$marker] == $options[$index]['label']) {
						$selected = 1;
					}
				}

			}

			return $selected;
		}

	}

	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->cObj = $this->configurationManager->getContentObject();
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = $typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.'];
	}

	/**
	 * Object initialization
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->piVars = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_powermail_pi1');
	}
}