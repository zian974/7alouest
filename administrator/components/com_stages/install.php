<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of install
 *
 * @author zian
 */
    
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;

class com_stagesInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function __construct(InstallerAdapter $adapter)
	{ 
	}
	
	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($route, InstallerAdapter $adapter)
	{
		return true;
	}
	
	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($route, $adapter)
	{
		return true;
	}
	
	/**
	 * Called on installation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(InstallerAdapter $adapter)
	{
        $app= Joomla\CMS\Factory::getApplication();
        $packagePath = $adapter->getParent()->getPath('source');
        $jRootPath = Path::clean(JPATH_ROOT);
        
		$templateName = $app->getTemplate();
        $templateXml = $adapter->getManifest()->administration->template;
		
        $destPath = "$jRootPath/administrator/templates/$templateName";
        
        $origPath = $packagePath ."/". 
                        $templateXml->attributes()->folder ."/".
                        $templateXml->folder;
     
        Folder::copy($origPath, $destPath, "", true);
        
		return true;
	}
	
	/**
	 * Called on update
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(InstallerAdapter $adapter)
	{
		$app= Joomla\CMS\Factory::getApplication();
        $packagePath = $adapter->getParent()->getPath('source');
        $jRootPath = Path::clean(JPATH_ROOT);
        
		$templateName = $app->getTemplate();
        $templateXml = $adapter->getManifest()->administration->template;
		
        $destPath = "$jRootPath/administrator/templates/$templateName";
        
        $origPath = $packagePath ."/". 
                        $templateXml->attributes()->folder ."/".
                        $templateXml->folder;
     
        Folder::copy($origPath, $destPath, "", true);

		return true;
	}
	
	/**
	 * Called on uninstallation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function uninstall(InstallerAdapter $adapter)
	{
		return true;
	}

}

