<?php

    namespace App\Domain\Auth;

    interface SessionInterface
    {
        public function set(string $key, mixed $value): void;
        public function get(string $key): mixed;
        public function has(string $key): bool;
        public function remove(string $key): void;
        public function destroy(): void;
    }