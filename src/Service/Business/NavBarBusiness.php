<?php

namespace App\Service\Business;

use App\Service\Util\YamlParser;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * Class NavBarBusiness
 * @package App\Service\Business
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class NavBarBusiness
{
	const NAV_BAR_CONFIGURATION_FOLDER = '/src/Resources/config/nav_bar/';
	const FILE_EXTENSION = 'yml';

	/**
	 * @var YamlParser
	 */
	private $parser;

	/**
	 * @var FileLocator
	 */
	private $fileLocator;

	/**
	 * @var string
	 */
	private $projectDirectory;

	/**
	 * NavBarBusiness constructor.
	 *
	 * @param YamlParser $parser
	 * @param FileLocator $fileLocator
	 * @param $projectDir
	 */
	public function __construct(YamlParser $parser, FileLocator $fileLocator, $projectDir)
	{
		$this->parser = $parser;
		$this->fileLocator = $fileLocator;
		$this->projectDirectory = $projectDir;
	}

	/**
	 *  Get nav bar configuration file.
	 *
	 * @param $name
	 * @return array
	 */
	public function getNavBar($name)
	{
		return $this->getImportFile(self::NAV_BAR_CONFIGURATION_FOLDER, $name . '.' . self::FILE_EXTENSION);
	}

	/**
	 * Get configuration file.
	 * Recursively allow to use import key word in file.
	 *
	 * @param $prefixPath
	 * @param $path
	 * @return array
	 */
	private function getImportFile($prefixPath, $path)
	{
		$fullPath = null;
		$fullPath = $prefixPath . $path;
		$file = $this->parser->getContent($fullPath);
		$items = array();

		foreach ($file as $item) {
			if (isset($item['import'])) {
				$subItems = $this->getImportFile($this->parser->getDirectoryPath($fullPath) . '/', $item['import']);
				$items = array_merge($items, $subItems);
			} else {
				$items[] = $item;
			}
		}

		return $items;
	}
}
