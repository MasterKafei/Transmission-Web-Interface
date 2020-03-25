<?php

namespace App\Service\Util;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlParser
 * @package App\Service\Util
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class YamlParser
{
	/**
	 * @var FileLocator
	 */
	private $fileLocator;

	/**
	 * @var string
	 */
	private $projectDirectory;

	/**
	 * Yaml parser constructor.
	 * @param FileLocator $fileLocator
	 * @param string $projectDir
	 */
	public function __construct(FileLocator $fileLocator, $projectDir)
	{
		$this->fileLocator = $fileLocator;
		$this->projectDirectory = $projectDir;
	}

	/**
	 * Get content.
	 *
	 * @param string $path
	 * @return array
	 */
	public function getContent($path)
	{
		$file = $this->getRealPath($path);
		return Yaml::parseFile($file) ?? array();
	}

	/**
	 * Set content.
	 *
	 * @param string $path
	 * @param array $content
	 */
	public function setContent($path, array $content)
	{
		$file = $this->getRealPath($path);
		file_put_contents($file, Yaml::dump($content));
	}

	/**
	 * Get real path.
	 *
	 * @param string $path
	 * @return array|string
	 */
	private function getRealPath($path)
	{
		return $this->fileLocator->locate($this->projectDirectory . $path);
	}

	/**
	 * Get directory path.
	 *
	 * @param $path
	 * @return mixed
	 */
	public function getDirectoryPath($path)
	{
		return str_replace($this->projectDirectory, '', dirname($this->getRealPath($path)));
	}
}
