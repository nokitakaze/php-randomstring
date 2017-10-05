<?php

    require_once __DIR__.'/../vendor/autoload.php';

    use \NokitaKaze\RandomString\RandomString;

    class GeneratingBench {
        function provide_strings() {
            return [
                ['length' => 10, 'dictionary' => RandomString::get_hashes_from_bit(1)],
                ['length' => 128, 'dictionary' => RandomString::get_hashes_from_bit(1)],
                ['length' => 10, 'dictionary' => RandomString::get_hashes_from_bit(7)],
                ['length' => 128, 'dictionary' => RandomString::get_hashes_from_bit(7)],
                ['length' => 10, 'dictionary' => RandomString::get_hashes_from_bit(15)],
                ['length' => 128, 'dictionary' => RandomString::get_hashes_from_bit(15)],
            ];
        }

        function provide_strings_openssl() {
            return [
                ['length' => 10, 'dictionary' => RandomString::get_hashes_from_bit(7), 'crypto' => true,],
                ['length' => 128, 'dictionary' => RandomString::get_hashes_from_bit(7), 'crypto' => false,],
                ['length' => 10, 'dictionary' => RandomString::get_hashes_from_bit(15), 'crypto' => true,],
                ['length' => 128, 'dictionary' => RandomString::get_hashes_from_bit(15), 'crypto' => false,],
            ];
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
                RandomString::generate($params['length'], $params['dictionary']);
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
            for ($i = 0; $i < 200; $i++) {
                RandomString::generate_trivial($params['length'], $params['dictionary']);
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
                for ($i = 0; $i < 200; $i++) {
                    RandomString::generate_random_bytes($params['length'], $params['dictionary']);
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
                for ($i = 0; $i < 200; $i++) {
                    RandomString::generate_openssl($params['length'], $params['dictionary'], $params['crypto']);
                }
            }
        }
    }

?>