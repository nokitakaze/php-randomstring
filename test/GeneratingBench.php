<?php

    require_once __DIR__.'/../vendor/autoload.php';

    use \NokitaKaze\RandomString\RandomString;

    class GeneratingBench {
        function provide_strings() {
            return [
                ['length' => 10, 'bit' => 1],
                ['length' => 128, 'bit' => 1],
                ['length' => 10, 'bit' => 7],
                ['length' => 128, 'bit' => 7],
                ['length' => 10, 'bit' => 15],
                ['length' => 128, 'bit' => 15],
            ];
        }

        function provide_strings_openssl() {
            $data = [];
            foreach ([10, 128] as $length) {
                foreach ([7, 15] as $bit) {
                    foreach ([false, true] as $crypto) {
                        $data[] = ['length' => $length, 'bit' => $bit, 'crypto' => $crypto,];
                    }
                }
            }

            return $data;
        }

        /**
         * @param array $params
         *
         * @Revs(5)
         * @Iterations(5)
         *
         * @ParamProviders({"provide_strings"})
         */
        public function bench_direct($params) {
            for ($i = 0; $i < 200; $i++) {
                RandomString::generate($params['length'], $params['bit']);
            }
        }

        /**
         * @param array $params
         *
         * @Revs(5)
         * @Iterations(5)
         *
         * @ParamProviders({"provide_strings"})
         */
        public function bench_trivial($params) {
            $dictionary = RandomString::get_hashes_from_bit($params['bit']);
            for ($i = 0; $i < 200; $i++) {
                RandomString::generate_trivial($params['length'], $dictionary);
            }
        }

        /**
         * @param array $params
         *
         * @Revs(5)
         * @Iterations(5)
         *
         * @ParamProviders({"provide_strings"})
         */
        public function bench_random_bytes($params) {
            if (function_exists('random_bytes')) {
                $dictionary = RandomString::get_hashes_from_bit($params['bit']);
                for ($i = 0; $i < 200; $i++) {
                    RandomString::generate_random_bytes($params['length'], $dictionary);
                }
            }
        }

        /**
         * @param array $params
         *
         * @Revs(5)
         * @Iterations(5)
         *
         * @ParamProviders({"provide_strings_openssl"})
         */
        public function bench_openssl($params) {
            if (function_exists('openssl_random_pseudo_bytes')) {
                $dictionary = RandomString::get_hashes_from_bit($params['bit']);
                for ($i = 0; $i < 200; $i++) {
                    RandomString::generate_openssl($params['length'], $dictionary, $params['crypto']);
                }
            }
        }
    }

?>