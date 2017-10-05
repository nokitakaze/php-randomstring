<?php

    namespace NokitaKaze\RandomString;

    class RandomString {
        const INCLUDE_NUMERIC = 1;
        const INCLUDE_LOWER_LETTERS = 2;
        const INCLUDE_UPPER_LETTERS = 4;
        const INCLUDE_PUNCTUATION = 8;

        /**
         * ceil(LOG(10^120)/LOG(62))*2
         * @doc https://en.wikipedia.org/wiki/Shannon_number
         * @doc https://en.wikipedia.org/wiki/Birthday_problem
         */
        const DEFAULT_KEY_LENGTH = 134;

        static $dictionaries = [
            0 => [],
            1 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            2 => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                  'w', 'x', 'y', 'z'],
            3 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
                  'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
            4 => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
                  'W', 'X', 'Y', 'Z'],
            5 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
                  'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            6 => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                  'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                  'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            7 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
                  'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                  'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
            8 => ['_', '-', ';', '!', '?', '.', '\'', '"', '@', '*', '/', '&', '#', '%', '`', '^', '+', '=', '~', '$'],
            9 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_', '-', ';', '!', '?', '.', '\'', '"', '@', '*', '/', '&',
                  '#', '%', '`', '^', '+', '=', '~', '$'],
            10 => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                   'w', 'x', 'y', 'z', '_', '-', ';', '!', '?', '.', '\'', '"', '@', '*', '/', '&', '#', '%', '`', '^', '+', '=',
                   '~', '$'],
            11 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
                   'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '_', '-', ';', '!', '?', '.', '\'', '"',
                   '@', '*', '/', '&', '#', '%', '`', '^', '+', '=', '~', '$'],
            12 => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
                   'W', 'X', 'Y', 'Z', '_', '-', ';', '!', '?', '.', '\'', '"', '@', '*', '/', '&', '#', '%', '`', '^', '+', '=',
                   '~', '$'],
            13 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
                   'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '_', '-', ';', '!', '?', '.', '\'', '"',
                   '@', '*', '/', '&', '#', '%', '`', '^', '+', '=', '~', '$'],
            14 => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                   'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                   'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '_', '-', ';', '!', '?', '.', '\'', '"', '@', '*', '/', '&', '#', '%',
                   '`', '^', '+', '=', '~', '$'],
            15 => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
                   'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                   'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '_', '-', ';', '!',
                   '?', '.', '\'', '"', '@', '*', '/', '&', '#', '%', '`', '^', '+', '=', '~', '$'],
        ];

        /**
         * @param integer|string[] $bit
         *
         * @return string[]
         */
        static function get_hashes_from_bit($bit) {
            if (is_int($bit)) {
                return self::$dictionaries[$bit];
            } else {
                return $bit;
            }
        }

        /**
         * Generating a fairly unique string
         *
         * @param integer          $length String size
         * @param integer|string[] $bit
         *
         * @return string
         */
        static function generate($length = self::DEFAULT_KEY_LENGTH, $bit = 7) {
            $letters = self::get_hashes_from_bit($bit);
            if (function_exists('random_bytes')) {
                return self::generate_random_bytes($length, $letters);
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                return self::generate_openssl($length, $letters, true);
            } else {
                return self::generate_trivial($length, $letters);
            }
        }

        /**
         * Generating a fairly unique string via mt_rand
         *
         * @param integer  $length String size
         * @param string[] $letters
         *
         * @return string
         */
        static function generate_trivial($length = self::DEFAULT_KEY_LENGTH, array $letters) {
            $letters_count_minus = count($letters) - 1;
            $hash = '';
            for ($i = 0; $i < $length; $i++) {
                $hash .= $letters[mt_rand(0, $letters_count_minus)];
            }

            return $hash;
        }

        /**
         * Generating a fairly unique string via OpenSSL
         *
         * At the moment the algorithm is not optimal
         *
         * @param integer  $length String size
         * @param string[] $letters
         * @param boolean  $crypto Cryptographic security is necessary
         *
         * @return string
         * @throws \Exception
         */
        static function generate_openssl($length = self::DEFAULT_KEY_LENGTH, array $letters, $crypto = true) {
            $callable = function ($length) use ($crypto) {
                return openssl_random_pseudo_bytes($length, $crypto);
            };
            if (count($letters) <= 64) {
                return self::generate_string_from_entropy_short64($callable, $length, $letters);
            } elseif (count($letters) <= 256) {
                return self::generate_string_from_entropy_byte($callable, $length, $letters);
            } else {
                throw new \Exception();
            }
        }

        /**
         * Generating a fairly unique string via Random Bytes
         *
         * @param integer  $length
         * @param string[] $letters
         *
         * @return string
         * @doc http://php.net/manual/ru/function.random-bytes.php
         * @throws \Exception
         */
        static function generate_random_bytes($length = self::DEFAULT_KEY_LENGTH, array $letters) {
            $callable = 'random_bytes';
            if (count($letters) <= 64) {
                return self::generate_string_from_entropy_short64($callable, $length, $letters);
            } elseif (count($letters) <= 256) {
                return self::generate_string_from_entropy_byte($callable, $length, $letters);
            } else {
                throw new \Exception('Not implemented');
            }
        }

        /**
         * Generating a fairly unique string via some entropy source
         *
         * At the moment the algorithm is not optimal
         *
         * @param callable $callable
         * @param integer  $length
         * @param string[] $letters
         *
         * @return string
         * @hint Этот алгоритм работает только при count($letters)<=64, так как 2^6 = 64
         */
        static function generate_string_from_entropy_short64($callable, $length = self::DEFAULT_KEY_LENGTH, array $letters) {
            $letters_len = count($letters);
            $hash = '';
            $raw = call_user_func($callable, $length * 10 + 6);
            while (strlen($hash) < $length) {
                if (strlen($raw) < 4) {
                    $raw .= call_user_func($callable, max(($length - strlen($hash)) * 10 + 6, 16));
                }
                $int = unpack('V', substr($raw, 0, 4))[1];
                $raw = substr($raw, 4);
                // 4 = floor(4 * 8 / 6) - 1
                for ($i = 0; ($i < 4) and (strlen($hash) < $length); $i++) {
                    $b = $int & 0b111111;// 0b111111 = 2^6 - 1
                    $int >>= 6;
                    if ($b < $letters_len) {
                        $hash .= $letters[$b];
                    }
                }
            }

            return substr($hash, 0, $length);
        }

        /**
         * Generating a fairly unique string via some entropy source
         *
         * At the moment the algorithm is not optimal
         *
         * @param callable $callable
         * @param integer  $length
         * @param string[] $letters
         *
         * @return string
         * @hint Этот алгоритм работает только при count($letters)<=256, так как 2^8 = 256
         */
        static function generate_string_from_entropy_byte($callable, $length = self::DEFAULT_KEY_LENGTH, array $letters) {
            $letters_len = count($letters);
            $hash = '';
            $raw = call_user_func($callable, $length);
            while (strlen($hash) < $length) {
                if (strlen($raw) < 4) {
                    $raw .= call_user_func($callable, max($length - strlen($hash), 16));
                }
                $int = unpack('V', substr($raw, 0, 4))[1];
                $raw = substr($raw, 4);
                // 4 = 4 * 8 / 8
                for ($i = 0; ($i < 4) and (strlen($hash) < $length); $i++) {
                    $b = $int & 0xFF;// 0xFF = 2^8 - 1
                    $int >>= 8;
                    if ($b < $letters_len) {
                        $hash .= $letters[$b];
                    }
                }
            }

            return substr($hash, 0, $length);
        }

        // @todo Протестировать: unpack as bytes
        // @todo Протестировать: генерировать килобайты, и потом str_replace'ить
    }

?>