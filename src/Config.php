<?php
/**
 * Copyright (c) 2015 Kerem Güneş
 *
 * MIT License <https://opensource.org/licenses/mit>
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

namespace Froq\Config;

use Froq\Collection\Collection;

/**
 * @package     Froq
 * @subpackage  Froq\Config
 * @object      Froq\Config\Config
 * @author      Kerem Güneş <k-gun@mail.com>
 */
final class Config extends Collection
{
    /**
     * Merge.
     * @param  array $source1
     * @param  array $source2
     * @return array
     */
    public static function merge(array $source1, array $source2): array
    {
        $return = $source2;
        foreach ($source1 as $key => $value) {
            if ($value && is_array($value) && isset($source2[$key]) && is_array($source2[$key])) {
                $value = array_merge($source2[$key], $value);
            }
            $return[$key] = $value;
        }

        return $return;
    }
}
