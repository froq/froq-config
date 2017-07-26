<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *     <k-gun@mail.com>
 *
 * GNU General Public License v3.0
 *     <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
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
    public static final function merge(array $source1, array $source2): array
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
