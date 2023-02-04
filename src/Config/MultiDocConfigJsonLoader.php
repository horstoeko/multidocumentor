<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Config;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Tools\MultiDocTools;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

/**
 * Class representing a JSON loader for the MultiDoc Configuration
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocConfigJsonLoader
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * The internal JSON object
     *
     * @var \stdClass
     */
    protected $jsonObject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = new MultiDocConfig();
        $this->jsonObject = new \stdClass();
    }

    /**
     * Returns the loaded config
     *
     * @return MultiDocConfig
     */
    public function getConfig(): MultiDocConfig
    {
        return $this->config;
    }

    /**
     * Load configuration from JSON object
     *
     * @return MultiDocConfigJsonLoader
     */
    private function loadFromJsonObject(): MultiDocConfigJsonLoader
    {
        return $this->validateJsonObject()->assignJsonObjectToConfig();
    }

    /**
     * Load configuration from JSON string
     *
     * @param  string $jsonString
     * @return MultiDocConfigJsonLoader
     */
    public function loadFromJsonString(string $jsonString): MultiDocConfigJsonLoader
    {
        $this->jsonObject = json_decode($jsonString);

        if (is_null($this->jsonObject)) {
            throw new \Exception("Error decoding the JSON content.");
        }

        $this->loadFromJsonObject();

        return $this;
    }

    /**
     * LOad configuration from JSON file
     *
     * @param  string $jsonFilename
     * @return MultiDocConfigJsonLoader
     */
    public function loadFromJsonFile(string $jsonFilename): MultiDocConfigJsonLoader
    {
        if (!file_exists($jsonFilename) || !is_readable($jsonFilename)) {
            throw new \Exception("JSON file $jsonFilename not found or not readable.");
        }

        $jsonString = file_get_contents($jsonFilename);

        if ($jsonString === false) {
            throw new \Exception("Content of JSON file $jsonFilename cannot be loaded.");
            return $this;
        }

        $this->loadFromJsonString($jsonString);

        return $this;
    }

    /**
     * Validate JSON against schema
     *
     * @return MultiDocConfigJsonLoader
     */
    private function validateJsonObject(): MultiDocConfigJsonLoader
    {
        $validator = new Validator();
        $validator->validate(
            $this->jsonObject,
            (object)[
                "type" => "object",
                "properties" => (object)[
                    "includedirectories" => (object)[
                        "type" => "array",
                        "default" => [],
                    ],
                    "excludedirectories" => (object)[
                        "type" => "array",
                        "default" => [],
                    ],
                    "outputTo" => (object)[
                        "type" => "string",
                        "default" => "./",
                    ],
                    "outputFormat" => (object)[
                        "type" => "string",
                        "default" => "singlepdf",
                    ],
                    "assetDirectory" => (object)[
                        "type" => "string",
                        "default" => "",
                    ],
                    "htmlDirectory" => (object)[
                        "type" => "string",
                        "default" => "",
                    ],
                    "markdownDirectory" => (object)[
                        "type" => "string",
                        "default" => "",
                    ],
                    "fontsDirectory" => (object)[
                        "type" => "string",
                        "default" => "",
                    ],
                ],
                "required" => [
                    "includedirectories",
                    "outputTo",
                    "outputFormat",
                ],
            ],
            Constraint::CHECK_MODE_NORMAL | Constraint::CHECK_MODE_ONLY_REQUIRED_DEFAULTS | Constraint::CHECK_MODE_EXCEPTIONS
        );

        return $this;
    }

    /**
     * Run mapping
     *
     * @param  object $jsonObject
     * @return MultiDocConfigJsonLoader
     */
    private function assignJsonObjectToConfig(): MultiDocConfigJsonLoader
    {
        $properties = [
            "includedirectories" => "setIncludedirectories",
            "excludedirectories" => "setExcludedirectories",
            "outputTo" => "setOutputTo",
            "outputFormat" => "setOutputFormat",
            "assetDirectory" => "setAssetDirectory",
            "htmlDirectory" => "setHtmlDirectory",
            "markdownDirectory" => "setMarkdownDirectory",
            "fontsDirectory" => "setFontsDirectory",
        ];

        foreach ($properties as $property => $propertySetter) {
            $value = MultiDocTools::dataGet($this->jsonObject, $property);

            if (is_null($value)) {
                continue;
            }

            $this->config->$propertySetter($value);
        }

        return $this;
    }
}
