<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alejandro Peña Florentín (alejandropenaflorentin@gmail.com).
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Tight;

/**
 * Description of Utils
 *
 * @author Alejandro Peña Florentín (alejandropenaflorentin@gmail.com)
 */
class Utils
{

    /**
     * Removes the first ocurrence of a substring in a string
     * @param string $string String
     * @param string $substrToRemove Substring to remove
     * @return string String without $substrToRemove or $string if $substrToRemove hasn't been found
     */
    public static function removeSubstring($string, $substrToRemove) {
        // Check index position on $string
        $firstIndex = strpos($string, $substrToRemove);
        // If $firstIndex is false $substrToRemove wasn't found in $string
        if ($firstIndex === FALSE)
            return $string;
        // Check last index
        $lastIndex = $firstIndex + strlen($substrToRemove);
        // Get the substring before $substrToRemove
        $pre = substr($string, 0, $firstIndex);
        // Get the substring after $substrToRemove
        $post = substr($string, $lastIndex);
        // Return $pre and $post
        return $pre . $post;
    }

    /**
     * Check if a substring is in a string
     * @param string $string String
     * @param string $strToSearch Substring to search
     * @return boolean TRUE on success,FALSE otherwise
     */
    public static function inString($string, $strToSearch) {
        return (strpos($string, $strToSearch) !== FALSE);
    }

    /**
     * Adds a trailing slash to a given path.
     * 
     * This method does not add the trailing slash if is already set.
     * 
     * @param string $path Path
     * @return string 
     */
    public static function addTrailingSlash($path) {
        if (null !== $path && is_string($path) && !empty($path)) {
            if (substr($path, -1) !== "/") {
                $path .="/";
            }
        }
        return $path;
    }

    /**
     * Replace all the backslash for slashes
     * 
     * @param string $path Path
     * @return string Converted path
     */
    public static function filterPath($path) {
        return self::addTrailingSlash(str_replace(DIRECTORY_SEPARATOR, "/", str_replace("\\", "/", $path)));
    }

    /**
     * Replace repeated substring.
     * 
     * @param string $string String
     * @param string $substring Substring
     * @return string
     */
    public static function removeDouble($string, $substring) {
        return str_replace($substring . $substring, $substring, $string);
    }

}
