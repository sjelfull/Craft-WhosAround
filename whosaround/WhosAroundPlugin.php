<?php
namespace Craft;

class WhosAroundPlugin extends BasePlugin {

    function getName()
    {
        return Craft::t('Who\'s around?');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Fred Carlsen';
    }

    function getDeveloperUrl()
    {
        return 'http://sjelfull.no';
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('whosaround/_settings', [
           'settings' => $this->getSettings(),
        ]);
    }
    
    protected function defineSettings()
    {
        return [
            'pusherClientKey' => AttributeType::String,
            'pusherClientSecret' => AttributeType::String,
            'pusherAppId' => AttributeType::String,
            'pusherChannelName' => AttributeType::String,
        ];
    }

    public function hasCpSection()
    {
        return true;
    }

}
