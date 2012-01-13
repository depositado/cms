<?php

/**
 *
 */
class PathService extends CApplicationComponent
{
	/* Paths */

	/**
	 * @return string
	 */
	public function getBasePath()
	{
		return $this->normalizeDirectorySeparators(BLOCKS_BASE_PATH);
	}

	/**
	 * @return string
	 */
	public function getConfigPath()
	{
		return $this->normalizeDirectorySeparators($this->basePath.'config/');
	}

	/**
	 * @return string
	 */
	public function getBlocksConfigPath()
	{
		return $this->normalizeDirectorySeparators($this->configPath.'blocks.php');
	}

	/**
	 * @return string
	 */
	public function getPluginsPath()
	{
		return $this->normalizeDirectorySeparators($this->basePath.'plugins/');
	}

	/**
	 * @return string
	 */
	public function getResourcesPath()
	{
		return $this->normalizeDirectorySeparators($this->appPath.'resources/');
	}

	/**
	 * @return string
	 */
	public function getAppPath()
	{
		return $this->normalizeDirectorySeparators(Blocks::app()->basePath.'/');
	}

	public function getFrameworkPath()
	{
		return $this->normalizeDirectorySeparators($this->appPath.'framework/');
	}

	/**
	 * @return string
	 */
	public function getRuntimePath()
	{
		return $this->normalizeDirectorySeparators(Blocks::app()->runtimePath.'/');
	}

	/**
	 * @return string
	 */
	public function getCPTemplatePath()
	{
		return $this->normalizeDirectorySeparators($this->appPath.'templates/');
	}

	/**
	 * @return string
	 */
	public function getMigrationsPath()
	{
		return $this->normalizeDirectorySeparators($this->appPath.'migrations/');
	}

	/**
	 * @return string
	 */
	public function getCommandsPath()
	{
		return $this->normalizeDirectorySeparators($this->appPath.'commands/');
	}

	/**
	 * @return string
	 */
	public function getSiteTemplatePath()
	{
		$siteHandle = Blocks::app()->site->currentSiteByUrl;
		$siteHandle = $siteHandle == null ? 'default' : $siteHandle->handle;

		return $this->normalizeDirectorySeparators($this->basePath.'templates/'.$siteHandle.'/');
	}

	/**
	 * @return string
	 */
	public function getTemplatePath()
	{
		$mode = Blocks::app()->mode;

		// site request or action request coming in through index.php
		if ($mode == AppMode::Site || ($mode == AppMode::Action && !defined('BLOCKS_CP_REQUEST')))
		{
			$templatePath = $this->siteTemplatePath;
		}
		// cp request or action request coming in through admin.php
		elseif ($mode == AppMode::CP || ($mode == AppMode::Action && defined('BLOCKS_CP_REQUEST') && BLOCKS_CP_REQUEST === true))
		{
			$templatePath = $this->cpTemplatePath;

			if (($module = Blocks::app()->urlManager->currentModule) !== null)
				$templatePath = $this->appPath.'modules/'.$module->Id.'/templates/';
		}
		else
		{
			$templatePath = $this->siteTemplatePath;
		}

		return $this->normalizeDirectorySeparators($templatePath);
	}

	/**
	 * @return string
	 */
	public function getTemplateCachePath()
	{
		$cachePath = null;
		$mode = Blocks::app()->mode;

		// site request or action request coming in through index.php
		if ($mode == AppMode::Site || ($mode == AppMode::Action && !defined('BLOCKS_CP_REQUEST')))
		{
			$siteHandle = Blocks::app()->site->currentSiteByUrl;
			$siteHandle = $siteHandle == null ? 'default' : $siteHandle->handle;
			$cachePath = $this->runtimePath.'parsed_templates/sites/'.$siteHandle.'/';
		}
		// cp request or action request coming in through admin.php
		elseif ($mode == AppMode::CP || ($mode == AppMode::Action && defined('BLOCKS_CP_REQUEST') && BLOCKS_CP_REQUEST === true))
		{
			$cachePath = $this->runtimePath.'parsed_templates/cp/';

			if (($module = Blocks::app()->urlManager->currentModule) !== null)
				$cachePath .= 'modules/'.$module->Id.'/';
		}
		else
		{
			$cachePath = $this->runtimePath.'/parsed_templates/';
		}

		if (!is_dir($cachePath))
			mkdir($cachePath, 0777, true);

		return $this->normalizeDirectorySeparators($cachePath);
	}

	/**
	 * @return string
	 */
	public function getSessionPath()
	{
		$path = $this->runtimePath.'sessions/';

		if (!is_dir($path))
			mkdir($path, 0777, true);

		return $this->normalizeDirectorySeparators($path);
	}

	/**
	 * @param $path
	 * @return mixed
	 */
	public function normalizeDirectorySeparators($path)
	{
		return str_replace('\\', '/', $path);
	}
}
