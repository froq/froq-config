<?php
/**
 * MIT License <https://opensource.org/licenses/mit>
 *
 * Copyright (c) 2015 Kerem Güneş
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace froq\config;

use froq\collection\Collection;
use froq\config\ConfigException;

/**
 * Config.
 * @package froq\config
 * @object  froq\config\Config
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   1.0
 */
final class Config extends Collection
{
    /**
     * Update.
     * @param  array $source
     * @return array
     * @since  4.0
     */
    public function update(array $source): self
    {
        $data = self::mergeSources($source, $this->getData());

        $this->setData($data);

        return $this;
    }

    /**
     * Merge sources.
     * @param  array $source1
     * @param  array $source2
     * @return array
     * @since  1.0, 4.0 Renamed as mergeSources() from merge().
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
     * Parse dotenv.
     * @param  string $file
     * @return array
     * @throws froq\config\ConfigException
     * @since  4.1
     */
    public static function parseDotenv(string $file): array
    {
        $ret = [];

        if (!is_file($file)) {
            throw new ConfigException('No .env file exists such "%s"', $file);
        }

        $lines = file($file);
        if ($lines === false) {
            throw new ConfigException('Cannot read .env file "%s" [error: %s]',
                [$file, error_get_last()['message'] ?? 'unknown']);
        }

        foreach ($lines as $i => $line) {
            $line = trim($line);

            // Skip empty & comment lines.
            if (!$line || $line[0] === '#') {
                continue;
            }

            $pairs = array_map('trim', explode('=', $line, 2));
            if (count($pairs) != 2) {
                throw new ConfigException('Invalid .env entry "%s" at file "%s:%s"',
                    [$line, $file, $i + 1]);
            }

            [$name, $value] = $pairs;
            if (isset($ret[$name])) {
                throw new ConfigException('Duplicated .env entry "%s" at file "%s:%s"',
                    [$name, $file, $i + 1]);
            }

            $ret[$name] = $value;
        }

        return $ret;
    }
}
