<?php

    use \PHPUnit\Framework\TestCase;
    use \NokitaKaze\RandomString\RandomString;

    class RandomStringTest extends TestCase {
        function dataGenerate_random() {
            $data = [];
            foreach ([
                         [1, '0-9'],
                         [2, 'a-z'],
                         [4, 'A-Z'],
                         [7, '0-9a-zA-Z'],
                         [range(0, 9), '0-9'],
                         [15, '0-9a-zA-Z\\_;!?\\.\'"#\\*\\/&#%`^+=~\\$@-'],
                     ] as $bit_mutation) {
                $hashes = $bit_mutation[0];
                $reg_exp = $bit_mutation[1];
                $letters = RandomString::get_hashes_from_bit($hashes);

                $closure_direct = function ($length) use ($hashes) {
                    return RandomString::generate($length, $hashes);
                };
                $closure_trivial = function ($length) use ($hashes, $letters) {
                    return RandomString::generate_trivial($length, $letters);
                };
                if (function_exists('random_bytes')) {
                    $closure_random_bytes = function ($length) use ($hashes, $letters) {
                        return RandomString::generate_random_bytes($length, $letters);
                    };
                } else {
                    $closure_random_bytes = null;
                }
                if (function_exists('random_bytes')) {
                    $closure_openssl = function ($length, $args) use ($hashes, $letters) {
                        return RandomString::generate_openssl($length, $letters, $args[0]);
                    };
                } else {
                    $closure_openssl = null;
                }

                if (is_int($hashes)) {
                    switch ($hashes) {
                        case 1:
                        case 2:
                        case 4:
                            $mt = [RandomString::DEFAULT_KEY_LENGTH, 1024];
                            break;
                        default:
                            $mt = [10, RandomString::DEFAULT_KEY_LENGTH, 1024];
                    }
                } else {
                    $mt = [RandomString::DEFAULT_KEY_LENGTH, 1024];
                }

                foreach ($mt as $length) {
                    $data[] = [$closure_direct, $length, $reg_exp, $letters];
                    $data[] = [$closure_trivial, $length, $reg_exp, $letters];
                    if (!is_null($closure_openssl)) {
                        $data[] = [$closure_openssl, $length, $reg_exp, $letters, [false]];
                        $data[] = [$closure_openssl, $length, $reg_exp, $letters, [true]];
                    }
                    if (!is_null($closure_random_bytes)) {
                        $data[] = [$closure_random_bytes, $length, $reg_exp, $letters];
                    }
                }

                unset($hashes, $letters, $closure_direct, $closure_trivial, $closure_random_bytes, $closure_openssl);
            }

            return $data;
        }


        /**
         * Санация части regexp для добавления напрямую в regexp
         *
         * @param string $text Часть, которую над санировать
         *
         * @return string
         */
        protected static function sad_safe_reg($text) {
            $ar = '.-\\/[]{}()*?+^$|';
            $s = '';
            for ($i = 0; $i < strlen($text); $i++) {
                if (strpos($ar, $text[$i]) !== false) {
                    $s .= '\\'.$text[$i];
                } else {
                    $s .= $text[$i];
                }
            }

            return $s;
        }

        /**
         * @param string   $closure
         * @param integer  $length
         * @param string[] $reg_exp
         * @param string[] $letters
         * @param mixed    $args
         *
         * @dataProvider dataGenerate_random
         */
        function testGenerate($closure, $length, $reg_exp, $letters, $args = null) {
            $letters_count = [];
            foreach ($letters as &$letter) {
                $letters_count[$letter] = 0;
            }
            unset($letter);

            $max = intval(ceil(200 * 1024 / $length));
            for ($i = 0; $i < $max; $i++) {
                $value = $closure($length, $args);
                $this->assertRegExp(sprintf('_^[%s]{%d,%d}$_', $reg_exp, $length, $length), $value);
                for ($j = 0; $j < strlen($value); $j++) {
                    $letters_count[substr($value, $j, 1)]++;
                }
            }
            unset($value, $i, $j);

            arsort($letters_count, SORT_NUMERIC);
            $this->assertLessThan($max * $length / count($letters) * 1.1,
                $letters_count[array_keys($letters_count)[0]]);
            $this->assertGreaterThan($max * $length / count($letters) * 0.9,
                $letters_count[array_keys($letters_count)[count($letters_count) - 1]]);
        }
    }

?>