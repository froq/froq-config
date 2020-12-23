<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-config
 */
declare(strict_types=1);

namespace froq\config;

use froq\config\ConfigException;
use froq\collection\Collection;

/**
 * Config.
 *
 * Represents a config collection entity.
 *
 * @package froq\config
 * @object  froq\config\Config
 * @author  Kerem Güneş
 * @since   1.0
 */
final class Config extends Collection
{
    /**
     * Update current options.
     *
     * @param  array $data
     * @return self
     * @since  4.0
     */
    public function update(array $data): self
    {
        $this->setData(self::mergeSources($data, $this->getData()));

        return $this;
    }

    /**
     * Merge two config sources.
     *
     * @param  array $source1
     * @param  array $source2
     * @return array
     * @since  1.0, 4.0 Derived from merge().
     */
    public static function mergeSources(array $source1, array $source2): array
    {
        $ret = $source2;

        foreach ($source1 as $key => $value) {
            if (
                $value
                && is_array($value)
                && isset($source2[$key])
                && is_array($source2[$key])
            ) {
                $value = array_replace_recursive($source2[$key], $value);
            }

            $ret[$key] = $value;
        }

        return $ret;
    }

    /**
     * Parse a dot-env file and return its options as array.
     *
     * @param  string $file
     * @return array
     * @throws froq\config\ConfigException
     * @since  4.1
     */
    public static function parseDotenv(string $file): array
    {
        $ret = [];

        if (!is_file($file)) {
            throw new ConfigException('No .env file exists such `%s`', $file);
        }

        $lines = file($file);
        if ($lines === false) {
            throw new ConfigException('Cannot read .env file `%s`, [error: %s]', [$file, '@error']);
        }

        foreach ($lines as $i => $line) {
            $line = trim($line);

            // Skip empty & comment lines.
            if (!$line || $line[0] === '#') {
                continue;
            }

            $pairs = array_map('trim', explode('=', $line, 2));
            if (count($pairs) != 2) {
                throw new ConfigException('Invalid .env entry `%s` at file `%s:%s`', [$line, $file, $i + 1]);
            }

            [$name, $value] = $pairs;
            if (isset($ret[$name])) {
                throw new ConfigException('Duplicated .env entry `%s` at file `%s:%s`', [$name, $file, $i + 1]);
            }

            $ret[$name] = $value;
        }

        return $ret;
    }
}
