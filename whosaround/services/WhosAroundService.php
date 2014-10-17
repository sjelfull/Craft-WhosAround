<?php
namespace Craft;

require_once(CRAFT_PLUGINS_PATH . '/whosaround/vendor/autoload.php');

class WhosAroundService extends BaseApplicationComponent {

    protected $settings = array();
    protected $pusher;

    function __construct()
    {
        $plugin = craft()->plugins->getPlugin('whosaround');
        if ( ! $plugin)
        {
            throw new Exception('Couldn’t find the whosaround plugin!');
        }
        $this->settings = $plugin->getSettings();

        $this->pusher = new \Pusher($this->settings->pusherClientKey, $this->settings->pusherClientSecret, $this->settings->pusherAppId, true);
    }

    public function authenticatePresence($socketId)
    {
        // Check if user is logged in
        $user = craft()->userSession->getUser();
        $data = array();
        // Should be registered in session instead of generated every time
        $userId = StringHelper::randomString(12);

        // Set ip
        $data['ip'] = craft()->request->getIpAddress();

        if ($user)
        {
            $userId = $user->id;
            $data['username'] = $user->username;

            if ($user->firstName or $user->lastName)
            {
                $data['name'] = sprintf('%s %s', $user->firstName, $user->lastName);
            }

        }

        return $this->pusher->presence_auth($this->settings->pusherChannelName,
            $socketId, // Socket id
            $userId, 
            $data
            );
    }

    public function getChannelName()
    {
        return $this->settings->pusherChannelName;
    }

    public function getPublicKey()
    {
        return $this->settings->pusherClientKey;
    }
}

//