<?php
namespace Craft;

class WhosAroundVariable
{
    public function setup()
    {
        $channelName = craft()->whosAround->getChannelName();
        $apiKey = craft()->whosAround->getPublicKey();

        $oldPath = craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'whosaround/templates';

        // Set path for this request
        craft()->path->setTemplatesPath($newPath);

        $html = craft()->templates->render('_js', [
            'apiKey' => $apiKey,
            'channelName' => $channelName,
        ]);

        craft()->path->setTemplatesPath($oldPath);

        return TemplateHelper::getRaw($html);
    }

    public function getPublicKey()
    {
        $apiKey = craft()->whosAround->getPublicKey();
        return $apiKey;
    }

    public function getChannelName()
    {
        $channelName = craft()->whosAround->getChannelName();
        return $channelName;
    }

    public function setupTemplatePath()
    {
        // Remember paths
        $oldPath = craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'whosaround/templates';

        // Set path for this request
        craft()->path->setTemplatesPath($newPath);

        $html = craft()->templates->render('path/to/template');
        craft()->path->setTemplatesPath($oldPath);
    }
}
