<?php
namespace Craft;

/**
 * Guest Entries controller
 */
class WhosAroundController extends BaseController
{
	/**
	 * @var Allows anonymous access to this controller's actions.
	 * @access protected
	 */
	protected $allowAnonymous = true;

    /**
     * Takes a power nap.
     */
    public function actionAuthenticate()
    {
        // Get settings
        $settings = $this->getSettings();
        $this->requirePostRequest();
        $socketId = craft()->request->getRequiredPost('socket_id');

		if ($authenticated = craft()->whosAround->authenticatePresence($socketId))
		{
			echo $authenticated;
            craft()->end();
		}

		$this->returnJson(array(
			'success' => true,
		));

        craft()->end();
    }

	/**
	 * Returns an 'error' response.
	 *
	 * @param $entry
	 */
	private function _returnError($entry)
	{
		if (craft()->request->isAjaxRequest())
		{
			$this->returnJson(array(
				'errors' => $entry->getErrors(),
			));
		}
		else
		{
			craft()->userSession->setError(Craft::t('Couldn’t save entry.'));

			// Send the entry back to the template
			craft()->urlManager->setRouteVariables(array(
				'entry' => $entry
			));
		}
	}

    public function getSettings()
    {
        $plugin = craft()->plugins->getPlugin('whosaround');

        return $plugin->getSettings();
    }

}
